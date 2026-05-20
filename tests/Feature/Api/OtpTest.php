<?php

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function makeActiveUser(string $phone = '0912345678'): User
{
    return User::create([
        'name'      => 'Test',
        'phone'     => $phone,
        'password'  => Hash::make('password'),
        'is_active' => true,
    ]);
}

function makeOtp(string $phone, string $code, bool $expired = false): OtpCode
{
    return OtpCode::create([
        'phone'      => $phone,
        'code'       => $code,
        'attempts'   => 0,
        'expires_at' => $expired ? now()->subMinutes(1) : now()->addMinutes(10),
    ]);
}

// ── POST /api/verify-otp ──────────────────────────────────────────────────────

describe('POST /api/verify-otp', function () {
    it('returns 200 with token when code is correct', function () {
        $user = makeActiveUser();
        makeOtp($user->phone, '123456');

        $this->postJson('/api/verify-otp', ['phone' => $user->phone, 'code' => '123456'])
            ->assertStatus(200)
            ->assertJsonStructure(['status', 'token', 'user'])
            ->assertJson(['status' => true]);
    });

    it('returns 422 with remaining_attempts when code is wrong', function () {
        $user = makeActiveUser();
        makeOtp($user->phone, '999999');

        $this->postJson('/api/verify-otp', ['phone' => $user->phone, 'code' => '000000'])
            ->assertStatus(422)
            ->assertJsonStructure(['status', 'message', 'remaining_attempts'])
            ->assertJson(['status' => false]);
    });

    it('returns 422 when OTP is expired', function () {
        $user = makeActiveUser();
        makeOtp($user->phone, '123456', expired: true);

        $this->postJson('/api/verify-otp', ['phone' => $user->phone, 'code' => '123456'])
            ->assertStatus(422)
            ->assertJson(['status' => false]);
    });

    it('returns 403 when user account is suspended', function () {
        $user = makeActiveUser('0900000001');
        $user->update(['is_active' => false]);
        makeOtp($user->phone, '123456');

        $this->postJson('/api/verify-otp', ['phone' => $user->phone, 'code' => '123456'])
            ->assertStatus(403)
            ->assertJson(['status' => false]);
    });

    it('returns 422 when phone or code is missing', function () {
        $this->postJson('/api/verify-otp', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['phone', 'code']);
    });
});

// ── POST /api/resend-otp ──────────────────────────────────────────────────────

describe('POST /api/resend-otp', function () {
    it('returns 200 for a registered phone number', function () {
        $user = makeActiveUser('0911111111');

        // Disable actual SMS sending
        config(['services.ultramsg.enabled' => false]);

        $this->postJson('/api/resend-otp', ['phone' => $user->phone])
            ->assertStatus(200)
            ->assertJson(['status' => true]);
    });

    it('returns 404 for an unregistered phone number', function () {
        $this->postJson('/api/resend-otp', ['phone' => '0000000000'])
            ->assertStatus(404)
            ->assertJson(['status' => false]);
    });
});
