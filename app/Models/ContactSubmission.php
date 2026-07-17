<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contact_method',
        'subject',
        'name',
        'email',
        'line_id',
        'country_code',
        'phone',
        'message',
        'attachment_path',
        'attachment_original_name',
        'ip_address',
        'status_reply',
    ];

    public function replies()
    {
        return $this->hasMany(ContactSubmissionReply::class, 'contact_submission_id');
    }
}
