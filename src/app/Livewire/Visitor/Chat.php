<?php

namespace Dotgix\Chatsupport\app\Livewire\Visitor;

use Livewire\Component;
use Dotgix\Chatsupport\Models\messages;
use Livewire\Attributes\On;
use Dotgix\Chatsupport\Models\conversations;
use Dotgix\Chatsupport\app\Events\MessageSentEvent;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Chat extends Component
{
    use WithFileUploads; // Add this trait
    public $conversation;
    public $messageBody;
    public $file;
    public $conversationId;
    public $recieverId;
    public $loadedMessages;
    public $uploadProgress = 0;
     // Track the progress of the upload
     
    #[On('messageReceived')]
    public function messageReceived($messageId)
    {

        // Check if conversation ID is set and is a number
        if ($this->conversationId == $messageId && is_numeric($messageId)) {
        
            // Fetch the message by its ID, confirming the receiver and unread status
            $message = messages::where('conversations_id', $messageId)
                ->where('receiver_id', null)  // Confirm the current user is the receiver
                ->whereNull('read_at')               // Check if the message is unread
                ->orderBy('created_at', 'desc')
                ->first();
    
            // If the message exists, mark it as read
            if ($message) {
                $this->markAsread();
                $this->loadedMessages->push($message);
                $this->dispatch('chat-update');
            }
        }
    }

    #[On('fileUpload:progress')]
    public function updateProgress($progress)
    {
        $this->uploadProgress = $progress;
    }
    public function sendMessage()
    {
        // Custom validation rule to ensure either messageBody or file is present
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

        $filePath = null;
        $directory = 'sharedFiles';

        if ($this->file) {
            $this->ensureDirectoryExists($directory);
            $filePath = $this->file->store($directory, 'public');
        }
        
        $createdMessage = messages::create([
            'conversations_id' => $this->conversationId,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->recieverId,
            'body' => $this->file ? $this->file->getClientOriginalName() : $this->messageBody,
            'type' => $this->file ? 'file' : 'text',
            'file_path' => $filePath,
        ]);

        $this->loadedMessages->push($createdMessage);

        $this->conversation->updated_at = now();
        $this->conversation->save();
        $this->uploadProgress = 0;
        $this->dispatch('messageSent');
        $this->reset(['messageBody', 'file']); // Reset fields

        broadcast(new MessageSentEvent($createdMessage))->toOthers();
    }

    public function removeFile()
    {

        $this->file = null;
    }
    public function markAsread()
{
       messages::where('conversations_id',$this->conversationId)
                ->whereNull('read_at')->whereNull('receiver_id')
                ->update(['read_at'=>now()]);
}
    public function mount()
    {

        $sessionId = session()->getId(); // Retrieve the current session ID
        $this->conversation = conversations::where(function ($query) use ($sessionId) {
            $query->where('sender_session_id', $sessionId)
                ->orWhere('receiver_session_id', $sessionId); // Consider both roles
        })->first();
        if ($this->conversation) {
            $this->conversationId = $this->conversation->id;
            $this->recieverId = $this->conversation->receiver_id;
            $this->markAsread();
            $this->loadedMessages = messages::where('conversations_id', $this->conversationId)->get();
        }
    }
    public function render()
    {
        return view('chatsupport::livewire.visitor.chat')->layout('layouts.Visitor');
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
