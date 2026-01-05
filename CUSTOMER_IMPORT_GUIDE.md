# Customer Import Guide

## Overview
This guide explains how to import customer data into projects using the CSV import feature.

## CSV File Format

### Required Headers (17 columns)
The CSV file must have exactly 17 columns in the following order:

```csv
Customer Name,Customer Type,Phone,Email,Latitude,Longitude,State,City,Installation Engineer,Vendor ID,Transmission,Capacity,VLAN,NRC,MRC,Installation Date,Status
```

### Column Details

| # | Column Name | Required | Description | Example | Validation |
|---|------------|----------|-------------|---------|------------|
| 1 | Customer Name | ✅ Yes | Business or person name | Acme Corporation | Must not be empty |
| 2 | Customer Type | ✅ Yes | Customer category | Home, Corporate | Must be "Home" or "Corporate" |
| 3 | Phone | No | Contact phone number | +256700123456 | Any format |
| 4 | Email | No | Contact email address | info@acme.com | Valid email format |
| 5 | Latitude | No | GPS latitude coordinate | 0.3136 | Numeric value |
| 6 | Longitude | No | GPS longitude coordinate | 32.5811 | Numeric value |
| 7 | State | No | Region/state name | Central | Text |
| 8 | City | No | City name | Kampala | Text |
| 9 | Installation Engineer | No | Assigned engineer/contact person | John Doe | Text |
| 10 | Vendor ID | ✅ Yes | ID from vendors table | 1 | Must exist in vendors table |
| 11 | Transmission | No | Product ID for Internet & Connectivity | 5 | Must exist in products table |
| 12 | Capacity | No | Service capacity | 100Mbps | Text |
| 13 | VLAN | No | VLAN number | 100 | Text or number |
| 14 | NRC | No | Non-recurring charge | 500000 | Numeric value |
| 15 | MRC | No | Monthly recurring charge | 150000 | Numeric value |
| 16 | Installation Date | No | Installation date | 2026-01-15 | YYYY-MM-DD format |
| 17 | Status | No | Service status | active | active, inactive, or suspended |

## Customer Type & Category Type

The **Customer Type** column determines the customer category:
- **Home**: Residential customers
- **Corporate**: Business customers

The value from **Customer Type** is automatically saved to the `category_type` column in the clients table. This ensures consistent categorization across the system.

## Vendor ID

The **Vendor ID** must be a valid ID from the vendors table. To find vendor IDs:
1. Go to the Vendors section in the system
2. Each vendor has a unique ID number
3. Use this ID in the CSV file

**Important**: If you provide an invalid Vendor ID, that row will be skipped with an error message.

## Transmission (Product)

The **Transmission** column should contain the Product ID from the products table where the service type is "Internet & Connectivity".

To find the correct Product ID:
1. Go to Products section
2. Filter by Service Type: "Internet & Connectivity"
3. Note the Product ID for the desired transmission type

**Note**: If left empty, no product will be associated with the service.

## Status Values

The **Status** column accepts three values:
- `active` - Service is currently active (default)
- `inactive` - Service is not active
- `suspended` - Service has been suspended

If the status column is empty or contains an invalid value, it defaults to `active`.

## Example CSV File

```csv
Customer Name,Customer Type,Phone,Email,Latitude,Longitude,State,City,Installation Engineer,Vendor ID,Transmission,Capacity,VLAN,NRC,MRC,Installation Date,Status
Acme Corporation,Corporate,+256700123456,info@acme.com,0.3136,32.5811,Central,Kampala,John Doe,1,5,100Mbps,100,500000,150000,2026-01-15,active
Home User,Home,+256700987654,user@example.com,0.3476,32.6825,Central,Kampala,Jane Smith,1,3,50Mbps,50,250000,75000,2026-01-20,active
Tech Solutions Ltd,Corporate,+256700111222,tech@solutions.ug,0.3500,32.6000,Western,Mbarara,Mike Johnson,2,7,200Mbps,150,750000,300000,2026-02-01,active
```

## Import Process

### Step 1: Prepare Your CSV File
1. Download the CSV template from the import modal
2. Fill in your customer data following the format above
3. Save the file with `.csv` extension

### Step 2: Import the File
1. Go to the Project View page
2. Click "Import Customers" button
3. Click "Download CSV Template" to get the template (optional)
4. Click "Choose File" and select your CSV file
5. Click "Import" button

### Step 3: Review Results
After import:
- Success message shows number of imported customers
- If any rows were skipped, you'll see the count and first 5 error messages
- All imported customers will appear in the project's customer list

## Important Notes

### Data Created
For each valid row in the CSV, the system creates:
1. **Client Record**: Basic customer information (name, contacts, location)
2. **Client Service Record**: Service details linked to the project (vendor, product, capacity, pricing)

### Project Association
All imported customers are automatically associated with the current project through the `project_id` in the client_services table.

### Error Handling
- Empty rows are automatically skipped
- Invalid data in a row causes that row to be skipped
- Import continues even if some rows fail
- Transaction ensures data consistency

### Validation Rules
- **Customer Name**: Cannot be empty
- **Customer Type**: Must be "Home" or "Corporate" (defaults to "Home" if invalid)
- **Vendor ID**: Must exist in vendors table
- **Product ID**: Must exist in products table (if provided)
- **Email**: Must be unique if provided
- **Status**: Must be active, inactive, or suspended (defaults to active)

## Troubleshooting

### Common Issues

**Issue**: "Invalid vendor_id: X"
- **Solution**: Check that the vendor ID exists in the vendors table

**Issue**: "Invalid product_id: X"
- **Solution**: Verify the product ID exists and belongs to "Internet & Connectivity" service type

**Issue**: "Customer name is required"
- **Solution**: Ensure the first column (Customer Name) is not empty

**Issue**: Email already exists
- **Solution**: Each email must be unique across all clients

**Issue**: Import shows 0 imported
- **Solution**: Check that your CSV file has the correct 17 columns and at least one valid data row

## Tips for Success

1. **Use the Template**: Always start with the downloaded template to ensure correct column order
2. **Test with Small File**: Import a few rows first to verify format
3. **Check Required Fields**: Ensure Customer Name and Vendor ID are present
4. **Verify IDs**: Double-check vendor and product IDs before importing
5. **Date Format**: Use YYYY-MM-DD format for dates (e.g., 2026-01-15)
6. **Numeric Values**: Don't use currency symbols in NRC/MRC fields
7. **Status Values**: Use lowercase for status (active, inactive, suspended)

## Export Format

When you export customers from a project, the CSV file will have the same format as the import template, making it easy to:
- Edit exported data and re-import
- Use exported data as a template for new imports
- Maintain consistent data format across projects

## Additional Resources

- **Vendors Management**: Configure vendors before importing
- **Products Management**: Set up products for transmission types
- **Client Management**: View and edit imported customers
- **Project Management**: Manage project-specific customer associations
