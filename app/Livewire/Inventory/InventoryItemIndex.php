<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InventoryItem;
use App\Models\Product;
use App\Models\Vendor;

class InventoryItemIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $category = '';
    public $status_filter = 'active';
    public $stock_status = '';
    public $perPage = 25;

    // Modal properties
    public $showModal = false;
    public $showDetailsModal = false;
    public $selectedItem = null;
    public $itemId;

    // Form properties
    public $sku;
    public $barcode;
    public $name;
    public $description;
    public $item_type;
    public $item_category;
    public $subcategory;
    public $unit_of_measure = 'units';
    public $unit_weight;
    public $unit_volume;
    public $reorder_level = 0;
    public $reorder_quantity = 0;
    public $max_stock_level;
    public $costing_method = 'Average';
    public $unit_cost = 0;
    public $standard_cost;
    public $track_serial_numbers = false;
    public $track_batches = false;
    public $track_expiry = false;
    public $shelf_life_days;
    public $product_id;
    public $preferred_vendor_id;
    public $is_active = true;
    public $is_stockable = true;
    public $is_purchasable = true;
    public $is_sellable = true;
    public $storage_location;
    public $notes;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'category' => ['except' => ''],
        'stock_status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingStockStatus()
    {
        $this->resetPage();
    }

    public function openModal($itemId = null)
    {
        $this->resetValidation();

        if ($itemId) {
            $item = InventoryItem::findOrFail($itemId);
            $this->itemId = $item->id;
            $this->sku = $item->sku;
            $this->barcode = $item->barcode;
            $this->name = $item->name;
            $this->description = $item->description;
            $this->item_type = $item->type;
            $this->item_category = $item->category;
            $this->subcategory = $item->subcategory;
            $this->unit_of_measure = $item->unit_of_measure;
            $this->unit_weight = $item->unit_weight;
            $this->unit_volume = $item->unit_volume;
            $this->reorder_level = $item->reorder_level;
            $this->reorder_quantity = $item->reorder_quantity;
            $this->max_stock_level = $item->max_stock_level;
            $this->costing_method = $item->costing_method;
            $this->unit_cost = $item->unit_cost;
            $this->standard_cost = $item->standard_cost;
            $this->track_serial_numbers = $item->track_serial_numbers;
            $this->track_batches = $item->track_batches;
            $this->track_expiry = $item->track_expiry;
            $this->shelf_life_days = $item->shelf_life_days;
            $this->product_id = $item->product_id;
            $this->preferred_vendor_id = $item->preferred_vendor_id;
            $this->is_active = $item->is_active;
            $this->is_stockable = $item->is_stockable;
            $this->is_purchasable = $item->is_purchasable;
            $this->is_sellable = $item->is_sellable;
            $this->storage_location = $item->storage_location;
            $this->notes = $item->notes;
        } else {
            $this->reset(['itemId', 'sku', 'barcode', 'name', 'description', 'item_type', 'item_category', 'subcategory',
                'unit_weight', 'unit_volume', 'standard_cost', 'shelf_life_days', 'product_id', 'preferred_vendor_id',
                'storage_location', 'notes']);
            $this->sku = $this->generateSKU();
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['itemId']);
    }

    public function save()
    {
        $this->validate([
            'sku' => 'required|string|max:100|unique:inventory_items,sku,' . $this->itemId,
            'barcode' => 'nullable|string|unique:inventory_items,barcode,' . $this->itemId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'item_type' => 'required|in:' . implode(',', InventoryItem::TYPES),
            'item_category' => 'nullable|string',
            'unit_of_measure' => 'required|string|max:50',
            'reorder_level' => 'nullable|numeric|min:0',
            'reorder_quantity' => 'nullable|numeric|min:0',
            'max_stock_level' => 'nullable|numeric|min:0',
            'costing_method' => 'required|in:' . implode(',', InventoryItem::COSTING_METHODS),
            'unit_cost' => 'required|numeric|min:0',
            'product_id' => 'nullable|exists:products,id',
            'preferred_vendor_id' => 'nullable|exists:vendors,id',
        ]);

        $data = [
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->item_type,
            'category' => $this->item_category,
            'subcategory' => $this->subcategory,
            'unit_of_measure' => $this->unit_of_measure,
            'unit_weight' => $this->unit_weight,
            'unit_volume' => $this->unit_volume,
            'reorder_level' => $this->reorder_level ?? 0,
            'reorder_quantity' => $this->reorder_quantity ?? 0,
            'max_stock_level' => $this->max_stock_level,
            'costing_method' => $this->costing_method,
            'unit_cost' => $this->unit_cost,
            'standard_cost' => $this->standard_cost,
            'average_cost' => $this->unit_cost,
            'track_serial_numbers' => $this->track_serial_numbers,
            'track_batches' => $this->track_batches,
            'track_expiry' => $this->track_expiry,
            'shelf_life_days' => $this->shelf_life_days,
            'product_id' => $this->product_id,
            'preferred_vendor_id' => $this->preferred_vendor_id,
            'is_active' => $this->is_active,
            'is_stockable' => $this->is_stockable,
            'is_purchasable' => $this->is_purchasable,
            'is_sellable' => $this->is_sellable,
            'storage_location' => $this->storage_location,
            'notes' => $this->notes,
        ];

        if ($this->itemId) {
            InventoryItem::findOrFail($this->itemId)->update($data);
            session()->flash('message', 'Inventory item updated successfully.');
        } else {
            InventoryItem::create($data);
            session()->flash('message', 'Inventory item created successfully.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function toggleStatus($itemId)
    {
        $item = InventoryItem::findOrFail($itemId);
        $item->is_active = !$item->is_active;
        $item->save();

        session()->flash('message', 'Item status updated successfully.');
    }

    public function confirmDelete($itemId)
    {
        $this->itemId = $itemId;
    }

    public function delete()
    {
        $item = InventoryItem::findOrFail($this->itemId);

        // Check if item has stock
        if ($item->quantity_on_hand > 0) {
            session()->flash('error', 'Cannot delete item with existing stock. Please adjust stock to zero first.');
            return;
        }

        $item->delete();
        session()->flash('message', 'Inventory item deleted successfully.');
        $this->reset(['itemId']);
    }

    public function viewDetails($itemId)
    {
        $this->selectedItem = InventoryItem::with(['product', 'preferredVendor', 'locationStock.location'])->findOrFail($itemId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedItem = null;
    }

    private function generateSKU()
    {
        $prefix = 'ITM';
        $date = now()->format('Ymd');
        $lastItem = InventoryItem::where('sku', 'like', "{$prefix}-{$date}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastItem) {
            $lastNumber = (int) substr($lastItem->sku, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$date}-{$newNumber}";
    }

    public function render()
    {
        $items = InventoryItem::with(['product', 'preferredVendor'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%')
                      ->orWhere('barcode', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->when($this->status_filter === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($this->status_filter === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->when($this->stock_status === 'out_of_stock', function ($query) {
                $query->where('quantity_on_hand', '<=', 0);
            })
            ->when($this->stock_status === 'low_stock', function ($query) {
                $query->whereColumn('quantity_on_hand', '<=', 'reorder_level')
                      ->where('quantity_on_hand', '>', 0);
            })
            ->when($this->stock_status === 'in_stock', function ($query) {
                $query->whereColumn('quantity_on_hand', '>', 'reorder_level');
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        $products = Product::where('status', 'active')->orderBy('name')->get();
        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();
        $categories = InventoryItem::select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('livewire.inventory.inventory-item-index', [
            'items' => $items,
            'products' => $products,
            'vendors' => $vendors,
            'categories' => $categories,
        ]);
    }
}
