<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user as default manager
        $adminUser = User::first();

        // Create root/parent departments
        $departments = [
            [
                'name' => 'Executive Management',
                'code' => 'EXEC',
                'description' => 'Executive leadership and strategic planning',
                'parent_id' => null,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Human resources and personnel management',
                'parent_id' => null,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Finance & Accounting',
                'code' => 'FIN',
                'description' => 'Financial management and accounting operations',
                'parent_id' => null,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'IT infrastructure and software development',
                'parent_id' => null,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Sales & Marketing',
                'code' => 'SALES',
                'description' => 'Sales operations and marketing initiatives',
                'parent_id' => null,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Operations',
                'code' => 'OPS',
                'description' => 'Business operations and service delivery',
                'parent_id' => null,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Customer Support',
                'code' => 'SUPPORT',
                'description' => 'Customer service and technical support',
                'parent_id' => null,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Create some sub-departments as examples
        $itDept = Department::where('code', 'IT')->first();
        if ($itDept) {
            Department::create([
                'name' => 'Software Development',
                'code' => 'IT-DEV',
                'description' => 'Software development and engineering',
                'parent_id' => $itDept->id,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ]);

            Department::create([
                'name' => 'IT Support',
                'code' => 'IT-SUP',
                'description' => 'Technical support and helpdesk',
                'parent_id' => $itDept->id,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ]);
        }

        $hrDept = Department::where('code', 'HR')->first();
        if ($hrDept) {
            Department::create([
                'name' => 'Recruitment',
                'code' => 'HR-REC',
                'description' => 'Talent acquisition and recruitment',
                'parent_id' => $hrDept->id,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ]);

            Department::create([
                'name' => 'Training & Development',
                'code' => 'HR-TRN',
                'description' => 'Employee training and development programs',
                'parent_id' => $hrDept->id,
                'manager_id' => $adminUser?->id,
                'is_active' => true,
            ]);
        }

        $this->command->info('Departments seeded successfully!');
    }
}
