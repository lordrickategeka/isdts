# Service Types, Subcategories & Products Structure

## Overview
The system supports a hierarchical structure for services:
- **Service Types** (e.g., Internet, Hosting)
- **Service Subcategories** (e.g., Dedicated/Shared for Internet, Monthly/Quarterly for Hosting)
- **Products** (e.g., Fiber, LTE)

## Database Structure

### 1. Service Types Table
Main service categories offered by the company.

**Fields:**
- `id` - Primary key
- `name` - Service type name (e.g., "Internet", "Hosting")
- `description` - Detailed description
- `base_price` - Base pricing (can be 0 if pricing is at product level)
- `billing_cycle` - Default billing cycle
- `status` - active/inactive
- `timestamps`

### 2. Service Subcategories Table
Optional subcategories under service types. Can represent:
- Service tiers (Dedicated, Shared)
- Billing periods (Monthly, Quarterly, Annually)
- Service variants

**Fields:**
- `id` - Primary key
- `service_type_id` - Foreign key to service_types
- `name` - Subcategory name
- `description` - Detailed description
- `price_modifier` - Additional price or discount (positive/negative)
- `status` - active/inactive
- `sort_order` - Display order
- `timestamps`

### 3. Products Table
Actual products/plans offered under service types or subcategories.

**Fields:**
- `id` - Primary key
- `service_type_id` - Foreign key to service_types (required)
- `service_subcategory_id` - Foreign key to service_subcategories (optional)
- `name` - Product name (e.g., "Fiber", "LTE", "VPS Hosting")
- `description` - Detailed description
- `price` - Product price
- `billing_cycle` - Billing cycle for this product
- `specifications` - JSON field for technical specs (speed, storage, etc.)
- `status` - active/inactive
- `sort_order` - Display order
- `timestamps`

## Relationships

### Service Type Model
```php
// Has many subcategories
public function subcategories()
{
    return $this->hasMany(ServiceSubcategory::class)->orderBy('sort_order');
}

// Has many products
public function products()
{
    return $this->hasMany(Product::class)->orderBy('sort_order');
}
```

### Service Subcategory Model
```php
// Belongs to a service type
public function serviceType()
{
    return $this->belongsTo(ServiceType::class);
}

// Has many products
public function products()
{
    return $this->hasMany(Product::class)->orderBy('sort_order');
}
```

### Product Model
```php
// Belongs to a service type
public function serviceType()
{
    return $this->belongsTo(ServiceType::class);
}

// Optionally belongs to a subcategory
public function subcategory()
{
    return $this->belongsTo(ServiceSubcategory::class, 'service_subcategory_id');
}
```

## Example Data Structure

### Example 1: Internet Service with Dedicated/Shared
```
Service Type: Internet
├── Subcategory: Dedicated
│   ├── Product: Fiber (500,000 UGX/month)
│   └── Product: LTE (400,000 UGX/month)
└── Subcategory: Shared
    ├── Product: Fiber (250,000 UGX/month)
    └── Product: LTE (150,000 UGX/month)
```

### Example 2: Hosting with Monthly/Quarterly Billing
```
Service Type: Hosting
├── Subcategory: Monthly
│   ├── Product: Shared Hosting (50,000 UGX/month)
│   └── Product: VPS Hosting (150,000 UGX/month)
└── Subcategory: Quarterly
    ├── Product: Shared Hosting (135,000 UGX/quarter - 10% discount)
    └── Product: VPS Hosting (405,000 UGX/quarter - 10% discount)
```

## Usage Examples

### Get all products for a service type
```php
$internet = ServiceType::where('name', 'Internet')->first();
$products = $internet->products;
```

### Get products by subcategory
```php
$dedicated = ServiceSubcategory::where('name', 'Dedicated')->first();
$dedicatedProducts = $dedicated->products;
```

### Get service type with subcategories and products
```php
$internet = ServiceType::with(['subcategories.products'])->find(1);
foreach ($internet->subcategories as $subcategory) {
    echo "Subcategory: " . $subcategory->name;
    foreach ($subcategory->products as $product) {
        echo "  - Product: " . $product->name . " - " . $product->price;
    }
}
```

### Product specifications (JSON field)
Products can store technical specifications in JSON format:
```php
Product::create([
    'name' => 'Fiber',
    'specifications' => [
        'speed' => '100 Mbps',
        'connection_type' => 'Fiber Optic',
        'sla' => '99.9%',
        'installation_time' => '3-5 business days'
    ]
]);

// Access specifications
$product = Product::find(1);
echo $product->specifications['speed']; // "100 Mbps"
```

## Price Calculation Logic

### With Price Modifier
```php
// Base product price + subcategory modifier
$totalPrice = $product->price + $product->subcategory->price_modifier;
```

### With Percentage Discount
```php
// If price_modifier is -10 (10% discount)
$discount = ($product->price * abs($product->subcategory->price_modifier)) / 100;
$totalPrice = $product->price - $discount;
```

## Admin Interface Workflow

1. **Create Service Type** (e.g., "Internet")
2. **Create Subcategories** (optional) (e.g., "Dedicated", "Shared")
3. **Create Products** under service type or subcategory (e.g., "Fiber", "LTE")
4. **Set pricing** at product level
5. **Configure specifications** for each product

## Migration Commands

```bash
# Create the tables
php artisan migrate

# Fresh migration with seed data
php artisan migrate:fresh --seed

# Rollback
php artisan migrate:rollback --step=2
```

## Seeder
Run `ServiceTypeSeeder` to populate example data:
```bash
php artisan db:seed --class=ServiceTypeSeeder
```
