<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password_hash',
        'role',
        'failed_login_attempts',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'failed_login_attempts' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Override default password field
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relationships
    public function savedEvents()
    {
        return $this->belongsToMany(Event::class, 'saved_events')->withTimestamps();
    }

    public function eventRequests()
    {
        return $this->hasMany(EventRequest::class, 'requested_by');
    }

    // Methods
    public function saveEvent(Event $event)
    {
        return $this->savedEvents()->attach($event->id);
    }

    public function unsaveEvent(Event $event)
    {
        return $this->savedEvents()->detach($event->id);
    }

    public function hasSavedEvent(Event $event)
    {
        return $this->savedEvents()->where('event_id', $event->id)->exists();
    }

    public function incrementFailedAttempts()
    {
        $this->increment('failed_login_attempts');
    }

    public function resetFailedAttempts()
    {
        $this->update(['failed_login_attempts' => 0]);
    }
}