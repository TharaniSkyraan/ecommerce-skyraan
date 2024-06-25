<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_name' => 'SkyraaEcommerce',
            'theme_primary_color' => '#8BC34A',
            'theme_secondary_color' => '#4CAF50',
            'theme_tertiary_color' => '#F9FCF6',
            'phone' => '9876543210',
        ];
    }
}
