<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmissionReply extends Model
{
    protected $primaryKey = 'reply_id';

    protected $fillable = [
        'contact_submission_id',
        'admin_user_id',
        'admin_name',
        'admin_email',
        'reply_subject',
        'reply_message',
        'attachment_path',
        'attachment_original_name',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(ContactSubmission::class, 'contact_submission_id');
    }
}
