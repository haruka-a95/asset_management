<?php

namespace Database\Factories;

use App\Models\LoanLog;
use App\Models\User;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanLog>
 */
class LoanLogFactory extends Factory
{
    protected $model = LoanLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // 貸出日時：過去1ヶ月以内
        $loanedAt = $this->faker->dateTimeBetween('-1 month', 'now');
        // 返却日時は貸出日時以降、またはnull（返却前）
        $returnedAt = $this->faker->optional()->dateTimeBetween($loanedAt, 'now');

        return [
            'user_id' => User::factory(),   // 関連ユーザーを自動生成
            'asset_id' => Asset::factory(), // 関連資産を自動生成
            'loaned_at' => $loanedAt,
            'returned_at' => $returnedAt,
        ];
    }
}
