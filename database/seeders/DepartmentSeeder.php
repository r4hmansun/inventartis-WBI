<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['code' => 'GDG-INV', 'name' => 'Gudang Inventaris'],
            ['code' => 'KEU', 'name' => 'Bagian Keuangan'],
            ['code' => 'INV', 'name' => 'Bagian Inventaris'],
            ['code' => 'HE', 'name' => 'Heavy Equipment'],
            ['code' => 'WBIC', 'name' => 'WBI Center'],
            ['code' => 'PKA', 'name' => 'PKA'],
            ['code' => 'GA', 'name' => 'General Affairs'],
            ['code' => 'IT', 'name' => 'Information Technology'],
            ['code' => 'HR', 'name' => 'Human Resources'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['code' => $dept['code']],
                $dept
            );
        }
    }
}
