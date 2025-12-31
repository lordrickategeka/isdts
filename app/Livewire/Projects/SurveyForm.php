<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Survey;
use App\Models\User;
use App\Models\Project;
use App\Models\Client;


class SurveyForm extends Component
{
    public $survey_name, $start_date, $contact_person, $project_id, $client_id, $assigned_user_id;
    public $engineer_user_ids = [];
    public $step = 1;

    // ...existing survey fields...
    public $company_name, $designation, $nature_of_business, $physical_address, $telephone_number, $alternative_contact, $email_address;
    public $latitude, $longitude, $serving_site;
    public $microwave, $fibre, $service_type, $capacity, $installation_charge, $monthly_charge, $router, $other_equipment, $contract_start_date;
    public $acceptance, $client_signature, $client_signature_date, $sales_person_name, $sales_person_signature, $sales_person_signature_date;
    public $sales_manager, $cco, $credit_control_manager, $cfo, $business_analysis, $network_planning, $implementation_manager, $director;

    // Suggested fields
    public $description, $location, $priority, $status;

    public $assignees = [], $engineers = [], $projects = [], $clients = [];

    protected $rules = [
        'survey_name' => 'required|string',
        'assigned_user_id' => 'required|integer',
        'engineer_user_ids' => 'required|array|min:1',
        'start_date' => 'required|date',
        'project_id' => 'nullable|integer',
        'client_id' => 'nullable|integer',
        'contact_person' => 'nullable|string',
        // ...other rules unchanged...
    ];

    public function mount()
    {
        $this->assignees = User::permission('can-assign-survey')->get();
        $this->engineers = User::role('Engineer')->get();
        $this->projects = Project::all();
        $this->clients = Client::all();
    }

    public function nextStep()
    {
        if ($this->step < 5) {
            $this->step++;
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function submit()
    {
        $this->validate();
        $survey = Survey::create([
            'survey_name' => $this->survey_name,
            'assigned_user_id' => $this->assigned_user_id,
            'project_id' => $this->project_id,
            'client_id' => $this->client_id,
            'contact_person' => $this->contact_person,
            'start_date' => $this->start_date,
            'description' => $this->description,
            'location' => $this->location,
            'priority' => $this->priority,
            'status' => $this->status,
            // ...existing survey fields...
            'company_name' => $this->company_name,
            'designation' => $this->designation,
            'nature_of_business' => $this->nature_of_business,
            'physical_address' => $this->physical_address,
            'telephone_number' => $this->telephone_number,
            'alternative_contact' => $this->alternative_contact,
            'email_address' => $this->email_address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'serving_site' => $this->serving_site,
            'microwave' => $this->microwave,
            'fibre' => $this->fibre,
            'service_type' => $this->service_type,
            'capacity' => $this->capacity,
            'installation_charge' => $this->installation_charge,
            'monthly_charge' => $this->monthly_charge,
            'router' => $this->router,
            'other_equipment' => $this->other_equipment,
            'contract_start_date' => $this->contract_start_date,
            'acceptance' => $this->acceptance,
            'client_signature' => $this->client_signature,
            'client_signature_date' => $this->client_signature_date,
            'sales_person_name' => $this->sales_person_name,
            'sales_person_signature' => $this->sales_person_signature,
            'sales_person_signature_date' => $this->sales_person_signature_date,
            'sales_manager' => $this->sales_manager,
            'cco' => $this->cco,
            'credit_control_manager' => $this->credit_control_manager,
            'cfo' => $this->cfo,
            'business_analysis' => $this->business_analysis,
            'network_planning' => $this->network_planning,
            'implementation_manager' => $this->implementation_manager,
            'director' => $this->director,
        ]);

        // Attach engineers and generate tokens
        $links = [];
        foreach ($this->engineer_user_ids as $engineerId) {
            $token = bin2hex(random_bytes(16));
            $survey->surveyEngineers()->create([
                'user_id' => $engineerId,
                'token' => $token,
                'link_sent' => false,
            ]);
            $links[] = route('survey.form.token', ['token' => $token]);
        }

        // Optionally: send email to engineers with $links here

        session()->flash('success', 'Survey ticket created successfully!');
        session()->flash('survey_links', $links);
        $this->reset();
        $this->mount(); // reload dropdowns
    }

    public $token;

    public function showByToken($token)
    {
        $surveyEngineer = \App\Models\SurveyEngineer::where('token', $token)->firstOrFail();
        $survey = $surveyEngineer->survey;
        $engineer = $surveyEngineer->user;

        // Pre-fill form fields
        $this->survey_name = $survey->survey_name;
        $this->project_id = $survey->project_id;
        $this->client_id = $survey->client_id;
        $this->contact_person = $survey->contact_person;
        $this->start_date = $survey->start_date;
        $this->description = $survey->description;
        $this->location = $survey->location;
        $this->priority = $survey->priority;
        $this->status = $survey->status;
        $this->engineer_user_ids = [$engineer->id];
        $this->token = $token;

        // ...other survey fields as needed...

        return view('livewire.projects.survey-form');
    }

    public function render()
    {
        return view('livewire.projects.survey-form');
    }
}
