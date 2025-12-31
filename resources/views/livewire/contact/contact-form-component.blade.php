<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    @if ($successMessage)
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ $successMessage }}</div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input type="text" wire:model.defer="title" class="mt-1 block w-full border rounded p-2" />
                @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">First name</label>
                <input type="text" wire:model.defer="first_name" class="mt-1 block w-full border rounded p-2" />
                @error('first_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Last name</label>
                <input type="text" wire:model.defer="last_name" class="mt-1 block w-full border rounded p-2" />
                @error('middle_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

           
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" wire:model.defer="email" class="mt-1 block w-full border rounded p-2" />
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Phone</label>
                <input type="text" wire:model.defer="phone" class="mt-1 block w-full border rounded p-2" />
                @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            
        </div>


        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h4 class="font-semibold">Identity</h4>
                <label class="block text-sm">National ID</label>
                <input type="text" wire:model.defer="national_id" class="mt-1 block w-full border rounded p-2" />
                @error('national_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Passport number</label>
                <input type="text" wire:model.defer="passport_number" class="mt-1 block w-full border rounded p-2" />
                @error('passport_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Date of birth</label>
                <input type="date" wire:model.defer="date_of_birth" class="mt-1 block w-full border rounded p-2" />
                @error('date_of_birth') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Gender</label>
                <input type="text" wire:model.defer="gender" class="mt-1 block w-full border rounded p-2" />
                @error('gender') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <h4 class="font-semibold">Social / Web</h4>

                <div>
                <label class="block text-sm font-medium">WhatsApp number</label>
                <input type="text" wire:model.defer="whatsapp_number" class="mt-1 block w-full border rounded p-2" />
                @error('whatsapp_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <label class="block text-sm">LinkedIn</label>
                <input type="url" wire:model.defer="linkedin" class="mt-1 block w-full border rounded p-2" />
                @error('linkedin') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Facebook</label>
                <input type="url" wire:model.defer="facebook" class="mt-1 block w-full border rounded p-2" />
                @error('facebook') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Website</label>
                <input type="url" wire:model.defer="website" class="mt-1 block w-full border rounded p-2" />
                @error('website') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h4 class="font-semibold">Address</h4>
                <label class="block text-sm">Address</label>
                <textarea wire:model.defer="address" class="mt-1 block w-full border rounded p-2" rows="2"></textarea>
                @error('address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">City</label>
                <input type="text" wire:model.defer="city" class="mt-1 block w-full border rounded p-2" />
                @error('city') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">State / Region</label>
                <input type="text" wire:model.defer="state_region" class="mt-1 block w-full border rounded p-2" />
                @error('state_region') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Postal code</label>
                <input type="text" wire:model.defer="postal_code" class="mt-1 block w-full border rounded p-2" />
                @error('postal_code') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Country</label>
                <input type="text" wire:model.defer="country" class="mt-1 block w-full border rounded p-2" />
                @error('country') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <h4 class="font-semibold">Building / Location</h4>
                <label class="block text-sm">Building name</label>
                <input type="text" wire:model.defer="building_name" class="mt-1 block w-full border rounded p-2" />
                @error('building_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Floor number</label>
                <input type="text" wire:model.defer="floor_number" class="mt-1 block w-full border rounded p-2" />
                @error('floor_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Landmark</label>
                <input type="text" wire:model.defer="landmark" class="mt-1 block w-full border rounded p-2" />
                @error('landmark') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Latitude</label>
                <input type="text" wire:model.defer="latitude" class="mt-1 block w-full border rounded p-2" />
                @error('latitude') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Longitude</label>
                <input type="text" wire:model.defer="longitude" class="mt-1 block w-full border rounded p-2" />
                @error('longitude') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h4 class="font-semibold">Organization</h4>
                <label class="block text-sm">Organization name</label>
                <input type="text" wire:model.defer="organization_name" class="mt-1 block w-full border rounded p-2" />
                @error('organization_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Department</label>
                <input type="text" wire:model.defer="department" class="mt-1 block w-full border rounded p-2" />
                @error('department') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Job title</label>
                <input type="text" wire:model.defer="job_title" class="mt-1 block w-full border rounded p-2" />
                @error('job_title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <h4 class="font-semibold">Billing</h4>
                <label class="inline-flex items-center mt-2">
                    <input type="checkbox" wire:model.defer="is_billing_contact" class="mr-2" />
                    <span>Is billing contact</span>
                </label>

                <label class="block text-sm mt-2">Billing email</label>
                <input type="email" wire:model.defer="billing_email" class="mt-1 block w-full border rounded p-2" />
                @error('billing_email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Billing phone</label>
                <input type="text" wire:model.defer="billing_phone" class="mt-1 block w-full border rounded p-2" />
                @error('billing_phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">TIN / Tax number</label>
                <input type="text" wire:model.defer="TIN_number" class="mt-1 block w-full border rounded p-2" />
                @error('TIN_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <label class="block text-sm mt-2">Invoice delivery method</label>
                <input type="text" wire:model.defer="invoice_delivery_method" class="mt-1 block w-full border rounded p-2" />
                @error('invoice_delivery_method') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        @if(count($customFields))
            <div class="mb-3">
                <h4 class="font-semibold">Additional fields</h4>
                @foreach($customFields as $field)
                    <div class="mt-2">
                        <label class="block text-sm font-medium">{{ $field->label }}</label>
                        @if($field->type === 'textarea')
                            <textarea wire:model.defer="custom.{{ $field->name }}" class="mt-1 block w-full border rounded p-2" rows="3"></textarea>
                        @elseif($field->type === 'select')
                            <select wire:model.defer="custom.{{ $field->name }}" class="mt-1 block w-full border rounded p-2">
                                <option value="">-- Select --</option>
                                @foreach((array) $field->options as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" wire:model.defer="custom.{{ $field->name }}" class="mt-1 block w-full border rounded p-2" />
                        @endif
                        @error('custom.'.$field->name) <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endforeach
            </div>
        @endif

        <div>
            <button type="submit" class="inline-block px-4 py-2 bg-blue-600 text-white rounded">Send</button>
        </div>
    </form>
</div>

