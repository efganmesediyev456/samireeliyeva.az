<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case PENDING = 1;
    case PREPARE = 2;
    case COURIER = 3 ;
    case DELIVERED = 4 ;
    case CANCELED = 5 ;

    function toString(): string
    {
        return match ($this) {
            self::PENDING => 'Sifariş verildi',
            self::PREPARE => 'Sifariş hazırlanır',
            self::COURIER => 'Kuryerə verildi',
            self::DELIVERED => 'Çatdırıldı',
            self::CANCELED => 'Ləğv edildi',
        };
    }

    function toColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PREPARE => 'info',
            self::COURIER => 'success',
            self::DELIVERED => 'success',
            self::CANCELED => 'danger',
        };
    }
}
