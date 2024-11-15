<?php

namespace Dotgix\Chatsupport\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conversations extends Model
{
    use HasFactory;
    protected $fillable = [
        'receiver_id',
        'sender_id',
        'sender_session_id',
        'sender_deleted_at',
        'sender_session_id',
        'receiver_session_id',

    ];


    public function messages()
    {
        return $this->hasMany(messages::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Define the relationship to the sender (User)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function getReceiver()
    {

        if ($this->sender_id === auth()->id()) {

            return User::firstWhere('id', $this->receiver_id);
        } else {

            return User::firstWhere('id', $this->sender_id);
        }
    }



    public function scopeWhereNotDeleted($query)
    {
        $userId = auth()->id();

        return $query->where(function ($query) use ($userId) {

            #where message is not deleted
            $query->whereHas('messages', function ($query) use ($userId) {

                $query->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->whereNull('sender_deleted_at');
                })->orWhere(function ($query) use ($userId) {

                    $query->where('receiver_id', $userId)
                        ->whereNull('receiver_deleted_at');
                });
            })
                #include conversations without messages
                ->orWhereDoesntHave('messages');
        });
    }



    public  function isLastMessageReadByUser(): bool
    {


        $user = Auth()->User();
        $lastMessage = $this->messages()->latest()->first();

        if ($lastMessage) {
            return  $lastMessage->read_at !== null && $lastMessage->sender_id == $user->id;
        }
    }


    public  function visitorunreadMessagesCount(): int
    {


        return $unreadMessages = messages::where('conversations_id', '=', $this->id)
            ->where('receiver_id', auth()->user()->id??null)
            ->whereNull('read_at')->count();
    }

    public  function unreadMessagesCount(): int
    {


        return $unreadMessages = messages::where('conversations_id', '=', $this->id)
            ->where('receiver_id', auth()->user()->id)
            ->whereNull('read_at')->count();
    }
}
