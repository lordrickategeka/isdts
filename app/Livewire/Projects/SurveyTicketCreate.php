<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Currency;
use App\Models\SurveyTicket;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Project;
use App\Models\Client;

class SurveyTicketCreate extends Component
{
    public $survey_name, $start_date, $contact_person, $project_id, $client_id, $assigned_user_id;
    public $engineer_user_ids = [];
    public $description, $location, $priority, $status;
    public $assignees = [], $engineers = [], $projects = [], $clients = [];
    public $currencies = [];
    public $links = [];
    public $is_project_survey = '';

    public function updatedIsProjectSurvey($value)
    {
        $this->is_project_survey = $value;
    }

    protected $rules = [
        'survey_name' => 'required|string',
        'assigned_user_id' => 'required|integer',
        'engineer_user_ids' => 'required|array|min:1',
        'start_date' => 'required|date',
        'is_project_survey' => 'required',
        'project_id' => 'nullable|integer',
        'client_id' => 'nullable|integer',
        'contact_person' => 'nullable|string',
    ];

    public function mount()
    {
        $this->assignees = User::permission('can-assign-survey')->get();
        $this->engineers = User::role('Engineer')->get();
        $this->projects = Project::all();
        $this->clients = Client::all();
        $this->currencies = Currency::supportedCurrencies();
    }

    public function submit()
    {
        $this->validate();
        $surveyTicket = SurveyTicket::create([
            'survey_name' => $this->survey_name,
            'assigned_user_id' => $this->assigned_user_id,
            'project_id' => $this->is_project_survey ? $this->project_id : null,
            'client_id' => $this->client_id,
            'contact_person' => $this->contact_person,
            'start_date' => $this->start_date,
            'description' => $this->description,
            'location' => $this->location,
            'priority' => $this->priority,
            'status' => $this->status,
        ]);

        foreach ($this->engineer_user_ids as $engineerId) {
            $token = bin2hex(random_bytes(16));
            $surveyTicket->surveyEngineers()->create([
                'user_id' => $engineerId,
                'token' => $token,
                'link_sent' => false,
            ]);
            $this->links[] = route('survey.form.token', ['token' => $token]);

            // Send email to engineer
            $engineer = User::find($engineerId);
            if ($engineer && $engineer->email) {
                Mail::raw(
                    "You have been assigned a new survey ticket: {$this->survey_name}.\nAccess your survey form here: " . route('survey.form.token', ['token' => $token]),
                    function ($message) use ($engineer) {
                        $message->to($engineer->email)
                            ->subject('New Survey Ticket Assigned');
                    }
                );
            }
        }

        session()->flash('success', 'Survey ticket created successfully!');
        session()->flash('survey_links', $this->links);
        $this->reset(['survey_name','start_date','contact_person','project_id','client_id','assigned_user_id','engineer_user_ids','description','location','priority','status','links','is_project_survey']);
        $this->mount();

        // Redirect to survey ticket list
        return redirect()->route('survey.tickets.list');
    }

    public function render()
    {
        return view('livewire.projects.survey-ticket-create');
    }
}
