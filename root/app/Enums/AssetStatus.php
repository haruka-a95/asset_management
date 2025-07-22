<?php

namespace App\Enums;

enum AssetStatus: string
{
    case UNUSED = '未使用';
    case IN_USE = '使用中';
    case BROKEN = '故障中';
    case DISPOSED = '廃棄済';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
