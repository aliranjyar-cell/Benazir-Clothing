<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_subscription_stores_a_subscriber(): void
    {
        $this->from('/')->post(route('store.newsletter.subscribe'), [
            'email' => 'amina@example.com',
        ])->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'amina@example.com',
            'status' => 'subscribed',
        ]);
    }

    public function test_duplicate_subscription_is_handled_gracefully(): void
    {
        NewsletterSubscriber::create([
            'email' => 'amina@example.com',
            'status' => 'subscribed',
            'subscribed_at' => now(),
            'source' => 'footer',
        ]);

        $this->from('/')->post(route('store.newsletter.subscribe'), [
            'email' => 'AMINA@example.com',
        ])->assertRedirect()
            ->assertSessionHas('success');

        $this->assertCount(1, NewsletterSubscriber::where('email', 'amina@example.com')->get());
    }

    public function test_unsubscribed_email_can_resubscribe(): void
    {
        NewsletterSubscriber::create([
            'email' => 'amina@example.com',
            'status' => 'unsubscribed',
            'subscribed_at' => now()->subDays(5),
            'source' => 'footer',
        ]);

        $this->from('/')->post(route('store.newsletter.subscribe'), [
            'email' => 'amina@example.com',
        ])->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'amina@example.com',
            'status' => 'subscribed',
        ]);
    }

    public function test_invalid_email_is_rejected(): void
    {
        $this->from('/')->post(route('store.newsletter.subscribe'), [
            'email' => 'not-an-email',
        ])->assertRedirect()
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('newsletter_subscribers', [
            'email' => 'not-an-email',
        ]);
    }
}
