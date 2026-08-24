<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed departments first
        $this->call(DepartmentSeeder::class);

        // Create default Super Admin user
        $adminDept = Department::where('code', 'IT')->first();

        User::firstOrCreate(
            ['email' => 'admin@wbi.co.id'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'department_id' => $adminDept?->id,
                'role' => 'admin',
            ]
        );

        // Create default Finance user
        $keuDept = Department::where('code', 'KEU')->first();

        User::firstOrCreate(
            ['email' => 'keuangan@wbi.co.id'],
            [
                'name' => 'Staff Keuangan',
                'password' => 'password',
                'department_id' => $keuDept?->id,
                'role' => 'finance',
            ]
        );

        // Create default Inventory user
        $invDept = Department::where('code', 'INV')->first();

        User::firstOrCreate(
            ['email' => 'inventaris@wbi.co.id'],
            [
                'name' => 'Staff Inventaris',
                'password' => 'password',
                'department_id' => $invDept?->id,
                'role' => 'inventory',
            ]
        );

        // Create sample departmental users
        $heDept = Department::where('code', 'HE')->first();
        $wbicDept = Department::where('code', 'WBIC')->first();
        $pkaDept = Department::where('code', 'PKA')->first();

        User::firstOrCreate(
            ['email' => 'user.he@wbi.co.id'],
            [
                'name' => 'User HE',
                'password' => 'password',
                'department_id' => $heDept?->id,
                'role' => 'user',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user.wbic@wbi.co.id'],
            [
                'name' => 'User WBIC',
                'password' => 'password',
                'department_id' => $wbicDept?->id,
                'role' => 'user',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user.pka@wbi.co.id'],
            [
                'name' => 'User PKA',
                'password' => 'password',
                'department_id' => $pkaDept?->id,
                'role' => 'user',
            ]
        );
    }
}
