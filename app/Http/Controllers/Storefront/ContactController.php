<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['nullable', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => [
                'required',
                'string',
                Rule::in([
                    'Order enquiry',
                    'Product information',
                    'Sizing and fitting',
                    'Bespoke and custom orders',
                    'Shipping and returns',
                    'Other',
                ]),
            ],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $recipient = config('storefront.notifications.admin_email');

        if (! is_string($recipient) || ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return back()
                ->withInput()
                ->withErrors(['contact' => 'Contact messages are temporarily unavailable. Please email us directly.']);
        }

        $senderName = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));
        $body = implode("\n", [
            'Name: ' . $senderName,
            'Email: ' . $data['email'],
            'Phone: ' . ($data['phone'] ?: 'Not provided'),
            'Subject: ' . $data['subject'],
            '',
            'Message:',
            $data['message'],
        ]);

        Mail::raw($body, function ($mail) use ($recipient, $data, $senderName): void {
            $mail->to($recipient)
                ->replyTo($data['email'], $senderName)
                ->subject('Contact enquiry: ' . $data['subject']);
        });

        return back()->with('success', 'Thank you for your message. Our team will be in touch soon.');
    }
}
