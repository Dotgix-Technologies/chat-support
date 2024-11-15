<?php

namespace Dotgix\Chatsupport\app\Livewire\Consultant;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Dotgix\Chatsupport\Models\messages;
use Illuminate\Support\Facades\Storage;
use Dotgix\Chatsupport\Models\conversations;
use Dotgix\Chatsupport\app\Events\MessageSentEvent;


class ChatBox extends Component
{
    use WithFileUploads; // Add this trait
    public $selectedConversation;
    public $conversationId;
    public $messageBody;
    public $file;
    public $uploadProgress = 0;
    public $loadedMessages;
    public $paginate_var = 50;
    #[On('messageReceived')]
    public function messageReceived($messageId)
    {
        $this->markAsread();
        // Check if conversation ID is set and is a number
        if ($this->conversationId == $messageId && is_numeric($messageId)) {
            // Fetch the message by its ID
            $message = messages::where('conversations_id', $messageId)
                ->with('sender')
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
    public function loadMessages()
    {
        $userId = auth()->id();
        $this->conversationId = $this->selectedConversation->id;

        // Get count of messages
        $count = messages::where('conversations_id', $this->conversationId)->count();

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
      
    
           messages::where('conversations_id',$this->selectedConversation->id)
                    ->whereNull('read_at')->whereNull('sender_id')
                    ->update(['read_at'=>now()]);
    }
    public function mount()
    {
        // Initialize loadedMessages as an empty collection or array to avoid null issues
        $this->loadedMessages = collect();  // or $this->loadedMessages = [];
        $this->markAsread();
        $this->loadMessages();
    }

    public function render()
    {
        return view('chatsupport::livewire.consultant.chat-box');
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
