# Import/Export Feature - Quick Reference

## What Was Implemented

### 1. Import Functionality
✅ Import clients from CSV files into projects
✅ Modal-based upload interface  
✅ CSV template download for users
✅ Validation of required fields (company name, vendor_id)
✅ Error handling with detailed feedback
✅ Transaction-based import (rollback on failure)
✅ Automatic client and service creation
✅ Flash messages for success/error feedback

### 2. Export Functionality  
✅ Export project clients to CSV
✅ One-click download
✅ Auto-generated filenames with timestamp
✅ Complete client and service data export
✅ Filtered by current project

### 3. UI Components
✅ Import button wired to `openImportModal()`
✅ Export button wired to `exportClients()`
✅ Import modal with file picker
✅ Download template button
✅ Loading indicators during upload
✅ Flash message displays in project-sites tab

## Files Modified

### Backend (Livewire Component)
- **app/Livewire/Projects/ProjectView.php**
  - Added `WithFileUploads` trait
  - New properties: `$showImportModal`, `$importFile`
  - New methods:
    - `openImportModal()` - Opens import dialog
    - `closeImportModal()` - Closes import dialog  
    - `downloadTemplate()` - Downloads CSV template
    - `importClients()` - Processes CSV and imports data
    - `exportClients()` - Exports clients to CSV

### Frontend (Blade Template)
- **resources/views/livewire/projects/project-view.blade.php**
  - Wired Import/Export buttons with `wire:click`
  - Added import modal component
  - Added flash message display
  - Added loading indicators

### Supporting Files
- **storage/app/templates/clients_import_template.csv** - Sample CSV template
- **IMPORT_EXPORT_GUIDE.md** - Comprehensive documentation

## CSV Format

### Required Columns (in order):
```
company, contact_person, phone, email, city, state, latitude, longitude, vendor_id, service_type, capacity, vlan, nrc, mrc, installation_date, status
```

### Example:
```csv
company,contact_person,phone,email,city,state,latitude,longitude,vendor_id,service_type,capacity,vlan,nrc,mrc,installation_date,status
Acme Corp,John Doe,+256700123456,john@acme.com,Kampala,Central,0.3476,-32.5825,1,Fiber,100Mbps,100,500000,200000,2025-01-15,active
```

## How to Use

### Import:
1. Navigate to Project View
2. Go to "Project Sites" tab
3. Click **Import** button
4. (Optional) Click "Download CSV Template"
5. Select your CSV file
6. Click **Import**
7. Review success/error messages

### Export:
1. Navigate to Project View
2. Go to "Project Sites" tab  
3. Click **Export** button
4. CSV file downloads automatically

## Key Features

### Validation:
- ✅ File type validation (CSV/TXT only)
- ✅ Required field validation (company name)
- ✅ Vendor existence validation
- ✅ Status enum validation (active/inactive/suspended)
- ✅ Data type validation (numeric for lat/long, NRC, MRC)

### Error Handling:
- ✅ Empty rows are skipped
- ✅ Invalid rows logged with line numbers
- ✅ Transaction rollback on critical errors
- ✅ Detailed error messages for first 5 errors
- ✅ All errors logged to Laravel log file

### User Experience:
- ✅ Real-time upload progress
- ✅ Success/error flash messages
- ✅ Template download for easy formatting
- ✅ Auto-refresh of client list after import
- ✅ CSV format guide in modal

## Testing Checklist

- [ ] Import valid CSV with multiple clients
- [ ] Import CSV with missing required fields
- [ ] Import CSV with invalid vendor_id
- [ ] Import CSV with various date formats
- [ ] Import CSV with empty rows
- [ ] Export clients from project with services
- [ ] Export empty project (no clients)
- [ ] Download template file
- [ ] Verify flash messages display correctly
- [ ] Check file uploads to storage/app/imports/
- [ ] Check exports download from storage/app/exports/
- [ ] Verify transaction rollback on errors
- [ ] Test with large CSV files (100+ rows)

## Next Steps / Enhancements

### Short Term:
- [ ] Add progress bar for large imports
- [ ] Preview import data before commit
- [ ] Custom column mapping interface
- [ ] Bulk edit via re-import

### Long Term:  
- [ ] Excel (.xlsx) support
- [ ] Import from Google Sheets
- [ ] Scheduled exports
- [ ] Email export files
- [ ] Import history and rollback
- [ ] API integration for external imports

## Troubleshooting

### Import not working:
1. Check file permissions on storage/app/imports/
2. Verify CSV format matches template
3. Check Laravel logs: storage/logs/laravel.log
4. Ensure vendor_id exists in database
5. Check Livewire file upload limits

### Export not downloading:
1. Check file permissions on storage/app/exports/
2. Verify clients exist for project
3. Check browser download settings
4. Review Laravel logs for errors

## Support
- **Documentation**: See IMPORT_EXPORT_GUIDE.md for detailed info
- **Logs**: storage/logs/laravel.log
- **Import Files**: storage/app/imports/
- **Export Files**: storage/app/exports/
- **Template**: storage/app/templates/clients_import_template.csv
