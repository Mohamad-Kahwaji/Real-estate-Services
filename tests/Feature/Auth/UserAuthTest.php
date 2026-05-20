<?php

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

/**
 * Create an active user with a known phone + password.
 */
function makeUser(array $overrides = []): User
{
    return User::create(array_merge([
        'name'      => 'Test User',
        'phone'     => '0501234567',
        'password'  => Hash::make('password'),
        'is_active' => true,
    ], $overrides));
}

/**
 * Seed an unexpired OTP row for a given phone.
 */
function seedOtp(string $phone, string $code = '123456'): OtpCode
{
    return OtpCode::create([
        'phone'      => $phone,
        'code'       => $code,
        'attempts'   => 0,
        'expires_at' => now()->addMinutes(10),
    ]);
}

// ---------------------------------------------------------------------------
// GET /login
// ---------------------------------------------------------------------------

describe('GET /login', function () {

    it('returns 200 for guests', function () {
        $this->get('/login')
             ->assertStatus(200);
    });

});

// ---------------------------------------------------------------------------
// POST /login  (credentials check + OTP dispatch)
// ---------------------------------------------------------------------------

describe('POST /login', function () {

    it('with valid credentials stores otp_phone in session and redirects to otp page', function () {
        $user = makeUser();

        config(['services.ultramsg.enabled' => false]);

        $this->post('/login', [
                 'phone'    => $user->phone,
                 'password' => 'password',
             ])
             ->assertRedirect(route('otp.show'))
             ->assertSessionHas('otp_phone', $user->phone);
    });

    it('with wrong password returns back with phone error', function () {
        makeUser();

        $this->post('/login', [
                 'phone'    => '0501234567',
                 'password' => 'wrong-password',
             ])
             ->assertRedirect()   // redirects back
             ->assertSessionHasErrors('phone');
    });

    it('with a non-existent phone returns back with phone error', function () {
        $this->post('/login', [
                 'phone'    => '0999999999',
                 'password' => 'password',
             ])
             ->assertRedirect()
             ->assertSessionHasErrors('phone');
    });

    it('for an inactive user returns back with phone error after logging out', function () {
        $user = makeUser(['is_active' => false]);

        $this->post('/login', [
                 'phone'    => $user->phone,
                 'password' => 'password',
             ])
             ->assertRedirect()
             ->assertSessionHasErrors('phone');
    });

    it('returns 422 when phone field is missing', function () {
        $this->post('/login', ['password' => 'password'])
             ->assertSessionHasErrors('phone');
    });

    it('returns 422 when password field is missing', function () {
        $this->post('/login', ['phone' => '0501234567'])
             ->assertSessionHasErrors('password');
    });

});

// ---------------------------------------------------------------------------
// GET /otp  (OTP verification page)
// ---------------------------------------------------------------------------

describe('GET /otp', function () {

    it('returns 200 when otp_phone is set in session', function () {
        $this->withSession(['otp_phone' => '0501234567'])
             ->get(route('otp.show'))
             ->assertStatus(200);
    });

    it('redirects to login when otp_phone is absent from session', function () {
        $this->get(route('otp.show'))
             ->assertRedirect(route('login'));
    });

});

// ---------------------------------------------------------------------------
// POST /otp/verify  (OTP code check → full login)
// ---------------------------------------------------------------------------

describe('POST /otp/verify', function () {

    it('with correct code logs in the user and redirects to dashi', function () {
        $user = makeUser(['phone' => '0501112233']);
        seedOtp('0501112233', '654321');

        $this->withSession(['otp_phone' => '0501112233'])
             ->post(route('otp.verify'), ['code' => '654321'])
             ->assertRedirect(route('dashi'));

        $this->assertAuthenticatedAs($user, 'users');
    });

    it('with wrong code returns back with code error', function () {
        $user = makeUser(['phone' => '0502223344']);
        seedOtp('0502223344', '111111');

        $this->withSession(['otp_phone' => '0502223344'])
             ->post(route('otp.verify'), ['code' => '999999'])
             ->assertRedirect()
             ->assertSessionHasErrors('code');
    });

    it('with expired OTP redirects to login with phone error', function () {
        $user = makeUser(['phone' => '0503334455']);
        OtpCode::create([
            'phone'      => '0503334455',
            'code'       => '777777',
            'attempts'   => 0,
            'expires_at' => now()->subMinutes(1),   // already expired
        ]);

        $this->withSession(['otp_phone' => '0503334455'])
             ->post(route('otp.verify'), ['code' => '777777'])
             ->assertRedirect(route('login'))
             ->assertSessionHasErrors('phone');
    });

    it('after three wrong attempts redirects to login with phone error', function () {
        $user = makeUser(['phone' => '0504445566']);
        OtpCode::create([
            'phone'      => '0504445566',
            'code'       => '222222',
            'attempts'   => 3,                      // already exhausted
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->withSession(['otp_phone' => '0504445566'])
             ->post(route('otp.verify'), ['code' => '000000'])
             ->assertRedirect(route('login'))
             ->assertSessionHasErrors('phone');
    });

    it('redirects to login when otp_phone session key is absent', function () {
        $this->post(route('otp.verify'), ['code' => '123456'])
             ->assertRedirect(route('login'));
    });

    it('returns 422 when code field is missing', function () {
        $this->withSession(['otp_phone' => '0501234567'])
             ->post(route('otp.verify'), [])
             ->assertSessionHasErrors('code');
    });

});

// ---------------------------------------------------------------------------
// POST /logout  (web user logout — defined inline in web.php)
// ---------------------------------------------------------------------------

describe('POST /logout', function () {

    it('logs out an authenticated user and redirects to login', function () {
        $user = makeUser();

        $this->actingAs($user, 'users')
             ->post(route('logout'))
             ->assertRedirect(route('login'));

        $this->assertGuest('users');
    });

    it('works even when no user is logged in', function () {
        $this->post(route('logout'))
             ->assertRedirect(route('login'));
    });

});
