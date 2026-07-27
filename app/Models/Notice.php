<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'audience',
        'posted_by',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Only notices meant for this user's role.
     * Admins always see everything (they're the ones posting).
     */
    public function scopeVisibleTo($query, User $user)
    {
        if ($user->role === 'admin') {
            return $query;
        }

        $audience = $user->role === 'teacher' ? 'teachers' : 'students';

        return $query->whereIn('audience', ['all', $audience]);
    }
}
