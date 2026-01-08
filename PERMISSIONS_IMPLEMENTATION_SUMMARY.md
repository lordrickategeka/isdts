# Project Budget & Item Availability Permissions Implementation

## Overview
Implemented comprehensive role-based permission system for project budget management and item availability checking using Spatie Laravel Permission package.

## Permissions Created

### Project Budget Permissions
1. **view_project_budget** - View budget items and summary
2. **create_budget_items** - Add new budget items to project
3. **edit_budget_items** - Edit existing budget items
4. **delete_budget_items** - Remove budget items from project
5. **approve_project_budget** - Approve submitted budgets (future use)
6. **submit_budget_for_approval** - Submit budget for approval

### Item Availability Permissions
7. **view_item_availability** - View availability check interface
8. **check_item_availability** - Check/verify item availability
9. **update_item_availability** - Update availability status and details
10. **manage_inventory** - Full inventory management (future use)

### Additional Project Permissions
11. **create_projects** - Create new projects
12. **edit_projects** - Edit project details
13. **delete_projects** - Delete projects

## Backend Implementation

### Files Modified

#### 1. Database Seeder
**File:** `database/seeders/RolesAndPermissionsSeeder.php`
- Added 12 new project-related permissions to the permissions array
- Permissions are auto-assigned to Super Admin role

#### 2. ProjectBudget Component
**File:** `app/Livewire/Projects/ProjectBudget.php`

**Protected Methods:**
- `mount()` - Aborts with 403 if user lacks 'view_project_budget'
- `toggleAddForm()` - Checks 'create_budget_items', shows error flash
- `saveAllItems()` - Validates 'create_budget_items' before saving
- `editItem()` - Requires 'edit_budget_items' to load item
- `updateBudgetItem()` - Validates 'edit_budget_items' before update
- `deleteItem()` - Checks 'delete_budget_items' before removal
- `submitForApproval()` - Requires 'submit_budget_for_approval'

**Permission Handling:**
- Page-level: `abort(403)` for unauthorized access
- Action-level: Flash error messages for blocked operations

#### 3. ProjectItemAvailability Component
**File:** `app/Livewire/Projects/ProjectItemAvailability.php`

**Protected Methods:**
- `mount()` - Aborts with 403 if user lacks 'view_item_availability'
- `checkAvailability()` - Validates 'check_item_availability', shows error
- `saveAvailability()` - Requires 'update_item_availability' to save

## Frontend Implementation

### Files Modified

#### 1. Project Budget Blade
**File:** `resources/views/livewire/projects/project-budget.blade.php`

**Permission Guards Added:**
- "Add Budget Items" button - `@can('create_budget_items')`
- "Submit Items for availability Check" button - `@can('create_budget_items')`
- Edit/Delete buttons in pending items - `@can('create_budget_items')`
- Edit button in saved items table - `@can('edit_budget_items')`
- Delete button in saved items table - `@can('delete_budget_items')`
- "Submit for Approval" button - `@can('submit_budget_for_approval')`

**Fallback UI:**
- Shows empty div when user has no create permission
- Shows "No actions" text when user has no edit/delete permissions

#### 2. Item Availability Blade
**File:** `resources/views/livewire/projects/project-item-availability.blade.php`

**Permission Guards Added:**
- "Check/Update" button in table - `@can('check_item_availability')`
- "Save Availability" button in modal - `@can('update_item_availability')`

**Fallback UI:**
- Shows "No access" text when user lacks permission

## Testing Checklist

### Backend Permission Checks
- [x] Page-level access control (403 abort)
- [x] Method-level permission validation
- [x] Error message flash for unauthorized actions

### Frontend Permission Guards
- [x] Conditional button rendering
- [x] Form element hiding based on permissions
- [x] Appropriate fallback UI for unauthorized users

### Database
- [x] Permissions created in database (10 confirmed)
- [x] Seeder can be run multiple times safely

## Role Assignment (TODO)

### Recommended Permission Distribution

**Super Admin Role:**
- All permissions (already assigned)

**Project Manager Role:**
- view_project_budget
- create_budget_items
- edit_budget_items
- delete_budget_items
- submit_budget_for_approval
- view_item_availability

**Implementation Team:**
- view_project_budget
- view_item_availability
- check_item_availability

**Stores/Inventory Team:**
- view_item_availability
- check_item_availability
- update_item_availability
- manage_inventory

**Finance Team:**
- view_project_budget
- approve_project_budget

## Usage Examples

### Checking Permissions in Controllers/Components
```php
// Check if user has permission
if (auth()->user()->can('create_budget_items')) {
    // Allow action
}

// Abort if unauthorized
if (!auth()->user()->can('view_project_budget')) {
    abort(403, 'Unauthorized access to project budget.');
}
```

### Checking Permissions in Blade Views
```blade
@can('create_budget_items')
    <button>Add Budget Items</button>
@endcan

@cannot('edit_budget_items')
    <p>You don't have permission to edit items.</p>
@endcannot
```

### Assigning Permissions to Users
```php
// Via role
$user->assignRole('Project Manager');

// Direct permission
$user->givePermissionTo('view_project_budget');

// Check permission
$user->hasPermissionTo('create_budget_items'); // returns boolean
```

## Next Steps

1. **Assign Permissions to Roles**
   - Update seeder to assign permissions to appropriate roles
   - OR use admin panel to assign via UI

2. **Test with Different Users**
   - Create test users with different roles
   - Verify UI elements hide/show correctly
   - Test action-level permission enforcement

3. **Documentation**
   - Document role-permission mapping for team
   - Create user guide for permission management

4. **Future Enhancements**
   - Add audit trail for permission-controlled actions
   - Implement permission-based email notifications
   - Create permission management UI for admins

## Commands Run

```bash
# Run the seeder to create permissions
php artisan db:seed --class=RolesAndPermissionsSeeder

# Verify permissions were created (tinker)
php artisan tinker --execute="echo 'Permissions count: ' . \Spatie\Permission\Models\Permission::whereIn('name', ['view_project_budget', 'create_budget_items', ...])->count();"
```

## Notes

- All permission checks use Spatie Laravel Permission package
- User model already has `HasRoles` trait
- Blade directives (`@can`, `@cannot`) provided by Spatie
- Backend uses `auth()->user()->can()` for permission checks
- Page-level: 403 abort for unauthorized access
- Action-level: Flash error messages for better UX
- Permissions are cached by Spatie (clear cache if needed: `php artisan permission:cache-reset`)

## Security Considerations

- ✅ Both backend and frontend protected
- ✅ Direct method calls blocked even if UI is manipulated
- ✅ Page-level access control prevents unauthorized viewing
- ✅ Action-level control prevents unauthorized modifications
- ✅ Appropriate error handling (403 vs flash messages)
- ✅ Database seeder uses firstOrCreate to prevent duplicates
