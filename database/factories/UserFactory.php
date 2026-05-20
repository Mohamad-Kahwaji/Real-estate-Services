<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 0;
        $counter++;

        return [
            'name'      => fake()->name(),
            'phone'     => '09' . str_pad($counter, 8, '0', STR_PAD_LEFT),
            'password'  => static::$password ??= Hash::make('password'),
            'is_active' => true,
        ];
    }
}
