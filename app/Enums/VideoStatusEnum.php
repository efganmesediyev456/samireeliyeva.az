<?php

namespace App\Enums;

enum VideoStatusEnum: int
{
    case VIDEO_LESSONS = 1;
    case ESSAY_SAMPLES = 2;
    case CRITICAL_READING = 3;

    public function toString(): string
    {
        return match ($this) {
            self::VIDEO_LESSONS => 'Video dərslər',
            self::ESSAY_SAMPLES => 'Esse nümunələri',
            self::CRITICAL_READING => 'Tənqidi oxu',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::VIDEO_LESSONS => 'warning',
            self::ESSAY_SAMPLES => 'info',
            self::CRITICAL_READING => 'success',
        };
    }
}
