<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'status',
        'source',
        'subscribed_at',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
    ];

    public const STATUS_SUBSCRIBED = 'subscribed';
    public const STATUS_UNSUBSCRIBED = 'unsubscribed';

    /**
     * Only subscribers who are currently subscribed.
     */
    public function scopeSubscribed($query)
    {
        return $query->where('status', self::STATUS_SUBSCRIBED);
    }

    /**
     * Only subscribers who have unsubscribed.
     */
    public function scopeUnsubscribed($query)
    {
        return $query->where('status', self::STATUS_UNSUBSCRIBED);
    }

    /**
     * Subscribe (or re-subscribe) an email address.
     *
     * Duplicate emails are handled gracefully: an already-subscribed
     * address is left untouched, while an unsubscribed address is
     * re-subscribed instead of creating a duplicate row.
     *
     * @return array{subscriber: static, wasAlreadySubscribed: bool}
     */
    public static function subscribe(string $email, string $source = 'footer'): array
    {
        $email = strtolower(trim($email));
        $now = now();

        $subscriber = static::query()->where('email', $email)->first();

        if ($subscriber !== null) {
            $wasAlreadySubscribed = $subscriber->status === self::STATUS_SUBSCRIBED;

            if (! $wasAlreadySubscribed) {
                $subscriber->update([
                    'status' => self::STATUS_SUBSCRIBED,
                    'subscribed_at' => $now,
                    'source' => $source,
                ]);
            }

            return [
                'subscriber' => $subscriber,
                'wasAlreadySubscribed' => $wasAlreadySubscribed,
            ];
        }

        $subscriber = static::create([
            'email' => $email,
            'status' => self::STATUS_SUBSCRIBED,
            'source' => $source,
            'subscribed_at' => $now,
        ]);

        return [
            'subscriber' => $subscriber,
            'wasAlreadySubscribed' => false,
        ];
    }
}
