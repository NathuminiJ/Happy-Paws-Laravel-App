<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'subject',
        'message',
        'status',
        'admin_reply',
        'admin_id',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    /**
     * Get the customer who sent the message
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the admin who replied to the message
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Check if message is new
     */
    public function isNew()
    {
        return $this->status === 'new';
    }

    /**
     * Check if message is read
     */
    public function isRead()
    {
        return $this->status === 'read';
    }

    /**
     * Check if message is replied
     */
    public function isReplied()
    {
        return $this->status === 'replied';
    }

    /**
     * Check if message is closed
     */
    public function isClosed()
    {
        return $this->status === 'closed';
    }

    /**
     * Scope for new messages
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->whereIn('status', ['new', 'read']);
    }
}