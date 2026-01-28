<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Create permissions FIRST
        $permissions = [
            // Client Management
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            'approve_clients',
            'export_clients',
            'edit-client-authorization',

            // Service Management
            'view_services',
            'create_services',
            'edit_services',
            'delete_services',
            'assign_services',

            // Product Management
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'manage_pricing',

            // Vendor Management
            'view_vendors',
            'create_vendors',
            'edit_vendors',
            'delete_vendors',
            'approve_vendors',

            // Financial Management
            'view_financial_reports',
            'manage_payments',
            'approve_invoices',
            'manage_credit',
            'view_revenue_reports',
            'access_financial_dashboard',

            // Sales Management
            'view_sales_reports',
            'create_quotations',
            'approve_quotations',
            'manage_deals',
            'view_sales_pipeline',

            // Network Operations
            'view_network_reports',
            'plan_network',
            'approve_implementations',
            'manage_installations',
            'access_technical_data',
            'view_feasibility',
            'create_feasibility',
            'edit_feasibility',
            'approve_feasibility',

            // Business Analysis
            'view_analytics',
            'generate_reports',
            'export_reports',
            'access_dashboard',
            'view_kpi_metrics',

            // Sidebar-only or missing permissions (full, deduplicated)
            'view_sales_module',
            'view_marketing_module',
            'view_parties_module',
            'view_support_module',
            'view_operations_module',
            'view_work_orders',
            'view_tasks_assignments',
            'view_field_operations',
            'view_activity_logs',
            'view_Operational_reports',
            'view_assets_module',
            'view_inventory',
            'view_finance_module',
            'wallets_credits_management',
            'view_taxes_discounts',
            'view_custom_reports',
            'view_automation_module',
            'view_integrations_module',
            'view_administration_module',
            'view_currencies',
            'view_departments',

            // User mgt
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'assign_roles',

            // Role & Permission mgt
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'manage_permissions',

            // Form mgt
            'view_forms',
            'create_forms',
            'edit_forms',
            'delete_forms',
            'view_submissions',
            'manage_forms',

            // System Settings
            'manage_settings',
            'view_audit_logs',
            'manage_system_config',

            // survey mgt
            'create_survey_ticket',
            'can-assign-survey',

            //Leads
            'create_leads',
            'view_leads',

            //projects
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',

            // Project Budget Management
            'view_project_budget',
            'create_budget_items',
            'edit_budget_items',
            'delete_budget_items',
            'approve_project_budget',
            'submit_budget_for_approval',

            // Project Item Availability
            'view_item_availability',
            'check_item_availability',
            'update_item_availability',
            'manage_inventory',

            //all reports
            'view_sales_reports',
            'view_support_reports',
            'view_Operational_reports',
            'view_financial_reports',
            'view_custom_reports',
            'view_network_reports',

            // integrated network devices
            'can-delete-router-details',

            //inventory
            'view_inventory',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Engineer Role
        $engineer = Role::firstOrCreate(
            ['name' => 'engineer'],
            ['description' => 'Engineer']
        );
        $engineer->syncPermissions([
            'view_clients',
            'view_services',
            'view_products',
            'view_vendors',
            'view_network_reports',
            'access_technical_data',
            'access_dashboard',
            'view_feasibility',
            'create_survey_ticket',
            'can-assign-survey',
        ]);

        // Create roles and assign permissions

        // Super Admin Role
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['description' => 'Super Administrator']
        );
        // Super Admin gets all permissions
        $superAdmin->syncPermissions(Permission::all());

        // Salesperson Role
        $salesperson = Role::firstOrCreate(
            ['name' => 'salesperson'],
            ['description' => 'Salesperson']
        );
        $salesperson->syncPermissions([
            'view_clients',
            'create_clients',
            'edit_clients',
            'view_services',
            'view_products',
            'create_quotations',
            'view_sales_reports',
            'view_sales_pipeline',
            'manage_deals',
            'access_dashboard',
            'view_feasibility',
            'view_vendors',
        ]);

        // Sales Role (Legacy - keeping for backward compatibility)
        $sales = Role::firstOrCreate(['name' => 'sales']);
        $sales->syncPermissions([
            'view_clients',
            'create_clients',
            'edit_clients',
            'view_services',
            'view_products',
            'create_quotations',
            'view_sales_reports',
            'view_sales_pipeline',
            'manage_deals',
            'access_dashboard',
        ]);

        // Sales Manager Role
        $salesManager = Role::firstOrCreate(['name' => 'sales_manager']);
        $salesManager->syncPermissions([
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            'view_services',
            'assign_services',
            'view_products',
            'create_quotations',
            'approve_quotations',
            'view_sales_reports',
            'view_sales_pipeline',
            'manage_deals',
            'access_dashboard',
            'view_kpi_metrics',
            'export_clients',
            'export_reports',
        ]);

        // Customer Experience (CX)
        $cx = Role::firstOrCreate(
            ['name' => 'customer_experience'],
            ['description' => 'CX']
        );
        $cx->syncPermissions([
            'view_clients',
            'edit_clients',
            'view_services',
            'view_forms',
            'view_submissions',
            'access_dashboard',
            'view_analytics',
        ]);

        // Chief Commercial Officer (CCO)
        $cco = Role::firstOrCreate(
            ['name' => 'chief_commercial'],
            ['description' => 'CCO']
        );
        $cco->syncPermissions([
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            'approve_clients',
            'export_clients',
            'view_services',
            'create_services',
            'edit_services',
            'delete_services',
            'assign_services',
            'view_products',
            'create_products',
            'edit_products',
            'manage_pricing',
            'view_vendors',
            'create_vendors',
            'edit_vendors',
            'view_sales_reports',
            'create_quotations',
            'approve_quotations',
            'manage_deals',
            'view_sales_pipeline',
            'view_analytics',
            'generate_reports',
            'export_reports',
            'access_dashboard',
            'view_kpi_metrics',
        ]);

        // Credit Control Manager (CCM)
        $ccm = Role::firstOrCreate(
            ['name' => 'credit_control'],
            ['description' => 'CCM']
        );
        $ccm->syncPermissions([
            'view_clients',
            'view_financial_reports',
            'manage_payments',
            'approve_invoices',
            'manage_credit',
            'view_analytics',
            'generate_reports',
            'export_reports',
            'access_dashboard',
        ]);

        // Chief Financial Officer (CFO)
        $cfo = Role::firstOrCreate(
            ['name' => 'chief_financial'],
            ['description' => 'CFO']
        );
        $cfo->syncPermissions([
            'view_clients',
            'view_services',
            'view_products',
            'view_vendors',
            'approve_vendors',
            'view_financial_reports',
            'manage_payments',
            'approve_invoices',
            'manage_credit',
            'view_revenue_reports',
            'access_financial_dashboard',
            'view_analytics',
            'generate_reports',
            'export_reports',
            'access_dashboard',
            'view_kpi_metrics',
            'manage_pricing',
        ]);

        // Business Analyst (BA0)
        $ba = Role::firstOrCreate(
            ['name' => 'business_analyst'],
            ['description' => 'BA0']
        );
        $ba->syncPermissions([
            'view_clients',
            'view_services',
            'view_products',
            'view_vendors',
            'view_analytics',
            'generate_reports',
            'export_reports',
            'access_dashboard',
            'view_kpi_metrics',
            'view_sales_reports',
            'view_financial_reports',
            'view_network_reports',
        ]);

        // Network Planning Officer (NPO)
        $npo = Role::firstOrCreate(
            ['name' => 'network_planning'],
            ['description' => 'NPO']
        );
        $npo->syncPermissions([
            'view_clients',
            'view_services',
            'view_products',
            'view_vendors',
            'view_network_reports',
            'plan_network',
            'access_technical_data',
            'access_dashboard',
            'generate_reports',
            'view_feasibility',
            'create_feasibility',
            'edit_feasibility',
        ]);

        // Network Implementation Officer (NIO)
        $nio = Role::firstOrCreate(
            ['name' => 'implementation'],
            ['description' => 'NIO']
        );
        $nio->syncPermissions([
            'view_clients',
            'view_services',
            'view_products',
            'view_vendors',
            'view_network_reports',
            'approve_implementations',
            'manage_installations',
            'access_technical_data',
            'access_dashboard',
        ]);

        // Director/CEO
        $ceo = Role::firstOrCreate(
            ['name' => 'director'],
            ['description' => 'CEO']
        );
        // CEO gets all permissions
        $ceo->syncPermissions(Permission::all());
    }
}
