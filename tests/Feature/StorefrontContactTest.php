<?php

namespace Tests\Feature;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class StorefrontContactTest extends TestCase
{
    public function test_contact_form_sends_a_message_to_the_configured_store_email(): void
    {
        Event::fake([MessageSent::class]);
        config()->set('mail.default', 'array');
        config()->set('storefront.notifications.admin_email', 'care@example.com');

        $this->from('/contact')->post('/contact', [
            'first_name' => 'Amina',
            'last_name' => 'Khan',
            'email' => 'amina@example.com',
            'phone' => '+92 300 1234567',
            'subject' => 'Order enquiry',
            'message' => 'I would like an update about my order.',
        ])->assertRedirect('/contact')
            ->assertSessionHas('success');

        Event::assertDispatched(MessageSent::class, function (MessageSent $event): bool {
            $recipients = $event->message->getTo();

            return count($recipients) === 1 && $recipients[0]->getAddress() === 'care@example.com';
        });
    }

    public function test_contact_form_rejects_invalid_submissions(): void
    {
        $this->from('/contact')->post('/contact', [
            'first_name' => '',
            'email' => 'not-an-email',
            'subject' => 'Not an available option',
            'message' => 'Short',
        ])->assertRedirect('/contact')
            ->assertSessionHasErrors(['first_name', 'email', 'subject', 'message']);
    }
}
