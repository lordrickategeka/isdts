<div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Ownership & Governance</h2>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-plus mr-2"></i>Add Person
            </button>
        </div>

        <!-- Project Persons List -->
        <div class="space-y-4">
            @forelse($projectPersons as $person)
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $person->user->name ?? $person->name }}</h3>
                            <p class="text-sm text-gray-600">{{ ucfirst($person->role) }}</p>
                            @if($person->responsibilities)
                                <p class="text-sm text-gray-500 mt-2">{{ $person->responsibilities }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-users text-5xl mb-3"></i>
                    <p class="text-lg">No team members assigned yet</p>
                    <p class="text-sm">Add team members and define their roles</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
