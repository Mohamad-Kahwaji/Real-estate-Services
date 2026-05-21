<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpCode extends Model
{
    protected $fillable = ['phone', 'code', 'attempts', 'expires_at'];

    protected $casts = ['expires_at' => 'datetime'];

    // ── إنشاء OTP وإرساله عبر واتساب ──────────────────────────
    public static function sendTo(string $phone): bool
    {
        // احذف أي OTP قديم لنفس الرقم
        static::where('phone', $phone)->delete();

        $devMode = ! config('services.ultramsg.enabled', false);
        $code = $devMode ? '123456' : str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        static::create([
            'phone'      => $phone,
            'code'       => $code,
            'attempts'   => 0,
            'expires_at' => now()->addMinutes(10),
        ]);

        // إذا OTP_ENABLED=false → سجّل الكود في اللوغ فقط (للاختبار)
        if (! config('services.ultramsg.enabled', false)) {
            Log::info("[OTP] {$phone} → {$code}");
            return true;
        }

        // إرسال فعلي عبر UltraMsg (نفس كود الكورس)
        $instanceId = config('services.ultramsg.instance_id');
        $token      = config('services.ultramsg.token');

        // Convert local Syrian format (0xxxxxxxxx) to international (+963xxxxxxxxx)
        $to = str_starts_with($phone, '+') ? $phone : '+963' . ltrim($phone, '0');

        $response = Http::post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
            'token' => $token,
            'to'    => $to,
            'body'  => "🔐 رمز التحقق: *{$code}*\nصالح 10 دقائق.",
        ]);

        if (! $response->successful()) {
            Log::error("[OTP] UltraMsg failed for {$phone} → {$to}: " . $response->body());
        }

        return $response->successful();
    }

    // ── التحقق من الكود ────────────────────────────────────────
    // يرجع: 'ok' | 'invalid' | 'expired' | 'exhausted'
    public static function check(string $phone, string $code): string
    {
        $otp = static::where('phone', $phone)->latest()->first();

        if (! $otp) return 'invalid';

        if ($otp->expires_at->isPast()) {
            $otp->delete();
            return 'expired';
        }

        if ($otp->attempts >= 3) {
            $otp->delete();
            return 'exhausted';
        }

        if ($otp->code !== $code) {
            $otp->increment('attempts');
            if ($otp->fresh()->attempts >= 3) {
                $otp->delete();
                return 'exhausted';
            }
            return 'invalid';
        }

        $otp->delete();
        return 'ok';
    }
}
