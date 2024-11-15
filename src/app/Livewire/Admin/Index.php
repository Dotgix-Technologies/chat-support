<?php

namespace Dotgix\Chatsupport\app\Livewire\Admin;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('chatsupport::livewire.admin.index')->layout('layouts.Admin');
    }
}
