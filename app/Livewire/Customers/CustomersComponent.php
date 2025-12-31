<?php

namespace App\Livewire\Customers;

use Livewire\Component;

class CustomersComponent extends Component
{
    public $demoClients;

    public function mount()
    {
        $this->demoClients = collect([
            [
                'id' => 1,
                'company' => 'Acme Corp',
                'contact_person' => 'Jane Doe',
                'category' => 'Corporate',
                'category_type' => 'Enterprise',
                'email' => 'jane.doe@acme.example',
                'phone' => '+256 700 000001',
                'services' => [
                    ['service' => 'Internet', 'product' => 'Fiber 100Mbps', 'capacity' => '100Mbps', 'monthly_charge' => 120000],
                ],
                'payment_type' => 'postpaid',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'company' => 'Beta Solutions',
                'contact_person' => 'John Smith',
                'category' => 'Home',
                'category_type' => null,
                'email' => 'john@beta.example',
                'phone' => '+256 700 000002',
                'services' => [],
                'payment_type' => 'prepaid',
                'status' => 'pending_approval',
            ],
            [
                'id' => 3,
                'company' => 'Gamma Traders',
                'contact_person' => 'Alice N',
                'category' => 'SME',
                'category_type' => 'Retail',
                'email' => 'alice@gamma.example',
                'phone' => '+256 700 000003',
                'services' => [
                    ['service' => 'Hosting', 'product' => 'Business Host', 'capacity' => null, 'monthly_charge' => 30000],
                ],
                'payment_type' => null,
                'status' => 'approved',
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.customers.customers-component');
    }
}
