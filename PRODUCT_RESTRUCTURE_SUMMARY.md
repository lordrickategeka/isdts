# Product Structure Restructure - Implementation Summary

## Date: January 4, 2026

## Overview
Restructured the product system to eliminate service_type and service_subcategory dependencies and establish a new hierarchy: **Vendor → Vendor Service → Product**.

## Previous Structure
```
Service Type (e.g., Internet & Connectivity)
  └── Service Subcategory (e.g., Home, Corporate)
      └── Product (e.g., Fiber 100Mbps)
```

## New Structure
```
Vendor (e.g., MTN, Airtel)
  └── Vendor Service (e.g., Internet, M&E, Voice, Cloud)
      └── Product (e.g., Fiber, LTE, Speakers, IP Phones)
```

## Changes Implemented

### 1. Database Migration

**File:** `database/migrations/2026_01_04_175049_restructure_products_table_to_use_vendor_services.php`

#### Changes to `products` table:
- ✅ **Added:** `vendor_id` (foreignId, nullable) → References vendors table
- ✅ **Added:** `vendor_service_id` (foreignId, nullable) → References vendor_services table
- ✅ **Removed:** `service_type_id` (foreignId)
- ✅ **Removed:** `service_subcategory_id` (foreignId)

#### Migration Details:
```php
// Up Migration
Schema::table('products', function (Blueprint $table) {
    // Add new vendor relationships
    $table->foreignId('vendor_id')->nullable()->after('id')->constrained()->onDelete('cascade');
    $table->foreignId('vendor_service_id')->nullable()->after('vendor_id')->constrained()->onDelete('cascade');
    
    // Drop old service type relationships
    $table->dropForeign(['service_type_id']);
    $table->dropForeign(['service_subcategory_id']);
    $table->dropColumn(['service_type_id', 'service_subcategory_id']);
});
```

### 2. Model Updates

#### Product Model (`app/Models/Product.php`)

**Fillable Fields Changed:**
```php
// OLD
protected $fillable = [
    'service_type_id',
    'service_subcategory_id',
    'vendor_id',
    'vendor_service_id',
    // ... other fields
];

// NEW
protected $fillable = [
    'vendor_id',
    'vendor_service_id',
    'name',
    'description',
    // ... other fields
];
```

**Relationships Changed:**
```php
// REMOVED
public function serviceType()
{
    return $this->belongsTo(ServiceType::class);
}

public function subcategory()
{
    return $this->belongsTo(ServiceSubcategory::class, 'service_subcategory_id');
}

// KEPT
public function vendor()
{
    return $this->belongsTo(Vendor::class);
}

public function vendorService()
{
    return $this->belongsTo(VendorService::class);
}
```

#### VendorService Model (`app/Models/VendorService.php`)

**Added Relationship:**
```php
public function products(): HasMany
{
    return $this->hasMany(Product::class);
}
```

### 3. Controller Updates

#### ProductsComponent (`app/Livewire/Products/ProductsComponent.php`)

**Property Changes:**
```php
// OLD
public $service_type_id = '';
public $service_subcategory_id = '';
public $subcategories = [];

// NEW
public $vendor_id = '';
public $vendor_service_id = '';
public $vendorServices = [];
```

**Validation Rules Changed:**
```php
// OLD
protected $rules = [
    'service_type_id' => 'required|exists:service_types,id',
    'service_subcategory_id' => 'nullable|exists:service_subcategories,id',
    // ... other rules
];

// NEW
protected $rules = [
    'vendor_id' => 'required|exists:vendors,id',
    'vendor_service_id' => 'required|exists:vendor_services,id',
    // ... other rules
];
```

**Dynamic Loading Method:**
```php
// OLD
public function updatedServiceTypeId($value)
{
    if ($value) {
        $this->subcategories = ServiceSubcategory::where('service_type_id', $value)
            ->orderBy('sort_order')
            ->get();
    }
}

// NEW
public function updatedVendorId($value)
{
    if ($value) {
        $this->vendorServices = VendorService::where('vendor_id', $value)
            ->orderBy('service_name')
            ->get();
    }
}
```

**Render Method Changes:**
```php
// OLD
$products = Product::with(['serviceType', 'subcategory'])
    ->when($this->search, function ($query) {
        $query->orWhereHas('serviceType', function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%');
        });
    })
    // ...

$serviceTypes = ServiceType::where('status', 'active')->get();

return view('livewire.products.products-component', [
    'products' => $products,
    'serviceTypes' => $serviceTypes,
]);

// NEW
$products = Product::with(['vendor', 'vendorService'])
    ->when($this->search, function ($query) {
        $query->orWhereHas('vendor', function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%');
        })
        ->orWhereHas('vendorService', function ($q) {
            $q->where('service_name', 'like', '%' . $this->search . '%');
        });
    })
    // ...

$vendors = Vendor::where('status', 'active')->get();

return view('livewire.products.products-component', [
    'products' => $products,
    'vendors' => $vendors,
]);
```

#### ProjectView Component (`app/Livewire/Projects/ProjectView.php`)

**Import Logic Updated:**
```php
// OLD
ClientService::create([
    'product_id' => $productId,
    'service_type_id' => $productId ? Product::find($productId)->service_type_id : null,
    // ... other fields
]);

// NEW
ClientService::create([
    'product_id' => $productId,
    'service_type_id' => null, // No longer using service types
    // ... other fields
]);
```

### 4. View Updates

#### Products Blade (`resources/views/livewire/products/products-component.blade.php`)

**Table Header Changes:**
```blade
<!-- OLD -->
<th>Service Type</th>
<th>Subcategory</th>

<!-- NEW -->
<th>Vendor</th>
<th>Vendor Service</th>
```

**Table Body Changes:**
```blade
<!-- OLD -->
<td>{{ $product->serviceType->name ?? 'N/A' }}</td>
<td>{{ $product->subcategory->name ?? '-' }}</td>

<!-- NEW -->
<td>{{ $product->vendor->name ?? 'N/A' }}</td>
<td>{{ $product->vendorService->service_name ?? '-' }}</td>
```

**Modal Form Changes:**
```blade
<!-- OLD -->
<select wire:model.live="service_type_id">
    <option value="">Select Service Type</option>
    @foreach($serviceTypes as $serviceType)
        <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
    @endforeach
</select>

@if(count($subcategories) > 0)
    <select wire:model="service_subcategory_id">
        <option value="">None</option>
        @foreach($subcategories as $subcategory)
            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
        @endforeach
    </select>
@endif

<!-- NEW -->
<select wire:model.live="vendor_id">
    <option value="">Select Vendor</option>
    @foreach($vendors as $vendor)
        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
    @endforeach
</select>

<select wire:model="vendor_service_id" {{ count($vendorServices) == 0 ? 'disabled' : '' }}>
    <option value="">{{ count($vendorServices) > 0 ? 'Select Vendor Service' : 'Select Vendor First' }}</option>
    @foreach($vendorServices as $vendorService)
        <option value="{{ $vendorService->id }}">{{ $vendorService->service_name }}</option>
    @endforeach
</select>
```

## New Workflow

### Creating a Product

1. **Select Vendor** (Required)
   - Choose from active vendors in the system
   - Example: MTN, Airtel, Liquid Telecom

2. **Select Vendor Service** (Required)
   - Dropdown populated based on selected vendor
   - Services like: Internet, M&E, Voice, Cloud
   - Disabled until vendor is selected

3. **Enter Product Details**
   - Name (e.g., Fiber, LTE, IP Phone)
   - Description
   - Capacity (e.g., 100Mbps)
   - Pricing information
   - Status

### Example Product Hierarchy

```
Vendor: MTN Uganda
  └── Vendor Service: Internet
      ├── Product: Fiber 100Mbps
      ├── Product: Fiber 200Mbps
      └── Product: LTE 50Mbps
  
  └── Vendor Service: M&E
      ├── Product: Microwave Link
      └── Product: Radio Equipment

Vendor: Cisco
  └── Vendor Service: Voice
      ├── Product: IP Phone 7841
      ├── Product: IP Phone 8841
      └── Product: Conference Phone

  └── Vendor Service: Cloud
      ├── Product: Webex Basic
      └── Product: Webex Premium
```

## Benefits

### 1. Clearer Hierarchy
- Products are directly linked to vendors
- Services are vendor-specific
- Better reflects real-world business relationships

### 2. Simplified Management
- Add vendor first
- Add vendor services
- Add products under those services
- No need for generic service types

### 3. Better Product Organization
- Products grouped by actual vendor
- Services reflect what each vendor offers
- Easier to manage vendor-specific offerings

### 4. Improved Flexibility
- Each vendor can have custom services
- Products can vary by vendor
- Better support for multi-vendor scenarios

## Migration Path

### For Existing Data

If you have existing products with service_type_id and service_subcategory_id:

1. **Before Migration:**
   - Export existing products data
   - Map service types to appropriate vendors
   - Map subcategories to vendor services

2. **After Migration:**
   - Create vendors if needed
   - Create vendor services for each vendor
   - Re-create products with new vendor/service associations

3. **Data Mapping Example:**
   ```
   OLD: Service Type: "Internet & Connectivity" → Subcategory: "Home"
   NEW: Vendor: "MTN" → Vendor Service: "Internet"
   ```

## Files Modified

### Migration Files
- ✅ `database/migrations/2026_01_04_175049_restructure_products_table_to_use_vendor_services.php` (NEW)

### Model Files
- ✅ `app/Models/Product.php` - Removed service type relationships, updated fillable
- ✅ `app/Models/VendorService.php` - Added products relationship

### Controller Files
- ✅ `app/Livewire/Products/ProductsComponent.php` - Complete refactor for vendor services
- ✅ `app/Livewire/Projects/ProjectView.php` - Removed service_type_id auto-detection

### View Files
- ✅ `resources/views/livewire/products/products-component.blade.php` - Updated table and modal

## Breaking Changes

### ⚠️ Important Notes

1. **Existing Products:** All existing products will lose their service_type_id and service_subcategory_id associations
2. **Seeders:** ServiceTypeSeeder will fail if run after this migration
3. **API/Integrations:** Any external systems relying on service_type_id will need updates

### Backward Compatibility

- ❌ **Not Backward Compatible** with service types
- ✅ **Forward Compatible** with vendor-based structure
- ⚠️ **Data Migration Required** for existing products

## Testing Recommendations

### Test Cases

1. **Create Product:**
   - Select vendor → verify vendor services load
   - Select vendor service → verify product can be created
   - Verify product appears in table with correct vendor/service

2. **Edit Product:**
   - Edit existing product
   - Change vendor → verify vendor services update
   - Save → verify changes persist

3. **Delete Product:**
   - Delete product
   - Verify cascade deletes work correctly

4. **Search:**
   - Search by product name
   - Search by vendor name
   - Search by vendor service name

5. **Import:**
   - Import customers with product_id
   - Verify service_type_id is set to null
   - Verify import still works

## Future Enhancements

### Potential Improvements

1. **Vendor Service Categories**
   - Add categories to vendor services
   - Enable filtering products by category

2. **Product Templates**
   - Create reusable product templates
   - Quick product creation from templates

3. **Vendor Product Catalog**
   - Public-facing vendor catalogs
   - Customer self-service product selection

4. **Pricing Tiers**
   - Multiple pricing tiers per product
   - Volume-based pricing

5. **Product Bundles**
   - Bundle multiple products together
   - Package deals and promotions

## Commands to Run

```bash
# Run the migration
php artisan migrate

# Clear caches (recommended)
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Verification Steps

After implementation, verify:

1. ✅ Products table has vendor_id and vendor_service_id columns
2. ✅ Products table does NOT have service_type_id and service_subcategory_id columns
3. ✅ Product creation form shows Vendor and Vendor Service dropdowns
4. ✅ Vendor Service dropdown is disabled until vendor is selected
5. ✅ Products display with correct vendor and service information
6. ✅ Search works for vendor name and vendor service name
7. ✅ Edit and delete functions work correctly

## Summary

The product structure has been successfully restructured to follow the vendor → vendor_service → product hierarchy. This provides a clearer, more flexible system that better reflects real-world business relationships and vendor offerings.
