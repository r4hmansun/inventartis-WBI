<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Department;
use App\Models\MutationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MutationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_asset_mutation_dual_approval_and_execution_flow(): void
    {
        // 1. Setup departments
        $gudang = Department::create(['code' => 'GDG-INV', 'name' => 'Gudang Inventaris']);
        $deptHE = Department::create(['code' => 'HE', 'name' => 'Human Enterprise']);
        $deptWBIC = Department::create(['code' => 'WBIC', 'name' => 'WBI Center']);

        // 2. Setup users
        $userHE = User::factory()->create([
            'name' => 'Staff HE',
            'email' => 'user.he@wbi.co.id',
            'role' => 'user',
            'department_id' => $deptHE->id,
        ]);

        $userWBIC = User::factory()->create([
            'name' => 'Staff WBIC',
            'email' => 'user.wbic@wbi.co.id',
            'role' => 'user',
            'department_id' => $deptWBIC->id,
        ]);

        $inventoryStaff = User::factory()->create([
            'name' => 'Petugas Inventaris',
            'email' => 'inventory@wbi.co.id',
            'role' => 'inventory',
            'department_id' => $gudang->id,
        ]);

        // 3. Setup asset in Dept HE
        $asset = Asset::create([
            'asset_code' => 'AST/HE/08/2026/0001',
            'name' => 'Laptop Dell Latitude 7420',
            'purchase_price' => 15000000,
            'purchase_date' => now()->subMonth(),
            'current_department_id' => $deptHE->id,
            'status' => 'active',
            'created_by_user_id' => $userHE->id,
        ]);

        // STEP 1: Staff HE initiates mutation form (Flow 2)
        $response = $this->actingAs($userHE)->post(route('mutations.store'), [
            'from_department_id' => $deptHE->id,
            'to_department_id' => $deptWBIC->id,
            'reason' => 'Penyerahan laptop kerja untuk kebutuhan operasional inkubator WBIC.',
            'asset_ids' => [$asset->id],
            'item_conditions' => [$asset->id => 'good'],
            'sender_approval_confirm' => '1',
        ]);

        $mutation = MutationForm::first();
        $this->assertNotNull($mutation);
        $response->assertRedirect(route('mutations.show', $mutation));

        $this->assertEquals('waiting_receiver', $mutation->status);
        $this->assertNotNull($mutation->sender_signed_at);
        $this->assertEquals($userHE->id, $mutation->sender_user_id);
        $this->assertEquals(1, $mutation->items()->count());

        // STEP 2: Receiver Approval by Staff WBIC
        $approveResponse = $this->actingAs($userWBIC)->post(route('mutations.approve-receiver', $mutation));
        $approveResponse->assertRedirect(route('mutations.show', $mutation));

        $mutation->refresh();
        $this->assertEquals('ready_for_execution', $mutation->status);
        $this->assertNotNull($mutation->receiver_signed_at);
        $this->assertEquals($userWBIC->id, $mutation->receiver_user_id);
        $this->assertTrue($mutation->hasDualApproval());
        $this->assertTrue($mutation->isReadyForExecution());

        // STEP 3: Execution & Archival by Inventory Staff
        $execResponse = $this->actingAs($inventoryStaff)->post(route('mutations.execute', $mutation));
        $execResponse->assertRedirect(route('mutations.show', $mutation));

        $mutation->refresh();
        $asset->refresh();

        // Verify status archived and asset moved to WBIC
        $this->assertEquals('archived', $mutation->status);
        $this->assertEquals($inventoryStaff->id, $mutation->executed_by_user_id);
        $this->assertEquals($deptWBIC->id, $asset->current_department_id);

        // Verify immutable asset history created
        $this->assertDatabaseHas('asset_histories', [
            'asset_id' => $asset->id,
            'from_department_id' => $deptHE->id,
            'to_department_id' => $deptWBIC->id,
            'actor_user_id' => $inventoryStaff->id,
            'action_type' => 'department_mutation',
        ]);

        // STEP 4: Official Berita Acara Print View
        $printResponse = $this->actingAs($userHE)->get(route('mutations.print', $mutation));
        $printResponse->assertStatus(200);
        $printResponse->assertSee($mutation->form_number);
        $printResponse->assertSee('BERITA ACARA SERAH TERIMA ASET INVENTARIS');
    }
}
