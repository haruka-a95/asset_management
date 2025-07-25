<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\AssetStatus;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'asset_number' => 'TEMP', // テストで指定する前提
            'acquisition_date' => $this->faker->date(),
            'price' => $this->faker->numberBetween(10000, 1000000),
            'location' => $this->faker->city(),
            'status' => $this->faker->randomElement(AssetStatus::cases()),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
