<?php

namespace Dotgix\Chatsupport\app\Livewire\Consultant;

use Livewire\Component;
use Dotgix\Chatsupport\Models\messages;
use Dotgix\Chatsupport\Models\conversations;

class Chat extends Component
{
    public $query;
    public $selectedConversation;
    public function mount()
    {

        $this->selectedConversation= conversations::findOrFail($this->query);

       messages::where('conversations_id',$this->selectedConversation->id)
                ->whereNull('read_at')
                ->update(['read_at'=>now()]);
    }
    public function render()
    {
        return view('chatsupport::livewire.consultant.chat')->layout('layouts.Consultant');
    }
}
