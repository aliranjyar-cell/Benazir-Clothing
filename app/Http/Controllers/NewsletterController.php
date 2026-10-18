<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Handle a newsletter subscription from the storefront footer.
     *
     * No marketing emails are sent: the subscription is stored and
     * managed only. CSRF protection and rate limiting are applied
     * through the web middleware group and the route throttle.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:254'],
        ]);

        $result = NewsletterSubscriber::subscribe($data['email'], 'footer');

        if ($result['wasAlreadySubscribed']) {
            return back()
                ->with('success', "You're already subscribed to the Atelier Circle.");
        }

        return back()
            ->with('success', 'Thank you for joining the Atelier Circle.');
    }
}
