# Client Count Feature

## Overview
Added functionality to get and display the count of clients associated with a project.

## Implementation

### 1. Project Model (`app/Models/Project.php`)
Added relationships and helper methods:

#### Relationships:
- `clients()` - HasManyThrough relationship to get all unique clients associated with the project
- `clientServices()` - HasMany relationship to get all client services for the project

#### Helper Methods:
- `getClientsCountAttribute()` - Accessor to get the count of unique clients
- `getClientServicesCountAttribute()` - Accessor to get the count of client services

#### Usage:
```php
// Get client count for a project
$project = Project::find(1);
$clientCount = $project->clients_count;

// Get client services count
$servicesCount = $project->client_services_count;

// Access clients collection
$clients = $project->clients;

// Access client services
$services = $project->clientServices;
```

### 2. ProjectView Component (`app/Livewire/Projects/ProjectView.php`)
Added properties and methods:

#### Properties:
- `$clientsCount` - Stores the current count of clients for display

#### Methods:
- `getClientCount()` - Returns the current client count for the component's project
- `getProjectClientCount($projectId)` - Static method to get client count for any project by ID

#### Updated Methods:
- `loadProjectClients()` - Now updates `$clientsCount` when loading clients

#### Usage:
```php
// In the component
$count = $this->getClientCount();

// Statically from anywhere
$count = ProjectView::getProjectClientCount($projectId);
```

### 3. Project View Blade (`resources/views/livewire/projects/project-view.blade.php`)
Added UI display:

#### Display:
- Shows "Total Clients: X" badge in the Project Sites tab
- Uses a blue badge with the count
- Positioned above the search bar

#### Appearance:
```
Total Clients: [5]
```
(Blue badge with count)

## Features

### Real-time Updates
- Count updates automatically when:
  - Clients are imported
  - New clients are added
  - Search is performed (shows total, not filtered)

### Multiple Access Points
1. **From Project Model:**
   ```php
   $project->clients_count
   ```

2. **From Component:**
   ```php
   $this->clientsCount
   ```

3. **Static Method:**
   ```php
   ProjectView::getProjectClientCount($projectId)
   ```

4. **Direct Query:**
   ```php
   $project->clients()->count()
   ```

## Use Cases

### 1. Display in Project Dashboard
Show how many clients are associated with a project:
```blade
<div>
    <span>Total Clients:</span>
    <span>{{ $project->clients_count }}</span>
</div>
```

### 2. Project Statistics
Include in project metrics:
```php
$stats = [
    'clients' => $project->clients_count,
    'services' => $project->client_services_count,
    'budget' => $project->total_budget,
];
```

### 3. Validation
Check if project has clients before certain operations:
```php
if ($project->clients_count > 0) {
    // Proceed with operation
}
```

### 4. Reporting
Generate reports with client counts:
```php
$projects = Project::all()->map(function($project) {
    return [
        'name' => $project->name,
        'clients' => $project->clients_count,
    ];
});
```

## Database Queries

### Efficient Counting
The implementation uses `whereHas` with count for efficiency:
```php
Client::whereHas('clientServices', function($query) use ($projectId) {
    $query->where('project_id', $projectId);
})->count();
```

This generates an optimized SQL query:
```sql
SELECT COUNT(*) FROM clients 
WHERE EXISTS (
    SELECT * FROM client_services 
    WHERE clients.id = client_services.client_id 
    AND client_services.project_id = ?
)
```

## UI Location
- **Tab:** Project Sites
- **Position:** Above search bar
- **Style:** Blue badge with count
- **Updates:** Automatically after import/add operations

## Related Features
- Import clients (updates count)
- Export clients (uses count in success message)
- Search clients (shows total count, not filtered)
- Add client (updates count on success)

## Future Enhancements
- [ ] Show count in project list table
- [ ] Add client count to project cards
- [ ] Display services count alongside clients count
- [ ] Add filtering by client status with counts
- [ ] Show count trends over time
- [ ] Add count to project export data
