<?php

namespace Tests\Feature;

use App\Enums\AssetStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\LoanLog;
use App\Models\User;
use App\Models\Asset;

/*貸出記録の作成ができるか

返却日時の更新ができるか

貸出中の状態判定ができるか（返却されていない＝returned_atがnull）*/

class LoanLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_loan_log() :void
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $loanLog = LoanLog::factory()->create([
            'user_id' => $user->id,
            'asset_id' => $asset->id,
            'loaned_at' => now()->subDays(5),
            'returned_at' => null,
        ]);

        $this->assertDatabaseHas('loan_logs', [
            'id' => $loanLog->id,
            'returned_at' => null,
        ]);
    }

    public function test_can_update_returned_at():void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $loanLog = LoanLog::factory()->create([
            'returned_at' => null,
        ]);

        $loanLog->returned_at = now();
        $loanLog->save();

        $this->assertNotNull($loanLog->returned_at);
        $this->assertDatabaseHas('loan_logs', [
            'id' => $loanLog->id,
            'returned_at' => $loanLog->returned_at,
        ]);
    }

}
