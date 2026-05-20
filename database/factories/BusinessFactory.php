<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_name_ar'    => $this->faker->word() . '_ar',
            'job_name_en'    => $this->faker->word() . '_en',
            'license_number' => $this->faker->numerify('LIC-####'),
            'activites'      => $this->faker->sentence(),
            'details'        => $this->faker->paragraph(),
            'status'         => 'approved',
            'user_id'        => \App\Models\User::factory(),
        ];
    }
}
