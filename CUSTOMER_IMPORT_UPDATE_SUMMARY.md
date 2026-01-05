# Customer Import System Update - Implementation Summary

## Date: January 4, 2026

## Overview
Updated the customer import system to include new fields for customer type, geographic coordinates, and product/transmission tracking. Changed terminology from "Client" to "Customer" in the UI and templates.

## Changes Implemented

### 1. Database Changes

#### Added Columns
- **client_services.product_id** (foreignId, nullable)
  - References products table
  - Stores the transmission/product selection
  - Migration: `2026_01_04_173515_add_product_id_to_client_services_table.php`

### 2. Model Updates

#### ClientService Model (`app/Models/ClientService.php`)
- Added `product_id` to fillable array
- Maintains relationship with Product model

### 3. Import Logic Updates (`app/Livewire/Projects/ProjectView.php`)

#### Updated CSV Format (17 columns)
**New Order:**
1. Customer Name (was: Company) - *Required*
2. Customer Type - *New field* (Home/Corporate) - *Required*
3. Phone
4. Email
5. Latitude - *Moved forward*
6. Longitude - *Moved forward*
7. State
8. City
9. Installation Engineer (was position 2)
10. Vendor ID - *Required*
11. Transmission (Product ID) - *New field*
12. Capacity
13. VLAN
14. NRC
15. MRC
16. Installation Date
17. Status (active/inactive/suspended)

#### Import Logic Changes
- **Customer Type Validation**: Validates "Home" or "Corporate", defaults to "Home"
- **Category Type Mapping**: Automatically maps Customer Type → category_type field
- **Product Validation**: Validates product_id exists in products table
- **Vendor Validation**: Updated to use correct column index (9)
- **Service Type Auto-Detection**: Automatically sets service_type_id from selected product
- **Status Validation**: Validates status is active/inactive/suspended, defaults to active

#### Key Features
```php
// Customer Type handling
$customerType = !empty($row[1]) ? trim($row[1]) : 'Home';
if (!in_array($customerType, ['Home', 'Corporate'])) {
    $customerType = 'Home';
}
$categoryType = $customerType; // Maps to category_type

// Product validation and service type detection
$productId = null;
if (!empty($row[10])) {
    $product = Product::find($row[10]);
    if (!$product) {
        throw new \Exception('Invalid product_id: ' . $row[10]);
    }
    $productId = $product->id;
}

// Service creation with product
ClientService::create([
    'product_id' => $productId,
    'service_type_id' => $productId ? Product::find($productId)->service_type_id : null,
    // ... other fields
]);
```

### 4. Export Logic Updates

#### Updated Export Headers
Changed to match new import format:
- Customer Name (was: Company)
- Customer Type (new)
- Phone, Email, Latitude, Longitude, State, City
- Installation Engineer (repositioned)
- Vendor ID
- Transmission (was: Service Type)
- Capacity, VLAN, NRC, MRC
- Installation Date, Status

#### Export Data Mapping
```php
fputcsv($file, [
    $client->company,              // Customer Name
    $client->category_type ?? 'Home', // Customer Type
    $client->phone,
    $client->email,
    $client->latitude,
    $client->longitude,
    $client->state,
    $client->city,
    $client->contact_person,       // Installation Engineer
    $service->vendor_id,           // Vendor ID
    $service->product_id,          // Transmission
    $service->capacity,
    $service->vlan,
    $service->nrc,
    $service->mrc,
    $service->installation_date,
    $service->status
]);
```

### 5. Template Files

#### CSV Template (`storage/app/templates/clients_import_template.csv`)
Updated header to:
```csv
Customer Name,Customer Type,Phone,Email,Latitude,Longitude,State,City,Installation Engineer,Vendor ID,Transmission,Capacity,VLAN,NRC,MRC,Installation Date,Status
```

#### Example Template (`storage/app/templates/clients_import_template_example.csv`)
Created with sample data showing:
- Corporate customer example
- Home customer example
- Proper formatting for all fields

### 6. UI Updates (`resources/views/livewire/projects/project-view.blade.php`)

#### Import Modal
- Changed title from "Import Clients" to "Import Customers"
- Updated format description from 16 to 17 columns
- Added detailed field descriptions with:
  - Field numbers
  - Field names
  - Required indicators (*)
  - Field descriptions
  - Example values
  - Validation rules
- Enhanced visual formatting with color-coded fields

#### Modal Content
```blade
<div class="space-y-1 text-gray-700">
    <p><span class="text-blue-600">1. Customer Name*</span> - Business/person name</p>
    <p><span class="text-blue-600">2. Customer Type*</span> - Home or Corporate</p>
    <!-- ... 15 more fields -->
</div>
```

### 7. Documentation

#### Created: CUSTOMER_IMPORT_GUIDE.md
Comprehensive guide including:
- Overview of import feature
- Complete CSV format specification
- Column-by-column details table
- Customer Type explanation
- Vendor ID lookup instructions
- Transmission/Product ID guidance
- Status values documentation
- Example CSV data
- Step-by-step import process
- Data creation explanation
- Error handling details
- Validation rules
- Troubleshooting section
- Tips for success
- Export format information

## Field Mapping Summary

### Clients Table
| CSV Column | Database Field | Type | Required |
|------------|---------------|------|----------|
| Customer Name | company | string | Yes |
| Customer Type | category_type | string | Yes |
| Phone | phone | string | No |
| Email | email | string | No |
| Latitude | latitude | decimal | No |
| Longitude | longitude | decimal | No |
| State | state | string | No |
| City | city | string | No |
| Installation Engineer | contact_person | string | No |

### Client Services Table
| CSV Column | Database Field | Type | Required |
|------------|---------------|------|----------|
| Project Context | project_id | foreignId | Auto |
| Vendor ID | vendor_id | foreignId | Yes |
| Transmission | product_id | foreignId | No |
| - | service_type_id | foreignId | Auto |
| Capacity | capacity | string | No |
| VLAN | vlan | string | No |
| NRC | nrc | decimal | No |
| MRC | mrc | decimal | No |
| Installation Date | installation_date | date | No |
| Status | status | enum | No |

## Validation Rules

### Required Fields
1. **Customer Name**: Cannot be empty
2. **Customer Type**: Must be "Home" or "Corporate"
3. **Vendor ID**: Must exist in vendors table

### Optional Field Validations
- **Product ID**: Must exist in products table (if provided)
- **Email**: Must be unique (if provided)
- **Latitude/Longitude**: Must be numeric (if provided)
- **NRC/MRC**: Must be numeric (if provided)
- **Status**: Must be active/inactive/suspended (defaults to active)
- **Installation Date**: Must be valid date format YYYY-MM-DD (if provided)

### Default Values
- **Customer Type**: Defaults to "Home" if invalid or empty
- **Status**: Defaults to "active" if invalid or empty
- **Client Status**: Always set to "active" for new imports
- **NRC/MRC**: Defaults to 0 if invalid or empty

## Integration Points

### Vendor Dropdown
- Import validates against vendors table
- Vendor ID must be a valid, existing vendor
- Users should populate vendors before importing

### Transmission/Product Selection
- Product ID references products table
- Should be filtered to "Internet & Connectivity" service type
- Automatically populates service_type_id from selected product
- Optional field - can be left empty

### Project Association
- All imported customers automatically linked to current project via project_id
- Creates client_services record with project_id = $this->projectId
- Enables project-specific customer tracking

## Testing Recommendations

### Test Cases
1. **Valid Import**: Import CSV with all required fields
2. **Missing Customer Type**: Verify defaults to "Home"
3. **Invalid Vendor ID**: Verify row skipped with error
4. **Invalid Product ID**: Verify row skipped with error
5. **Invalid Status**: Verify defaults to "active"
6. **Mixed Valid/Invalid Rows**: Verify partial import with error report
7. **Export and Re-import**: Verify round-trip consistency

### Sample Test Data
```csv
Customer Name,Customer Type,Phone,Email,Latitude,Longitude,State,City,Installation Engineer,Vendor ID,Transmission,Capacity,VLAN,NRC,MRC,Installation Date,Status
Test Corp 1,Corporate,+256700111111,test1@example.com,0.3136,32.5811,Central,Kampala,Engineer 1,1,5,100Mbps,100,500000,150000,2026-01-15,active
Test Home 1,Home,+256700222222,test2@example.com,0.3476,32.6825,Western,Mbarara,Engineer 2,2,3,50Mbps,50,250000,75000,2026-02-01,inactive
```

## Benefits

### For Users
1. **Clear Terminology**: "Customer" is more universal than "Client"
2. **Customer Type Tracking**: Distinguish between Home and Corporate customers
3. **Geographic Data**: Capture latitude/longitude for mapping
4. **Product Tracking**: Link services to specific products/transmissions
5. **Better Documentation**: Comprehensive guide for import process

### For System
1. **Data Consistency**: Automatic validation and defaults
2. **Referential Integrity**: Foreign key validation
3. **Transaction Safety**: Atomic imports with rollback on critical failures
4. **Error Reporting**: Detailed error messages for troubleshooting
5. **Project Tracking**: Clear association between customers and projects

## Future Enhancements

### Potential Improvements
1. **Product Dropdown**: Add product dropdown filter in UI (Internet & Connectivity)
2. **Vendor Dropdown**: Add vendor dropdown helper in UI
3. **Bulk Edit**: Allow editing imported customers before finalizing
4. **Import Preview**: Show data preview before actual import
5. **Field Mapping**: Allow custom column mapping for different CSV formats
6. **Validation Report**: Detailed validation report before import
7. **Import History**: Track import operations with timestamps and user info

## Migration Commands

To apply these changes to existing database:

```bash
php artisan migrate
```

This will run the migration:
- `2026_01_04_173515_add_product_id_to_client_services_table.php`

## Files Modified

### PHP Files
1. `app/Livewire/Projects/ProjectView.php` - Import/export logic
2. `app/Models/ClientService.php` - Added product_id to fillable

### Migration Files
1. `database/migrations/2026_01_04_173515_add_product_id_to_client_services_table.php` - New

### View Files
1. `resources/views/livewire/projects/project-view.blade.php` - Import modal

### Template Files
1. `storage/app/templates/clients_import_template.csv` - Updated header
2. `storage/app/templates/clients_import_template_example.csv` - New sample file

### Documentation Files
1. `CUSTOMER_IMPORT_GUIDE.md` - New comprehensive guide
2. `CUSTOMER_IMPORT_UPDATE_SUMMARY.md` - This file

## Notes

- All changes are backward compatible with existing data
- Existing client_services records will have NULL product_id (acceptable)
- CSV import maintains transactional integrity
- Export format matches import format for easy round-trips
- Comprehensive error handling prevents data corruption
