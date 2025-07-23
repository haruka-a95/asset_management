<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AssetStatus;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'asset_number',
        'acquisition_date',
        'price',
        'location',
        'status',
        'user_id',
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $casts = [
        'status' => AssetStatus::class,
    ];
}
