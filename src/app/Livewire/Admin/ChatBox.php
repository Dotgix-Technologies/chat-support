<?php

namespace Dotgix\Chatsupport\app\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Dotgix\Chatsupport\Models\messages;
use Livewire\Attributes\On;
use Dotgix\Chatsupport\Models\conversations;
use Livewire\WithFileUploads;
use App\Events\MessageSentEvent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ChatBox extends Component
{
    use WithFileUploads; // Add this trait
    public $selectedConversation;
    public $conversationId;
    public $messageBody;
    public $file;
    public $loadedMessages;
    public $paginate_var = 50;
    public $consultants;
    public $uploadProgress = 0;
    public $Assignedto;
    #[On('messageReceived')]
    public function messageReceived($messageId)
    {

        // Check if conversation ID is set and is a number
        if ($this->conversationId == $messageId && is_numeric($messageId)) {
            $this->markAsread();
            // Fetch the message by its ID
            $message = messages::where('conversations_id', $messageId)
                ->orderBy('created_at', 'desc')
                ->first();
            $this->loadedMessages->push($message);
            $this->dispatch('chat-update');
        }
    }
    #[On('fileUpload:progress')]
    public function updateProgress($progress)
    {
        $this->uploadProgress = $progress;
    }
    public function assignConsultant($consultantId)
    {
        // Find the conversation
        $conversation = conversations::find($this->selectedConversation->id);

        // Check if the conversation exists
        if ($conversation) {
            // Update the receiver_id for the conversation
            $conversation->receiver_id = $consultantId;
            $conversation->save();

            // Update the receiver_id for all messages in the conversation
            $messages = messages::where('conversations_id', $this->selectedConversation->id)->get();

            foreach ($messages as $message) {
                if ($message->receiver_id != null) {
                    $message->receiver_id = $consultantId;
                    $message->save();
                }
                broadcast(new MessageSentEvent($message))->toOthers();
            }
            $this->loadMessages();
        } else {
            // Handle the case where the conversation is not found
            session()->flash('error', 'Conversation not found.');
        }
    }


    public function archiveChat($conversationId)
    {
        $this->selectedConversation = Conversations::find($conversationId);
        $this->selectedConversation->status = 'archived';
        $this->selectedConversation->save();
        $this->loadMessages();
    }
    public function unarchiveChat($conversationId)
    {
        $this->selectedConversation = Conversations::find($conversationId);
        $this->selectedConversation->status = 'general';
        $this->selectedConversation->save();

        $this->loadMessages();
    }
    public function loadMessages()
    {
        $userId = auth()->id();
        $this->conversationId = $this->selectedConversation->id;
        $this->consultants = User::where('role', 'Consultant')->get();
        // Get count of messages
        $count = messages::where('conversations_id', $this->conversationId)->count();
        $this->Assignedto = User::findOrFail($this->selectedConversation->receiver_id);
        // Load messages with sender relationship
        $this->loadedMessages = messages::where('conversations_id', $this->selectedConversation->id)
            ->with('sender') // Ensure sender relationship is loaded
            ->skip($count - $this->paginate_var)
            ->take($this->paginate_var)
            ->get();

        return $this->loadedMessages;
    }


    public function sendMessage()
    {
        
        $this->validate([
            'messageBody' => 'nullable|string|max:5000',
            'file' => 'nullable|file|max:102400',
        ], [
            'required_without_all' => 'You must provide either a message or a file.',
        ]);
        // Ensure that at least one of messageBody or file is provided
        if (!$this->messageBody && !$this->file) {
            $this->addError('input', 'Please provide either a message or a file.');
            return;
        }
        // Initialize file path
        $filePath = null;
        // Define the directory to store the file
        $directory = 'sharedFiles';
        // Check if a file is uploaded
        if ($this->file) {
            // Ensure the directory exists
            $this->ensureDirectoryExists($directory);

            // Store the file and get the path
            $filePath = $this->file->store($directory, 'public');
        }

        // Create or update the message based on the presence of a file
        $createdMessage = messages::create([
            'conversations_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->sender_id,
            'body' => $this->file ? $this->file->getClientOriginalName() : $this->messageBody,
            'type' => $this->file ? 'file' : 'text',
            'file_path' => $filePath, // Store the file path if a file is uploaded
        ]);

        // Push the message to the loaded messages
        $this->loadedMessages->push($createdMessage);

        // Update the conversation's timestamp
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        // Broadcast the message sent event
        broadcast(new MessageSentEvent($createdMessage))->toOthers();

        // Dispatch the event to refresh the chat
        $this->dispatch('messageSent');

        // Reset the properties
        $this->reset(['messageBody', 'file']);
    }
    public function removeFile()
    {

        $this->file = null;
    }
    public function markAsread()
    {

        messages::where('conversations_id', $this->selectedConversation->id)
            ->whereNull('read_at')->whereNull('sender_id')
            ->update(['read_at' => now()]);
    }
    public function mount()
    {

        $this->loadedMessages = collect();  // or $this->loadedMessages = [];
        $this->markAsread();
        $this->loadMessages();
    }
    public function render()
    {
        return view('chatsupport::livewire.admin.chat-box');
    }

    /**
     * Ensure the directory exists.
     *
     * @param string $directory
     * @return void
     */
    protected function ensureDirectoryExists($directory)
    {
        // Get the full path to the directory
        $path = storage_path("app/public/{$directory}");

        // Check if the directory exists
        if (!File::isDirectory($path)) {
            // Create the directory if it does not exist
            File::makeDirectory($path, 0755, true);
        }
    }
}
