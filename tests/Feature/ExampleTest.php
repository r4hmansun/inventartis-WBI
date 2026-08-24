<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test root redirects to dashboard or login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated regular user accesses age-friendly user portal.
     */
    public function test_authenticated_regular_user_sees_user_portal(): void
    {
        $dept = Department::create(['code' => 'HE', 'name' => 'Teknik Elektro HE', 'is_active' => true]);
        $user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'user.he@wbi.co.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'department_id' => $dept->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Portal Inventaris Unit Anda', false);
        $response->assertSee('Halo, Budi Santoso', false);
        $response->assertSee('Barang di Unit Anda');
        $response->assertSee('Panduan Minta Kode Aset');
        $response->assertSee('Cek Aturan Harga Barang');
    }

    /**
     * Test regular user can access asset list and view assets.
     */
    public function test_regular_user_can_view_asset_list_and_details(): void
    {
        $dept = Department::create(['code' => 'HE', 'name' => 'Teknik Elektro HE', 'is_active' => true]);
        $user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'user.he@wbi.co.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'department_id' => $dept->id,
        ]);

        $asset = \App\Models\Asset::create([
            'asset_code' => 'AST/HE/08/2026/001',
            'name' => 'Laptop Asus ExpertBook',
            'purchase_price' => 8500000,
            'purchase_date' => '2026-08-01',
            'current_department_id' => $dept->id,
            'status' => 'active',
            'created_by_user_id' => $user->id,
        ]);

        $indexResponse = $this->actingAs($user)->get('/assets');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Laptop Asus ExpertBook');
        $indexResponse->assertSee('AST/HE/08/2026/001');

        $showResponse = $this->actingAs($user)->get('/assets/' . $asset->id);
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Laptop Asus ExpertBook');
        $showResponse->assertSee('Detail Informasi Aset');
    }

    /**
     * Test authenticated admin sees executive dashboard.
     */
    public function test_authenticated_admin_sees_executive_dashboard(): void
    {
         $dept = Department::create(['code' => 'IT', 'name' => 'IT Dept', 'is_active' => true]);
         $user = User::create([
             'name' => 'Super Admin',
             'email' => 'admin@wbi.co.id',
             'password' => bcrypt('password'),
             'role' => 'admin',
             'department_id' => $dept->id,
         ]);

         $response = $this->actingAs($user)->get('/dashboard');
         $response->assertStatus(200);
         $response->assertSee('Ringkasan Eksekutif &amp; Operasional', false);
         $response->assertSee('Total Aset Terdata');
         $response->assertSee('Siklus Mutasi Aset');
     }
}



