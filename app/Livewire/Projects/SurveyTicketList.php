<?php
namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\SurveyTicket;

class SurveyTicketList extends Component
{
    public $tickets;
    public $editId = null;
    public $editData = [];
    public $showId = null;
    public $showData = [];

    public function mount()
    {
        $this->tickets = SurveyTicket::with(['assignedUser', 'project', 'client'])->latest()->get();
    }

    public function delete($id)
    {
        SurveyTicket::findOrFail($id)->delete();
        $this->tickets = SurveyTicket::with(['assignedUser', 'project', 'client'])->latest()->get();
        session()->flash('success', 'Survey ticket deleted successfully.');
    }

    public function edit($id)
    {
        $ticket = SurveyTicket::findOrFail($id);
        $this->editId = $id;
        $this->editData = $ticket->toArray();
    }

    public function update()
    {
        if ($this->editId) {
            $ticket = SurveyTicket::findOrFail($this->editId);
            $ticket->update($this->editData);
            $this->editId = null;
            $this->editData = [];
            $this->tickets = SurveyTicket::with(['assignedUser', 'project', 'client'])->latest()->get();
            session()->flash('success', 'Survey ticket updated successfully.');
        }
    }

    public function show($id)
    {
        $ticket = SurveyTicket::with(['assignedUser', 'project', 'client'])->findOrFail($id);
        $this->showId = $id;
        $this->showData = $ticket->toArray();
    }

    public function closeShow()
    {
        $this->showId = null;
        $this->showData = [];
    }

    public function render()
    {
        return view('livewire.projects.survey-ticket-list');
    }
}
