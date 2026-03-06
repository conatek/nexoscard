<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Collaborator>
 */
class CollaboratorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $provinces = Province::all()->modelKeys();
        $cities = City::all()->modelKeys();


        return [
            'company_id' => $this->faker->randomElement([1, 2]),
            'document_type_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6]),
            'document_number' => $this->faker->numberBetween(10000000, 99999999),
            'expedition_date' => $this->faker->dateTime(),
            'document_province_id' => $this->faker->randomElement($provinces),
            'document_city_id' => $this->faker->randomElement($cities),
            'name' => $this->faker->name(),
            'first_surname' => $this->faker->lastName(),
            'second_surname' => $this->faker->lastName(),
            'civil_status_type_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6]),
            'sex_type_id' => $this->faker->randomElement([1, 2]),
            'rh_type_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8]),
            // 'academic_achievement_type_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]),
            'birth_date' => $this->faker->dateTime(),
            'birth_province_id' => $this->faker->randomElement($provinces),
            'birth_city_id' => $this->faker->randomElement($cities),
            'residence_province_id' => $this->faker->randomElement($provinces),
            'residence_city_id' => $this->faker->randomElement($cities),
            'address' => $this->faker->address(),
            'housing_tenure_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7]),
            'stratum_type_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6]),
            'phone' => $this->faker->phoneNumber(),
            'cellphone' => $this->faker->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            // 'image_public_id' => $this->faker->string,
            // 'image_url' => $this->faker->url(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
