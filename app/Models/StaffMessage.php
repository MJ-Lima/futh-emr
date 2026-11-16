<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'sender_id', 'recipient_id', 'parent_id',
        'subject', 'body', 'priority', 'status',
        'read_at', 'archived_at', 'attachments',
    ];

    protected $casts = [
        'attachments'  => 'array',
        'read_at'      => 'datetime',
        'archived_at'  => 'datetime',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(StaffMessage::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(StaffMessage::class, 'parent_id');
    }

    public function markRead(): void
    {
        if (! $this->read_at) {
            $this->update(['read_at' => now(), 'status' => 'read']);
        }
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeInbox($query, int $userId)
    {
        return $query->where('recipient_id', $userId)->whereNull('archived_at');
    }

    public function scopeSent($query, int $userId)
    {
        return $query->where('sender_id', $userId);
    }
}
