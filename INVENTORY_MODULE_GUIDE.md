# Inventory Management Module

## Overview
A comprehensive inventory management system for tracking stock levels, locations, movements, and adjustments across multiple warehouse locations.

## Database Structure

### Tables Created

1. **inventory_locations** - Storage locations (warehouses, stores, sites, etc.)
   - Hierarchical structure with parent-child relationships
   - Location manager assignments
   - Capacity tracking and utilization percentage
   - Contact information and address details
   - Types: warehouse, store, office, vehicle, site, other

2. **inventory_items** - Master inventory items catalog
   - SKU and barcode tracking
   - Multiple item types: product, material, equipment, consumable, spare_part
   - Stock levels: on_hand, reserved, available (computed)
   - Reorder points and max stock levels
   - Costing methods: FIFO, LIFO, Average, Standard
   - Batch, serial number, and expiry tracking options
   - Links to products and preferred vendors

3. **inventory_transactions** - All stock movements
   - Transaction types: receipt, issue, transfer, adjustment, return, damage, production, count
   - Tracks quantity, cost, and balance before/after
   - Reference to source documents (projects, feasibilities, etc.)
   - Batch and serial number tracking
   - Approval workflow (pending, approved, rejected, completed)

4. **stock_adjustments** - Stock corrections and physical counts
   - Adjustment types: physical_count, damage, loss, found, expiry, quality, correction, write_off, write_on
   - Tracks counted vs. system quantities
   - Reasons and documentation references
   - Approval workflow before impacting stock

5. **inventory_item_locations** - Item stock levels per location
   - Quantity on hand and reserved per location
   - Location-specific reorder levels
   - Bin, aisle, shelf locations for warehouse management
   - Last stock take and movement dates

## Key Features

### 1. Multi-Location Inventory
- Hierarchical location structure (main warehouse > sub-locations)
- Track stock at each location independently
- Transfer stock between locations
- Location-specific reorder points

### 2. Stock Tracking
- Real-time quantity on hand
- Reserved stock for projects/orders
- Available quantity (on hand - reserved)
- Automatic low stock alerts

### 3. Costing Methods
- **FIFO** (First In, First Out)
- **LIFO** (Last In, First Out)
- **Average Cost** - Weighted average
- **Standard Cost** - Predetermined cost

### 4. Transaction Types
- **Receipt**: Incoming stock (purchases, returns from customers)
- **Issue**: Outgoing stock (sales, project consumption)
- **Transfer**: Between location movements
- **Adjustment**: Stock corrections
- **Return**: Returns to vendors
- **Damage**: Damaged/obsolete items write-off
- **Production**: Manufacturing consumption/output
- **Count**: Physical inventory adjustments

### 5. Advanced Tracking
- **Serial Numbers**: Track individual units (equipment, tools)
- **Batch Numbers**: Group tracking (materials, chemicals)
- **Expiry Dates**: For perishable items
- **Shelf Life**: Automatic expiry calculation

### 6. Approval Workflows
- Stock adjustments require approval
- Transaction approval for sensitive operations
- Audit trail of all approvals

### 7. Integration Points
- **Products**: Link inventory items to product catalog
- **Vendors**: Preferred vendor for each item
- **Projects**: Track material consumption per project
- **Client Feasibility**: Material requirements and usage
- **Vendors**: Purchase order integration

## Models Created

### InventoryLocation
- Relationships: parent/children locations, manager, items, transactions
- Scopes: active(), ofType(), root()
- Methods: updateUtilization(), hasChildren(), getTotalStockValueAttribute()

### InventoryItem
- Relationships: product, preferredVendor, locationStock, transactions, adjustments
- Scopes: active(), belowReorderLevel(), outOfStock(), ofType()
- Methods: needsReorder(), updateAverageCost(), adjustStock(), reserveStock(), releaseReservedStock()

### InventoryTransaction
- Relationships: inventoryItem, location, toLocation, creator, approver, reference (polymorphic)
- Scopes: ofType(), atLocation(), dateRange(), pending(), completed()
- Methods: generateTransactionNumber(), approve(), reject(), increasesStock(), decreasesStock()

### StockAdjustment
- Relationships: inventoryItem, location, creator, approver
- Scopes: ofType(), pendingApproval(), approved()
- Methods: generateAdjustmentNumber(), submitForApproval(), approve(), reject(), createInventoryTransaction()

### InventoryItemLocation
- Relationships: inventoryItem, location
- Methods: needsReorder(), isOverMax()

## Usage Scenarios

### 1. Receive Stock from Vendor
```php
InventoryTransaction::create([
    'transaction_type' => 'receipt',
    'inventory_item_id' => $itemId,
    'location_id' => $warehouseId,
    'quantity' => 100,
    'unit_cost' => 50.00,
    'reference_type' => 'PurchaseOrder',
    'reference_id' => $poId,
]);
```

### 2. Issue Stock to Project
```php
InventoryTransaction::create([
    'transaction_type' => 'issue',
    'inventory_item_id' => $itemId,
    'location_id' => $warehouseId,
    'quantity' => -25,
    'reference_type' => 'Project',
    'reference_id' => $projectId,
]);
```

### 3. Physical Stock Count
```php
$adjustment = StockAdjustment::create([
    'adjustment_type' => 'physical_count',
    'inventory_item_id' => $itemId,
    'location_id' => $locationId,
    'quantity_before' => 100,
    'quantity_counted' => 95,
    'quantity_adjusted' => -5,
    'quantity_after' => 95,
    'reason' => 'physical_count',
]);
$adjustment->submitForApproval();
```

### 4. Transfer Between Locations
```php
InventoryTransaction::create([
    'transaction_type' => 'transfer',
    'inventory_item_id' => $itemId,
    'location_id' => $fromLocationId,
    'to_location_id' => $toLocationId,
    'quantity' => 50,
]);
```

## Reports Available

1. **Stock on Hand Report** - Current stock levels across all locations
2. **Low Stock Report** - Items below reorder level
3. **Stock Valuation Report** - Total inventory value
4. **Transaction History** - All movements for an item
5. **Location Stock Report** - Stock levels per location
6. **Slow Moving Items** - Items with no recent movements
7. **Expiry Alert Report** - Items nearing expiry

## Permissions Required

- `view_inventory` - View inventory items and stock levels
- `create_inventory` - Add new inventory items
- `edit_inventory` - Modify inventory items
- `delete_inventory` - Remove inventory items
- `manage_inventory_locations` - Manage storage locations
- `create_transactions` - Record stock movements
- `approve_adjustments` - Approve stock adjustments
- `view_inventory_reports` - Access inventory reports

## Next Steps to Complete

1. Create remaining Livewire components:
   - InventoryLocationIndex (location management)
   - InventoryTransactionIndex (transaction history)
   - StockAdjustmentIndex (adjustment workflow)

2. Create Blade views for all components

3. Add routes to web.php

4. Create permission seeder

5. Update sidebar navigation

6. Create seed data for demo locations and items

7. Add inventory dashboard widgets:
   - Total stock value
   - Low stock alerts
   - Recent transactions
   - Pending adjustments

## API Integration Points

The module is designed to integrate with:
- **Project Module**: Track material consumption per project
- **Client Feasibility**: Link feasibility materials to inventory
- **Vendor Module**: Purchase orders and receiving
- **Product Module**: Link products to inventory items
- **Audit Logs**: All changes automatically tracked via Auditable trait

All models use the `Auditable` trait for complete change tracking.
