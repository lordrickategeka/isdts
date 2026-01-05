# Import/Export Functionality Guide

## Overview
The Project View component now includes import and export functionality for project clients. This allows you to bulk import clients via CSV files and export existing clients to CSV format.

## Features

### 1. Import Clients
- **Location**: Project View → Project Sites Tab
- **Button**: Import button in the toolbar
- **Format**: CSV file with specific columns

#### CSV Format
The import CSV file must include the following columns in this exact order:

```
company, contact_person, phone, email, city, state, latitude, longitude, vendor_id, service_type, capacity, vlan, nrc, mrc, installation_date, status
```

#### Column Descriptions:
- **company**: Client company name (required)
- **contact_person**: Contact person name
- **phone**: Phone number
- **email**: Email address
- **city**: City/District
- **state**: State/Region
- **latitude**: Geographic latitude (decimal)
- **longitude**: Geographic longitude (decimal)
- **vendor_id**: ID of the vendor providing service (must exist in vendors table)
- **service_type**: Type of service (e.g., Fiber, Wireless)
- **capacity**: Service capacity (e.g., 100Mbps)
- **vlan**: VLAN identifier
- **nrc**: Non-Recurring Charge (installation fee)
- **mrc**: Monthly Recurring Charge
- **installation_date**: Installation date (YYYY-MM-DD format)
- **status**: Service status (active, inactive, or suspended)

#### Sample CSV:
```csv
company,contact_person,phone,email,city,state,latitude,longitude,vendor_id,service_type,capacity,vlan,nrc,mrc,installation_date,status
Acme Corp,John Doe,+256700123456,john@acme.com,Kampala,Central,0.3476,-32.5825,1,Fiber,100Mbps,100,500000,200000,2025-01-15,active
Beta Solutions,Jane Smith,+256700234567,jane@beta.com,Entebbe,Central,0.0639,-32.4514,2,Wireless,50Mbps,101,300000,150000,2025-01-20,active
```

#### Import Process:
1. Click the **Import** button in the Project Sites tab
2. Click **Download CSV Template** to get a sample file (optional)
3. Select your CSV file using the file picker
4. Click **Import** to process the file
5. The system will:
   - Validate each row
   - Create Client records
   - Create ClientService records linked to the project
   - Display success/error messages
   - Reload the clients list

#### Error Handling:
- Invalid rows are skipped and logged
- A summary message shows how many clients were imported and how many errors occurred
- Errors are logged for admin review

### 2. Export Clients
- **Location**: Project View → Project Sites Tab
- **Button**: Export button in the toolbar
- **Format**: CSV file

#### Export Process:
1. Click the **Export** button in the Project Sites tab
2. The system will:
   - Query all clients with services in the current project
   - Generate a CSV file with all client and service data
   - Download the file automatically
   - File name format: `project_{id}_clients_{date}_{time}.csv`

#### Export Data:
The exported CSV includes:
- All client information (company, contact, location)
- All service details (vendor, capacity, charges, VLAN)
- One row per client-service combination

## Technical Implementation

### Files Modified:
1. **app/Livewire/Projects/ProjectView.php**
   - Added `WithFileUploads` trait
   - Added import/export properties and methods
   - Methods: `openImportModal()`, `closeImportModal()`, `importClients()`, `exportClients()`, `downloadTemplate()`

2. **resources/views/livewire/projects/project-view.blade.php**
   - Wired up Import and Export buttons
   - Added Import modal with file upload
   - Added flash message display in project-sites tab

3. **storage/app/templates/clients_import_template.csv**
   - Sample CSV template for users

### Database Operations:
- Import uses DB transactions for data consistency
- Failed imports are rolled back
- Exports query only clients with services in the current project

### Security Considerations:
- File type validation (only CSV/TXT allowed)
- Vendor ID validation (must exist in database)
- Status enum validation
- User permissions checked via Livewire middleware

## Usage Examples

### Example 1: Import 10 Clients
```csv
company,contact_person,phone,email,city,state,latitude,longitude,vendor_id,service_type,capacity,vlan,nrc,mrc,installation_date,status
Client 1,Person 1,+256700001,client1@email.com,Kampala,Central,0.3476,-32.5825,1,Fiber,100Mbps,100,500000,200000,2025-01-15,active
Client 2,Person 2,+256700002,client2@email.com,Kampala,Central,0.3476,-32.5825,1,Fiber,50Mbps,101,300000,150000,2025-01-16,active
...
```

### Example 2: Export All Project Clients
1. Navigate to Project View
2. Switch to Project Sites tab
3. Click Export button
4. CSV file downloads with all clients and their services

## Troubleshooting

### Import Issues:
1. **File not uploading**: Check file size (max 2MB by default)
2. **Invalid vendor_id**: Ensure vendor exists in database
3. **Date format errors**: Use YYYY-MM-DD format
4. **Status errors**: Use only active, inactive, or suspended

### Export Issues:
1. **No data exported**: Verify clients exist with services for this project
2. **File not downloading**: Check storage permissions
3. **Missing data**: Verify client-service relationships in database

## Future Enhancements
- [ ] Excel file support (.xlsx)
- [ ] Import validation preview before commit
- [ ] Bulk edit via import
- [ ] Schedule automated exports
- [ ] Email export files
- [ ] Import history and rollback
- [ ] Custom column mapping
- [ ] Import from external APIs

## Support
For issues or questions, contact the development team or check the logs at:
- Import errors: `storage/logs/laravel.log`
- File uploads: `storage/app/imports/`
- Exports: `storage/app/exports/`
