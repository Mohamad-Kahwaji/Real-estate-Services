<?php

namespace App\Services;

use App\Models\DeviceToken;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AdminPushNotificationService
{
    public function send(string $title, string $body, array $data = []): void
    {
        $tokens = DeviceToken::where(function ($query) {
                $query->whereNotNull('admin_id')
                      ->orWhereNotNull('super_admin_id');
            })
            ->pluck('token')
            ->unique()
            ->values();

        if ($tokens->isEmpty()) {
            return;
        }

        $messaging = Firebase::messaging();

        foreach ($tokens as $token) {
            $message = CloudMessage::new()->withToken($token)
                ->withNotification(Notification::create($title, $body))
                ->withData(array_map('strval', $data));

            try {
                $messaging->send($message);
            } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                DeviceToken::where('token', $token)->delete();
            } catch (\Throwable $e) {
                // skip other transient errors silently
            }
        }
    }
}
