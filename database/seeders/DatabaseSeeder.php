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

        // Create sample departmental users for ALL departments
        $departmentUsers = [
            ['code' => 'HE', 'email' => 'user.he@wbi.co.id', 'name' => 'User Heavy Equipment'],
            ['code' => 'WBIC', 'email' => 'user.wbic@wbi.co.id', 'name' => 'User WBI Center'],
            ['code' => 'PKA', 'email' => 'user.pka@wbi.co.id', 'name' => 'User PKA'],
            ['code' => 'GA', 'email' => 'user.ga@wbi.co.id', 'name' => 'User General Affairs'],
            ['code' => 'IT', 'email' => 'user.it@wbi.co.id', 'name' => 'User IT Staff'],
            ['code' => 'HR', 'email' => 'user.hr@wbi.co.id', 'name' => 'User Human Resources'],
        ];

        foreach ($departmentUsers as $deptUser) {
            $dept = Department::where('code', $deptUser['code'])->first();
            User::firstOrCreate(
                ['email' => $deptUser['email']],
                [
                    'name' => $deptUser['name'],
                    'password' => 'password',
                    'department_id' => $dept?->id,
                    'role' => 'user',
                ]
            );
        }
    }
}
