<?php

namespace Dotgix\Chatsupport\app\Livewire\Consultant;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('chatsupport::livewire.consultant.index')->layout('layouts.Consultant');
    }
}
