<?php

namespace Dotgix\Chatsupport\app\Livewire\Visitor;

use Domain\Users\Models\User;
use Livewire\Component;
use Dotgix\Chatsupport\Models\conversations;
use Livewire\Attributes\On;

class Index extends Component
{
    public $showMainChat = false;
    public $conversation;
    public $conversationId;
    public $receiverId;
    public $defaultAdminId;public $lastMessage;public $lastMessageDate;public $conversationStatus;
    #[On('messageReceived')]
    public function messageReceived($messageId)
    {
        $this->checkExistingConversation();
    }
    public function mount()
    {
        $adminUser = User::where('role', 'Admin')->first();
        $this->defaultAdminId = $adminUser ? $adminUser->id : null;
        $this->checkExistingConversation();
    }
    
    public function checkExistingConversation()
    {
        $sessionId = session()->getId(); // Retrieve the current session ID
        $this->conversation = Conversations::where(function ($query) use ($sessionId) {
            $query->where('sender_session_id', $sessionId)
                  ->orWhere('receiver_session_id', $sessionId); // Consider both roles
        })->first();
        if ($this->conversation) {
            $this->conversationId = $this->conversation->id;
            $this->lastMessage = $this->conversation->messages()->latest()->first(); // Fetch the last message
            $this->lastMessageDate = $this->lastMessage ? $this->lastMessage->created_at->format('g:i a') : '';
            $this->conversationStatus = $this->conversation->read_at ? 'Active' : 'Unanswered'; // Determine status based on read_at
        }
    }

    public function showChatBox()
    {
        $sessionId = session()->getId(); // Retrieve visitor session ID
        $receiverId = $this->defaultAdminId; // Admin as receiver
        $conversation = conversations::where(function ($query) use ($sessionId) {
            $query->where('sender_session_id', $sessionId)
                  ->orWhere('receiver_session_id', $sessionId);
        })->first();

        if (!$conversation) {
            $conversation = conversations::create([
                'sender_session_id' => $sessionId, // Visitor is identified by session
                'receiver_id' => $receiverId, // Admin is the receiver
                'status' => 'unassigned',
            ]);
        }

        $this->showMainChat = true;
        $this->conversationId = $conversation->id;
    }

    public function render()
    {
        return view('chatsupport::livewire.visitor.index')->layout('layouts.Visitor');
    }
}
