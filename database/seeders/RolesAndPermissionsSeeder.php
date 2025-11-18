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
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
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

            // Business Analysis
            'view_analytics',
            'generate_reports',
            'export_reports',
            'access_dashboard',
            'view_kpi_metrics',

            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'assign_roles',

            // Role & Permission Management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'manage_permissions',

            // Form Management
            'view_forms',
            'create_forms',
            'edit_forms',
            'delete_forms',
            'view_submissions',

            // System Settings
            'manage_settings',
            'view_audit_logs',
            'manage_system_config',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin Role
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['description' => 'Super Administrator']
        );
        // Super Admin gets all permissions
        $superAdmin->syncPermissions(Permission::all());

        // Sales Role
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
