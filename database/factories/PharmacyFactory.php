<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pharmacy>
 */
class PharmacyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $pharmacyNames = [
        "Walmart Pharmacy",
        "Target Pharmacy",
        "Kroger Pharmacy",
        "Safeway Pharmacy",
        "Costco Pharmacy",
        "Publix Pharmacy",
        "H-E-B Pharmacy",
        "Albertsons Pharmacy",
        "WinCo Foods Pharmacy",
        "Kaiser Permanente Pharmacy",
        "Medicine Shoppe Pharmacy",
        "Omnicare Pharmacy",
        "PharMerica Pharmacy",
        "Village Pharmacy",
        "Express Scripts Pharmacy",
        "Humana Pharmacy",
        "OptumRx Pharmacy",
        "UnitedHealthcare Pharmacy",
        "Caremark Pharmacy"
    ];
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement($this->pharmacyNames),
            'licence' => $this->faker->randomNumber(9),
            'chest' => '0',
            'safe' => '0',
        ];
    }
}
