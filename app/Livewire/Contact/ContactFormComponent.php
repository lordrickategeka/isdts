<?php

namespace App\Livewire\Contact;

use Livewire\Component;
use App\Models\CustomField;
use App\Models\ContactMessage;
use Illuminate\Support\Arr;

class ContactFormComponent extends Component
{

    public $name;
    public $email;
    public $phone;
    public $subject;
    public $message;
    // name parts
    public $title;
    public $first_name;
    public $middle_name;
    public $last_name;

    // optional identity
    public $national_id;
    public $passport_number;
    public $date_of_birth;
    public $gender;

    // social
    public $whatsapp_number;
    public $linkedin;
    public $facebook;
    public $website;

    // address
    public $address;
    public $city;
    public $state_region;
    public $postal_code;
    public $country;

    // building
    public $building_name;
    public $floor_number;
    public $landmark;
    public $latitude;
    public $longitude;

    // organization
    public $organization_name;
    public $department;
    public $job_title;

    // billing
    public $is_billing_contact = false;
    public $billing_email;
    public $billing_phone;
    public $TIN_number;
    public $invoice_delivery_method;
    public $custom = []; // associative array for custom field values

    public $customFields = []; // loaded CustomField models

    public $successMessage;

    public function mount()
    {
        // load custom fields declared for 'contact' model
        $this->customFields = CustomField::where('model', 'contact')->get();

        // initialize default values for custom fields
        foreach ($this->customFields as $f) {
            $this->custom[$f->name] = null;
        }
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ];

        // additional simple rules
        $rules = array_merge($rules, [
            'title' => 'nullable|string|max:20',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:100',
            'passport_number' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:50',
            'linkedin' => 'nullable|url',
            'facebook' => 'nullable|url',
            'website' => 'nullable|url',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state_region' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'floor_number' => 'nullable|string|max:50',
            'landmark' => 'nullable|string|max:255',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'organization_name' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'is_billing_contact' => 'nullable|boolean',
            'billing_email' => 'nullable|email|max:255',
            'billing_phone' => 'nullable|string|max:50',
            'TIN_number' => 'nullable|string|max:100',
            'invoice_delivery_method' => 'nullable|string|max:100',
        ]);

        // add rules from custom fields
        foreach ($this->customFields as $field) {
            $rule = [];
            if ($field->required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            // add simple validation based on type
            switch ($field->type) {
                case 'email':
                    $rule[] = 'email';
                    break;
                case 'number':
                    $rule[] = 'numeric';
                    break;
                case 'text':
                case 'textarea':
                default:
                    $rule[] = 'string';
                    break;
            }

            $rules['custom.'.$field->name] = implode('|', $rule);
        }

        return $rules;
    }

    public function submit()
    {
        $data = $this->validate();

        // build payload
        $payload = [
            'title' => $this->title,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
            'national_id' => $this->national_id,
            'passport_number' => $this->passport_number,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'whatsapp_number' => $this->whatsapp_number,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'website' => $this->website,
            'address' => $this->address,
            'city' => $this->city,
            'state_region' => $this->state_region,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'building_name' => $this->building_name,
            'floor_number' => $this->floor_number,
            'landmark' => $this->landmark,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'organization_name' => $this->organization_name,
            'department' => $this->department,
            'job_title' => $this->job_title,
            'is_billing_contact' => $this->is_billing_contact,
            'billing_email' => $this->billing_email,
            'billing_phone' => $this->billing_phone,
            'TIN_number' => $this->TIN_number,
            'invoice_delivery_method' => $this->invoice_delivery_method,
            'custom' => $this->custom,
        ];

        ContactMessage::create([
            'tenant_id' => null,
            'title' => $this->title,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
            'national_id' => $this->national_id,
            'passport_number' => $this->passport_number,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'whatsapp_number' => $this->whatsapp_number,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'website' => $this->website,
            'address' => $this->address,
            'city' => $this->city,
            'state_region' => $this->state_region,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'building_name' => $this->building_name,
            'floor_number' => $this->floor_number,
            'landmark' => $this->landmark,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'organization_name' => $this->organization_name,
            'department' => $this->department,
            'job_title' => $this->job_title,
            'is_billing_contact' => $this->is_billing_contact,
            'billing_email' => $this->billing_email,
            'billing_phone' => $this->billing_phone,
            'TIN_number' => $this->TIN_number,
            'invoice_delivery_method' => $this->invoice_delivery_method,
            'data' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->reset([
            'title','first_name','middle_name','last_name','name','email','phone','subject','message',
            'national_id','passport_number','date_of_birth','gender',
            'whatsapp_number','linkedin','facebook','website',
            'address','city','state_region','postal_code','country',
            'building_name','floor_number','landmark','latitude','longitude',
            'organization_name','department','job_title',
            'is_billing_contact','billing_email','billing_phone','TIN_number','invoice_delivery_method','custom'
        ]);

        // reinitialize custom keys
        foreach ($this->customFields as $f) {
            $this->custom[$f->name] = null;
        }

        $this->successMessage = 'Thanks! Your message has been sent.';

        $this->emit('contactFormSubmitted');
    }

    public function render()
    {
        return view('livewire.contact.contact-form-component');
    }
}
