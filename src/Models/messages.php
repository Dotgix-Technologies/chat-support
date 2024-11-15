<?php

namespace Dotgix\Chatsupport\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class messages extends Model
{
    use HasFactory;
    protected $fillable=[
        'body',
        'type',
        'file_path',
        'sender_id',
        'receiver_id',
        'conversations_id',
        'read_at',
        'receiver_deleted_at',
        'sender_deleted_at',
    ];


    protected $dates=['read_at','receiver_deleted_at','sender_deleted_at'];


    /* relationship */

    public function conversation()
    {
        return $this->belongsTo(conversations::class);
    }
    public  function unreadMessagesCount(): int
    {


        return $unreadMessages = Messages::where('conversations_id', '=', $this->id)
            ->where('receiver_id', auth()->user()->id)
            ->whereNull('read_at')->count();
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
    public function isRead():bool
    {

         return $this->read_at != null;
    }
}
