# Customer Import Quick Reference

## CSV Column Order (17 columns)

```
1.  Customer Name       → clients.company           [REQUIRED]
2.  Customer Type       → clients.category_type     [REQUIRED] Home|Corporate
3.  Phone              → clients.phone
4.  Email              → clients.email
5.  Latitude           → clients.latitude          [Numeric]
6.  Longitude          → clients.longitude         [Numeric]
7.  State              → clients.state
8.  City               → clients.city
9.  Installation Eng   → clients.contact_person
10. Vendor ID          → client_services.vendor_id [REQUIRED, FK]
11. Transmission       → client_services.product_id [FK to products]
12. Capacity           → client_services.capacity
13. VLAN               → client_services.vlan
14. NRC                → client_services.nrc        [Numeric]
15. MRC                → client_services.mrc        [Numeric]
16. Installation Date  → client_services.installation_date [YYYY-MM-DD]
17. Status             → client_services.status    [active|inactive|suspended]
```

## Required Fields
- ✅ Customer Name
- ✅ Customer Type (Home/Corporate)
- ✅ Vendor ID (must exist in vendors table)

## Key Points
- **Customer Type** → sets `category_type` in clients table
- **Transmission** → Product ID from products table (Internet & Connectivity)
- **Status** defaults to "active" if empty or invalid
- **Customer Type** defaults to "Home" if empty or invalid
- **Project ID** automatically set from current project context

## Example Row
```csv
Acme Corp,Corporate,+256700123456,info@acme.com,0.3136,32.5811,Central,Kampala,John Doe,1,5,100Mbps,100,500000,150000,2026-01-15,active
```

## Template Location
`storage/app/templates/clients_import_template.csv`

## Import Location
Project View → Import Customers button

## Documentation
See `CUSTOMER_IMPORT_GUIDE.md` for full details
