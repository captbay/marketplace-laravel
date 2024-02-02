<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversations extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id'];

    public function messages()
    {
        return $this->belongsTo(Messages::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class);
    }
}
