# Audit Logs Module

## Overview
The Audit Logs module provides comprehensive activity tracking across your application. It automatically logs create, update, and delete operations on models and can be extended to track custom events.

## Features
- ✅ Automatic logging of CRUD operations
- ✅ User activity tracking
- ✅ IP address and user agent logging
- ✅ Before/after value comparison
- ✅ Advanced filtering and search
- ✅ Detailed view of changes
- ✅ Permission-based access control

## Components Created

### 1. Database
- **Migration**: `2026_01_08_130000_create_audit_logs_table.php`
- **Model**: `app/Models/AuditLog.php`

### 2. Trait
- **Auditable Trait**: `app/Traits/Auditable.php`
  - Auto-logs create, update, delete events
  - Customizable per model

### 3. Livewire Component
- **Component**: `app/Livewire/AuditLogs/AuditLogIndex.php`
- **View**: `resources/views/livewire/audit-logs/audit-log-index.blade.php`

### 4. Routes
- **Route**: `/audit-logs` - View all audit logs

## Usage

### Making a Model Auditable

Add the `Auditable` trait to any model you want to track:

```php
use App\Traits\Auditable;

class YourModel extends Model
{
    use Auditable;
    
    // That's it! Now all changes are automatically logged
}
```

### Customizing Audit Behavior

#### 1. Specify Which Columns to Audit
```php
class Department extends Model
{
    use Auditable;
    
    // Only audit these specific columns
    protected $auditableColumns = ['name', 'code', 'manager_id'];
}
```

#### 2. Exclude Specific Events
```php
class User extends Model
{
    use Auditable;
    
    // Don't log delete events
    protected $auditExclude = ['deleted'];
}
```

#### 3. Disable Audit Completely
```php
class TemporaryModel extends Model
{
    use Auditable;
    
    protected $disableAudit = true;
}
```

### Manual Logging

You can manually log custom events:

```php
use App\Models\AuditLog;

// Simple log
AuditLog::logActivity(
    event: 'login',
    description: 'User logged in successfully'
);

// With model reference
AuditLog::logActivity(
    event: 'export',
    auditable: $project,
    description: 'Project data exported to Excel',
    properties: ['format' => 'xlsx', 'rows' => 1500]
);

// With before/after values
AuditLog::logActivity(
    event: 'status_changed',
    auditable: $order,
    description: 'Order status updated',
    oldValues: ['status' => 'pending'],
    newValues: ['status' => 'completed']
);
```

### Logging Authentication Events

Add to your `LoginController` or authentication logic:

```php
use App\Models\AuditLog;

// On successful login
AuditLog::logActivity(
    event: 'login',
    description: auth()->user()->name . ' logged in'
);

// On logout
AuditLog::logActivity(
    event: 'logout',
    description: auth()->user()->name . ' logged out'
);

// Failed login attempt
AuditLog::logActivity(
    event: 'login_failed',
    description: 'Failed login attempt for email: ' . $request->email,
    properties: ['email' => $request->email]
);
```

### Querying Audit Logs

```php
use App\Models\AuditLog;

// Get all logs for a specific model
$logs = AuditLog::where('auditable_type', Department::class)
    ->where('auditable_id', $departmentId)
    ->get();

// Get logs by event type
$creations = AuditLog::byEvent('created')->get();

// Get logs by user
$userLogs = AuditLog::byUser($userId)->latest()->get();

// Get logs for date range
$logs = AuditLog::dateRange('2026-01-01', '2026-01-31')->get();

// Complex query
$logs = AuditLog::with('user')
    ->byModel('Department')
    ->byEvent('updated')
    ->whereDate('created_at', '>=', now()->subDays(7))
    ->latest()
    ->paginate(25);
```

### Accessing Logs from Models

```php
// Get audit logs for a specific model instance
$department = Department::find(1);
$logs = $department->auditLogs;

// With user information
$logs = $department->auditLogs()->with('user')->get();

// Recent changes only
$recentChanges = $department->auditLogs()
    ->where('created_at', '>=', now()->subDays(7))
    ->get();
```

## Viewing Audit Logs

### Web Interface
1. Navigate to `/audit-logs`
2. Use filters to narrow down results:
   - **Search**: Search by description, user name, or IP address
   - **Event Type**: Filter by created, updated, deleted, etc.
   - **User**: Filter by specific user
   - **Module**: Filter by model type
   - **Date Range**: Filter by date range

3. Click the eye icon to view detailed information including:
   - Full change details (before/after values)
   - User agent and browser information
   - Complete URL and HTTP method

## Permissions

The audit logs feature uses the `view_audit_logs` permission. Make sure users have this permission to access the audit logs page.

```php
// In your seeder or permission setup
Permission::create(['name' => 'view_audit_logs']);

// Assign to admin role
$adminRole->givePermissionTo('view_audit_logs');
```

## Examples

### Example 1: Department Changes
When a department is created, updated, or deleted, the audit log will show:
- Who made the change
- When it happened
- What fields changed
- Old and new values

### Example 2: User Management
Track when users are assigned to departments:
```php
$user->department_id = $newDepartmentId;
$user->save();
// Automatically logged with old and new department IDs
```

### Example 3: Bulk Operations
```php
foreach ($departments as $dept) {
    $dept->is_active = false;
    $dept->save();
    // Each change is logged individually
}
```

## Best Practices

1. **Selective Auditing**: Only audit models that need tracking to avoid database bloat
2. **Regular Cleanup**: Set up a scheduled job to archive or delete old logs
3. **Sensitive Data**: Exclude sensitive fields like passwords from audit logs
4. **Performance**: Add indexes on frequently queried columns
5. **Monitoring**: Regularly review audit logs for suspicious activity

## Maintenance

### Cleanup Old Logs
```php
// In a scheduled command or job
AuditLog::where('created_at', '<', now()->subMonths(6))->delete();
```

### Archive Logs
```php
// Export to file before deletion
$oldLogs = AuditLog::where('created_at', '<', now()->subYear())->get();
Storage::put('audit_archives/'.now()->format('Y-m-d').'.json', $oldLogs->toJson());
```

## Troubleshooting

### Logs Not Being Created
1. Ensure the `Auditable` trait is added to your model
2. Check that the model has the correct fillable fields
3. Verify the user is authenticated when making changes

### Performance Issues
1. Add indexes to frequently queried columns
2. Implement log rotation/archiving
3. Consider using database partitioning for large datasets

## Summary

The audit logs module is now fully functional and provides:
- Automatic tracking of model changes
- Manual event logging capability
- Comprehensive filtering and search
- Detailed change visualization
- Permission-based access control

Access it at: `/audit-logs`
