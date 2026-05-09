<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FcmController extends Controller
{

    protected $messaging;
    public function __construct()
    {
        $this->messaging = Firebase::messaging();
    }

  public function storeToken(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

          DB::table('device_tokens')->updateOrInsert(
            [
                'user_id' => auth('users')->id(),
            ],
            [
                'token' => $request->token,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Token saved successfully',
        ]);
    }
    public function send()
    {

        $tokens = DeviceToken::pluck('token')->toArray();

        $this->sendToMany(
            $tokens,
            'Order Update',
            'Your order has been shipped'
        );

        return response()->json("test");
    }

    public function sendToToken($token, $title, $body, $data = [])
    {

        $message = CloudMessage::new()->toToken($token)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);


        return $this->messaging->send($message);
    }

    public function sendToMany(array $tokens, $title, $body)
    {
        // dd($tokens);
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body));

        $response = $this->messaging->sendMulticast($message, $tokens);

        $invalidTokens = $response->invalidTokens();      // malformed
        $unknownTokens = $response->unknownTokens();      // NotRegistered

        // دمجهم مع بعض
        $tokensToDelete = array_merge($invalidTokens, $unknownTokens);

        if (!empty($tokensToDelete)) {
            DeviceToken::whereIn('token', $tokensToDelete)->delete();
        }
        return true;

    }

    public function sendToTopic($topic, $title, $body)
    {
        $message = CloudMessage::new()->toTopic($topic)
            ->withNotification(Notification::create($title, $body));

        return $this->messaging->send($message);
    }





    ////
    public function storeAdminToken(Request $request)
{
    $request->validate([
        'token' => 'required|string',
    ]);

    $adminId = null;
    $superAdminId = null;

    if (auth('admins')->check()) {
        $adminId = auth('admins')->id();
    } elseif (auth('superadmins')->check()) {
        $superAdminId = auth('superadmins')->id();
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Admin not authenticated',
        ], 401);
    }

    DeviceToken::updateOrCreate(
        ['token' => $request->token],
        [
            'admin_id' => $adminId,
            'super_admin_id' => $superAdminId,
            'platform' => 'web',
        ]
    );

    return response()->json([
        'status' => true,
        'message' => 'FCM token saved successfully',
    ]);
}
}
