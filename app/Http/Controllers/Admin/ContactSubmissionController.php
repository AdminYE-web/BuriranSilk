<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\ContactSubmissionReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactSubmission::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('contact_method')) {
            $query->where('contact_method', $request->string('contact_method')->toString());
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->string('subject')->toString());
        }

        $submissions = $query->paginate(20)->withQueryString();

        return view('admin.contact_submissions.index', compact('submissions'));
    }

    public function show(ContactSubmission $contactSubmission)
    {
        $contactSubmission->load('replies');

        return view('admin.contact_submissions.show', compact('contactSubmission'));
    }

    public function reply(ContactSubmission $submission)
    {
        $submission->load('replies');

        return view('admin.contact_submissions.reply', compact('submission'));
    }

    public function sendReply(Request $request, ContactSubmission $submission)
    {
        $request->validate([
            'reply_subject' => 'nullable|string|max:255',
            'reply_message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip|max:10240',
        ]);

        $attachmentPath = null;
        $attachmentOriginalName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            $attachmentPath = $file->store('contact-replies', 'public');
            $attachmentOriginalName = $file->getClientOriginalName();
        }

        $admin = Auth::guard('admin')->user();

        $reply = ContactSubmissionReply::create([
            'contact_submission_id' => $submission->id,
            'admin_user_id' => $admin->id ?? null,
            'admin_name' => $admin->name ?? session('admin_name'),
            'admin_email' => $admin->email ?? session('admin_email'),
            'reply_subject' => $request->reply_subject ?: 'Reply to your contact submission',
            'reply_message' => $request->reply_message,
            'attachment_path' => $attachmentPath,
            'attachment_original_name' => $attachmentOriginalName,
            'sent_at' => now(),
        ]);
        $submission->update([
            'status_reply' => 'reply',
        ]);

        /*
    |--------------------------------------------------------------------------
    | Optional: ส่งอีเมลจริง
    |--------------------------------------------------------------------------
    | ถ้ายังไม่ได้ setup Mail ให้ comment ส่วนนี้ไว้ก่อน
    |--------------------------------------------------------------------------
    */
        Mail::raw($request->reply_message, function ($message) use ($submission, $request, $attachmentPath, $attachmentOriginalName) {
            $message->to($submission->email)
                ->subject($request->reply_subject ?: 'Reply to your contact submission');

            if ($attachmentPath) {
                $message->attach(storage_path('app/public/'.$attachmentPath), [
                    'as' => $attachmentOriginalName,
                ]);
            }
        });

        return redirect()
            ->route('admin.contact-submissions.show', $submission)
            ->with('success', 'Reply has been saved successfully.');
    }
}
