<?php

namespace App\Livewire\Currency;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Currency;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class CurrencyIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 6;
    public $showModal = false;
    public $editMode = false;
    public $activeTab = 'currencies';
    
    // Form fields
    public $currencyId;
    public $code;
    public $name;
    public $symbol;
    public $numeric_code;
    public $decimal_places = 2;
    public $decimal_separator = '.';
    public $thousand_separator = ',';
    public $is_active = true;
    public $is_default = false;

    protected $rules = [
        'code' => 'required|string|max:3|unique:currencies,code',
        'name' => 'required|string|max:255',
        'symbol' => 'nullable|string|max:10',
        'numeric_code' => 'nullable|integer',
        'decimal_places' => 'required|integer|min:0|max:8',
        'decimal_separator' => 'required|string|max:1',
        'thousand_separator' => 'required|string|max:1',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->currencyId = null;
        $this->code = '';
        $this->name = '';
        $this->symbol = '';
        $this->numeric_code = null;
        $this->decimal_places = 2;
        $this->decimal_separator = '.';
        $this->thousand_separator = ',';
        $this->is_active = true;
        $this->is_default = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        if ($this->editMode) {
            $this->rules['code'] = 'required|string|max:3|unique:currencies,code,' . $this->currencyId;
        }

        $this->validate();

        DB::beginTransaction();
        try {
            // If setting as default, unset other defaults
            if ($this->is_default) {
                Currency::where('is_default', true)->update(['is_default' => false]);
            }

            $data = [
                'code' => strtoupper($this->code),
                'name' => $this->name,
                'symbol' => $this->symbol,
                'numeric_code' => $this->numeric_code,
                'decimal_places' => $this->decimal_places,
                'decimal_separator' => $this->decimal_separator,
                'thousand_separator' => $this->thousand_separator,
                'is_active' => $this->is_active,
                'is_default' => $this->is_default,
            ];

            if ($this->editMode) {
                Currency::findOrFail($this->currencyId)->update($data);
                session()->flash('success', 'Currency updated successfully.');
            } else {
                Currency::create($data);
                session()->flash('success', 'Currency created successfully.');
            }

            DB::commit();
            Cache::forget('currencies');
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to save currency: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);
        
        $this->currencyId = $currency->id;
        $this->code = $currency->code;
        $this->name = $currency->name;
        $this->symbol = $currency->symbol;
        $this->numeric_code = $currency->numeric_code;
        $this->decimal_places = $currency->decimal_places;
        $this->decimal_separator = $currency->decimal_separator;
        $this->thousand_separator = $currency->thousand_separator;
        $this->is_active = $currency->is_active;
        $this->is_default = $currency->is_default;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function toggleActive($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->update(['is_active' => !$currency->is_active]);
        Cache::forget('currencies');
        session()->flash('success', 'Currency status updated.');
    }

    public function setDefault($id)
    {
        DB::beginTransaction();
        try {
            Currency::where('is_default', true)->update(['is_default' => false]);
            $currency = Currency::findOrFail($id);
            $currency->update(['is_default' => true]);
            
            // Set default country based on this currency
            $defaultCountry = Country::whereHas('currencies', function ($query) use ($currency) {
                $query->where('currencies.id', $currency->id)
                      ->where('country_currency.is_primary', true);
            })->first();

            if ($defaultCountry) {
                Country::where('is_default', true)->update(['is_default' => false]);
                $defaultCountry->update(['is_default' => true]);
            }

            DB::commit();
            Cache::forget('currencies');
            session()->flash('success', 'Default currency updated.' . ($defaultCountry ? " {$defaultCountry->name} set as default country." : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to set default currency.');
        }
    }

    public function syncCurrencies()
    {
        try {
            Artisan::call('currencies:sync');
            Cache::forget('currencies');
            session()->flash('success', 'Currencies synced successfully from API.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to sync currencies: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $currency = Currency::findOrFail($id);
            
            if ($currency->is_default) {
                session()->flash('error', 'Cannot delete the default currency.');
                return;
            }
            
            $currency->delete();
            Cache::forget('currencies');
            session()->flash('success', 'Currency deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete currency: ' . $e->getMessage());
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function setDefaultCountry($id)
    {
        DB::beginTransaction();
        try {
            Country::where('is_default', true)->update(['is_default' => false]);
            $country = Country::findOrFail($id);
            $country->update(['is_default' => true]);
            
            // Set default currency based on this country's primary currency
            $primaryCurrency = $country->currencies()
                ->wherePivot('is_primary', true)
                ->first();

            if ($primaryCurrency) {
                Currency::where('is_default', true)->update(['is_default' => false]);
                $primaryCurrency->update(['is_default' => true]);
                Cache::forget('currencies');
            }

            DB::commit();
            session()->flash('success', 'Default country updated.' . ($primaryCurrency ? " {$primaryCurrency->code} set as default currency." : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to set default country.');
        }
    }

    public function render()
    {
        $currencies = Currency::query()
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('symbol', 'like', '%' . $this->search . '%');
            })
            ->orderBy('is_default', 'desc')
            ->orderBy('code')
            ->paginate($this->perPage);

        $countries = Country::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('alpha2', 'like', '%' . $this->search . '%')
                    ->orWhere('alpha3', 'like', '%' . $this->search . '%');
            })
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.currency.currency-index', [
            'currencies' => $currencies,
            'countries' => $countries
        ]);
    }
}
