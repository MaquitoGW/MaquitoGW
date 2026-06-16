<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ], [
            'name.required' => __('site.contact_validation.name_required'),
            'name.max' => __('site.contact_validation.name_max'),
            'email.required' => __('site.contact_validation.email_required'),
            'email.email' => __('site.contact_validation.email_invalid'),
            'email.max' => __('site.contact_validation.email_max'),
            'subject.required' => __('site.contact_validation.subject_required'),
            'subject.max' => __('site.contact_validation.subject_max'),
            'message.required' => __('site.contact_validation.message_required'),
            'message.min' => __('site.contact_validation.message_min'),
        ]);

        ContactMessage::create($validated);

        return redirect()
            ->back()
            ->with('contact_success', __('site.minimalist.contact_sent'));
    }
}
