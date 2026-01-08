<div class="p-4 border rounded-lg bg-white">
    <h4 class="text-sm font-semibold mb-2">Customer Data Templates</h4>
    <p class="text-xs text-gray-600 mb-4">Download templates and reference CSVs for each import type. Use these templates to prepare your files before importing.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <button wire:click.prevent="$emitTo('customers.customer-data-manager', 'downloadTemplate')"
            class="px-3 py-2 bg-green-600 text-white rounded text-sm">Download Master Template</button>

        <a href="#" class="px-3 py-2 bg-gray-100 border rounded text-sm text-gray-700">View Template Docs</a>
    </div>
</div>
