<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $main_company = Company::create([
            'company_name' => 'CONATEK',
            'company_type_id' => 2,
            'identification_type_id' => 3,
            'identification_number' => 123,
            'province_id' => 2,
            'city_id' => 135,
            'address' => 'San Antonio de Prado',
            'industry_type_id' => 7,
            'size' => 60,
            'founded_at' => '2021-01-01',
            'is_active' => 1,
            'description' => 'Empresa de tecnología',
            // 'logo_public_id' => $this->faker->string,
            // 'logo_url' => $this->faker->url(),

            // 'contact_name' => $this->faker->name(),
            // 'contact_first_surname' => $this->faker->lastName(),
            // 'contact_second_surname' => $this->faker->lastName(),
            // 'website' => $this->faker->url(),
            // 'email' => $this->faker->unique()->companyEmail(),
            // 'phone' => $this->faker->phoneNumber(),
            // 'cellphone' => $this->faker->e164PhoneNumber(),

            // 'facebook' => 'https://www.facebook.com/' . $this->faker->userName,
            // 'linkedin' => 'https://www.linkedin.com/' . $this->faker->userName,
            // 'instagram' => 'https://www.instagram.com/' . $this->faker->userName,
            // 'x_twitter' => 'https://www.x.com/' . $this->faker->userName,
            // 'youtube' => 'https://www.youtube.com/' . $this->faker->userName,

            // 'created_at' => $this->faker->dateTime(),
            // 'updated_at' => $this->faker->dateTime(),
        ]);

        $guest_company = Company::create([
            'company_name' => 'MUY HUMANO',
            'company_type_id' => 2,
            'identification_type_id' => 3,
            'identification_number' => 123,
            'province_id' => 2,
            'city_id' => 135,
            'address' => 'San Antonio de Prado',
            'industry_type_id' => 7,
            'size' => 60,
            'founded_at' => '2021-01-01',
            'is_active' => 1,
            'description' => 'Empresa de tecnología',
            // 'logo_public_id' => $this->faker->string,
            // 'logo_url' => $this->faker->url(),

            // 'contact_name' => $this->faker->name(),
            // 'contact_first_surname' => $this->faker->lastName(),
            // 'contact_second_surname' => $this->faker->lastName(),
            // 'website' => $this->faker->url(),
            // 'email' => $this->faker->unique()->companyEmail(),
            // 'phone' => $this->faker->phoneNumber(),
            // 'cellphone' => $this->faker->e164PhoneNumber(),

            // 'facebook' => 'https://www.facebook.com/' . $this->faker->userName,
            // 'linkedin' => 'https://www.linkedin.com/' . $this->faker->userName,
            // 'instagram' => 'https://www.instagram.com/' . $this->faker->userName,
            // 'x_twitter' => 'https://www.x.com/' . $this->faker->userName,
            // 'youtube' => 'https://www.youtube.com/' . $this->faker->userName,

            // 'created_at' => $this->faker->dateTime(),
            // 'updated_at' => $this->faker->dateTime(),
        ]);

        Company::factory()
                ->count(48)
                ->create();
    }
}
