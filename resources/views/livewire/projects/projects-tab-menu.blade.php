<ul class="flex border-b border-gray-200">
    <li class="mr-4">
        <button wire:click="$set('activeTab', 'project-sites')"
            class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'project-sites' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
            Project Customers
        </button>
    </li>
    <li class="mr-4">
        <button wire:click="$set('activeTab', 'new-client')"
            class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'new-client' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
            Add Customer
        </button>
    </li>
    <li class="mr-4">
        <button wire:click="$set('activeTab', 'import-export')"
            class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'import-export' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
            Import/Export
        </button>
    </li>
    <li class="mr-4 relative" x-data="{ open: false }">
        <button @click="open = !open" @click.away="open = false"
            class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none flex items-center gap-1 {{ in_array($activeTab, ['project-summary', 'feasibility-details', 'materials', 'cost-summary']) ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
            Project Summary
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg z-50 border border-gray-200"
             style="display: none;">
            <div class="py-1">
                <button wire:click="$set('activeTab', 'project-summary')" @click="open = false"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $activeTab === 'project-summary' ? 'bg-blue-50 text-blue-600' : '' }}">
                    Summary
                </button>
                <button wire:click="$set('activeTab', 'feasibility-details')" @click="open = false"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $activeTab === 'feasibility-details' ? 'bg-blue-50 text-blue-600' : '' }}">
                    Feasibility, Vendor & Cost
                </button>
                <button wire:click="$set('activeTab', 'materials')" @click="open = false"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $activeTab === 'materials' ? 'bg-blue-50 text-blue-600' : '' }}">
                    Materials
                </button>
                <button wire:click="$set('activeTab', 'cost-summary')" @click="open = false"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $activeTab === 'cost-summary' ? 'bg-blue-50 text-blue-600' : '' }}">
                    Cost Summary
                </button>
            </div>
        </div>
    </li>

    <li class="mr-4">
        <button wire:click="$set('activeTab', 'project-milestones')"
            class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'project-milestones' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
            Project Milestones
        </button>
    </li>

    <li class="mr-4">
        <button wire:click="$set('activeTab', 'project-tasks')"
            class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'project-tasks' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
            Project Tasks
        </button>
    </li>

    <li class="mr-4">
        <button wire:click="$set('activeTab', 'project-hierarchy')"
            class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'project-hierarchy' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
            Project Hierarchy
        </button>
    </li>

    <li>
        <button wire:click="$set('activeTab', 'print-content')"
            class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'print-content' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
            Print Content
        </button>
    </li>
</ul>
