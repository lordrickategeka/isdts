<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user
        $user = User::first();

        // BCC Project
        Project::create([
            'name' => 'BCC Internal Project',
            'project_code' => 'PROJ-BCC-' . now()->format('Ymd'),
            'description' => 'BCC internal infrastructure and connectivity project',
            'start_date' => now(),
            'end_date' => now()->addMonths(12),
            'estimated_budget' => 200000000,
            'status' => 'in_progress',
            'priority' => 'high',
            'objectives' => 'Improve BCC internal network infrastructure and connectivity',
            'deliverables' => 'Network upgrades, fiber installation, security implementation',
            'created_by' => $user ? $user->id : 1,
        ]);

        // Electoral Commission EC Project
        Project::create([
            'name' => 'Electoral Commission EC',
            'project_code' => 'PROJ-EC-' . now()->format('Ymd'),
            'description' => 'Electoral Commission connectivity and infrastructure project',
            'start_date' => now(),
            'end_date' => now()->addMonths(6),
            'estimated_budget' => 500000000,
            'status' => 'in_progress',
            'priority' => 'high',
            'objectives' => 'Provide reliable connectivity infrastructure for Electoral Commission offices nationwide',
            'deliverables' => 'Fiber connectivity, MPLS network, backup connectivity, network security',
            'created_by' => $user ? $user->id : 1,
        ]);

        // UNEB Project
        Project::create([
            'name' => 'UNEB Project',
            'project_code' => 'PROJ-UNEB-' . now()->format('Ymd'),
            'description' => 'Uganda National Examinations Board connectivity project',
            'start_date' => now(),
            'end_date' => now()->addMonths(8),
            'estimated_budget' => 350000000,
            'status' => 'budget_planning',
            'priority' => 'medium',
            'objectives' => 'Establish secure and reliable connectivity for UNEB offices and examination centers',
            'deliverables' => 'Internet connectivity, VPN setup, secure network infrastructure, surveillance systems',
            'created_by' => $user ? $user->id : 1,
        ]);
    }
}
