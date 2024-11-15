<?php

namespace Dotgix\Chatsupport\app\Livewire\Consultant;

use Domain\Users\Models\User;
use Livewire\Component;
use Dotgix\Chatsupport\Models\messages;
use Livewire\Attributes\On;
use Dotgix\Chatsupport\Models\conversations;
use Dotgix\Chatsupport\app\Events\MessageSentEvent;

class ChatList extends Component
{
    public $selectedConversation;
    public $filterType = 'all'; // Default filter type
    public $messages;
    public $selectedConversationId;

    protected $listeners = ['refresh' => 'refreshChatList'];

    #[On('messageSent')]
    #[On('messageReceived')]
    public function refresh()
    {
        $this->messages = $this->getFilteredConversations();
    }
    public function archiveChat($conversationId)
    {
        $conversation = Conversations::find($conversationId);
        $conversation->status = 'archived';
        $conversation->save();
        $this->messages = $this->getFilteredConversations();
    }

    public function deleteChat($conversationId)
    {
        // Delete chat logic here
    }
    public function mount()
    {

        $this->messages = $this->getFilteredConversations();
    }

    // Handle chat filtering by updating the conversations list
    public function filterChat($filterType)
    {
        $this->filterType = $filterType;
        $this->messages = $this->getFilteredConversations();
    }
    public function unarchiveChat($conversationId)
    {
        $conversation = Conversations::find($conversationId);
        $conversation->status = 'general';
        $conversation->save();
        $this->messages = $this->getFilteredConversations();
    }
    public function getFilteredConversations()
    {
        $query = Conversations::query();
        
        // Apply filter based on filterType
        switch ($this->filterType) {
            case 'all':
                // No specific filters for "All"
                break;
            case 'general':
                // Filter conversations where the receiver_id is not null (authenticated users)
                $query->whereNotNull('receiver_id');
                // Exclude conversations with status 'archived'
                $query->where('status', '<>', 'archived');
                break;
            case 'archived':
                // Filter conversations with status 'archived'
                $query->where('status', 'archived');
                break;
            default:
                // No filter applied
                break;
        }
    
        // Apply filter for conversations assigned to the current user
        $query->where('receiver_id', auth()->user()->id);
    
        // Fetch the results
        return $query->latest('updated_at')->whereNull('deleted_at')->get();
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
        return view('chatsupport::livewire.consultant.chat-list', [
            'conversations' => $this->messages,
            'users' => $users,
        ]);
    }
}
