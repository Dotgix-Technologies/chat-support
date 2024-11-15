<?php

namespace Dotgix\Chatsupport\app\Livewire\Admin;

use Livewire\Component;
use Dotgix\Chatsupport\Models\messages;
use Livewire\Attributes\On;
use App\Models\User;
use Dotgix\Chatsupport\Models\conversations;
use Dotgix\Chatsupport\app\Events\MessageSentEvent;

class ChatList extends Component
{
    public $selectedConversation;
    public $filterType = 'all'; // Default filter type
    public $messages;
    public $consultants;
    public $selectedConversationId;

    protected $listeners = ['refresh' => 'refreshChatList'];

    #[On('messageSent')]
    #[On('messageReceived')]
    public function refresh()
    {
        $this->messages = $this->getFilteredConversations();
    }
    public function assignConsultant($consultantId)
    {
        // Find the conversation
        $conversation = conversations::find($this->selectedConversationId);
    
        // Check if the conversation exists
        if ($conversation) {
            // Update the receiver_id for the conversation
            $conversation->receiver_id = $consultantId;
            $conversation->save();
    
            // Update the receiver_id for all messages in the conversation
            $messages = messages::where('conversations_id', $this->selectedConversationId)->get();
    
            foreach ($messages as $message) {
                if( $message->receiver_id!=null){
                $message->receiver_id = $consultantId;
                $message->save();}
                broadcast(new MessageSentEvent($message))->toOthers();
            }
            $this->messages = $this->getFilteredConversations();
            $this->reset('selectedConversationId');
        } else {
            // Handle the case where the conversation is not found
            session()->flash('error', 'Conversation not found.');
        }

    }
    

    public function archiveChat($conversationId)
    {
        $conversation = Conversations::find($conversationId);
        $conversation->status = 'archived';
        $conversation->save();
        $this->messages = $this->getFilteredConversations();
    }
    public function unarchiveChat($conversationId)
    {
        $conversation = Conversations::find($conversationId);
        $conversation->status = 'general';
        $conversation->save();
        $this->messages = $this->getFilteredConversations();
    }
    public function deleteChat($conversationId)
    {
        // Delete chat logic here
    }
    public function mount()
    {
        $this->consultants = User::where('role', 'Consultant')->get();

        $this->messages = $this->getFilteredConversations();
    }

    // Handle chat filtering by updating the conversations list
    public function filterChat($filterType)
    {
        $this->filterType = $filterType;
        $this->messages = $this->getFilteredConversations();
    }

    public function getFilteredConversations()
    {
        $query = conversations::query();
        // Exclude conversations with status 'archived' from other filters
        if (in_array($this->filterType, ['general', 'assigned', 'unassigned'])) {
            $query->where('status', '<>', 'archived');
        }
        switch ($this->filterType) {
            case 'all':
                // No specific filters for "All"
                break;

            case 'general':
                // Filter conversations where the receiver_id is not null (authenticated users)
                $query->whereNotNull('receiver_id');
                break;

            case 'assigned':
                // Filter conversations where the receiver is a consultant
                $query->whereHas('receiver', function ($q) {
                    $q->where('role', 'Consultant');
                });
                break;
            case 'unassigned':
                // Filter conversations where the receiver is an admin
                $query->whereHas('receiver', function ($q) {
                    $q->where('role', 'Admin');
                });
                break;

            case 'archived':
                // Filter conversations with status 'archived'
                $query->where('status', 'archived');
                break;

            default:
                // No filter applied
                break;
        }

        return $query->with('receiver')->latest('updated_at')->whereNull('deleted_at')->get();
    }


    // Method to handle deletion of conversation by the authenticated user
    public function deleteByUser($id)
    {
        $userId = auth()->id();
        $messages = Conversations::find(decrypt($id));

        if ($messages) {
            // Update messages to reflect user deletion
            $messages->messages()->each(function ($message) use ($userId) {
                if ($message->sender_id === $userId) {
                    $message->update(['sender_deleted_at' => now()]);
                } elseif ($message->receiver_id === $userId) {
                    $message->update(['receiver_deleted_at' => now()]);
                }
            });

            // Check if both sender and receiver have deleted the conversation
            $isFullyDeleted = $messages->messages()
                ->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->orWhere('receiver_id', $userId);
                })
                ->whereNull('sender_deleted_at')
                ->orWhereNull('receiver_deleted_at')
                ->doesntExist();

            if ($isFullyDeleted) {
                $messages->forceDelete();
            }
        }

        // Redirect back to chat list after deletion
        return redirect(route('chat.index'));
    }
    public function render()
    {
        $users = User::all(); // Load all users if needed
        return view('chatsupport::livewire.admin.chat-list', [
            'conversations' => $this->messages,
            'users' => $users,
        ]);
    }
}
