<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    private string $vapidPublicKey;
    private string $vapidPrivateKey;

    public function __construct()
    {
        $this->vapidPublicKey = config('services.vapid.public_key');
        $this->vapidPrivateKey = config('services.vapid.private_key');
    }

    public function sendNotification($subscription, $payload)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->vapidPrivateKey,
                'Content-Type' => 'application/json',
            ])->post($subscription->endpoint, [
                'title' => $payload['title'] ?? 'Dental Store',
                'body' => $payload['body'] ?? 'New notification',
                'icon' => $payload['icon'] ?? '/icon-192.png',
                'badge' => $payload['badge'] ?? '/icon-192.png',
                'data' => $payload['data'] ?? [],
                'actions' => $payload['actions'] ?? [],
                'vibrate' => $payload['vibrate'] ?? [100, 50, 100],
                'tag' => $payload['tag'] ?? 'default',
                'requireInteraction' => $payload['requireInteraction'] ?? false,
                'silent' => $payload['silent'] ?? false,
            ]);

            if ($response->successful()) {
                Log::info('Push notification sent successfully', [
                    'subscription_id' => $subscription->id,
                    'payload' => $payload
                ]);
                return true;
            } else {
                Log::error('Failed to send push notification', [
                    'subscription_id' => $subscription->id,
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Push notification error', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendToAll($payload)
    {
        // Get all active subscriptions
        $subscriptions = \App\Models\PushSubscription::where('active', true)->get();
        
        $successCount = 0;
        foreach ($subscriptions as $subscription) {
            if ($this->sendNotification($subscription, $payload)) {
                $successCount++;
            }
        }

        Log::info('Bulk push notification sent', [
            'total' => $subscriptions->count(),
            'successful' => $successCount
        ]);

        return $successCount;
    }

    public function sendToCategory($categoryId, $payload)
    {
        // Send notifications to users interested in specific category
        $subscriptions = \App\Models\PushSubscription::whereHas('user.preferences', function($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })->where('active', true)->get();

        $successCount = 0;
        foreach ($subscriptions as $subscription) {
            if ($this->sendNotification($subscription, $payload)) {
                $successCount++;
            }
        }

        return $successCount;
    }

    public function sendProductNotification($product, $type = 'new')
    {
        $payload = [
            'title' => 'New Product Available',
            'body' => "Check out our new {$product->name}",
            'data' => [
                'product_id' => $product->id,
                'product_slug' => $product->slug,
                'type' => $type
            ],
            'actions' => [
                [
                    'action' => 'view',
                    'title' => 'View Product'
                ],
                [
                    'action' => 'dismiss',
                    'title' => 'Dismiss'
                ]
            ]
        ];

        return $this->sendToAll($payload);
    }

    public function sendPriceDropNotification($product, $oldPrice, $newPrice)
    {
        $discount = round((($oldPrice - $newPrice) / $oldPrice) * 100);
        
        $payload = [
            'title' => 'Price Drop Alert!',
            'body' => "{$product->name} is now {$discount}% off!",
            'data' => [
                'product_id' => $product->id,
                'product_slug' => $product->slug,
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'discount' => $discount
            ],
            'actions' => [
                [
                    'action' => 'buy',
                    'title' => 'Buy Now'
                ],
                [
                    'action' => 'dismiss',
                    'title' => 'Dismiss'
                ]
            ]
        ];

        return $this->sendToAll($payload);
    }

    public function getVapidPublicKey(): string
    {
        return $this->vapidPublicKey;
    }
} 