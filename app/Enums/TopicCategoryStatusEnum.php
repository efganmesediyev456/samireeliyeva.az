<?php

namespace App\Enums;

enum TopicCategoryStatusEnum: int
{
    case EMPLOYMENT_CONTRACT = 1;
    case EDUCATION_LEGISLATION = 2;

    function toString(): string
    {
        return match ($this) {
            self::EMPLOYMENT_CONTRACT => 'Əmək müqaviləsi',
            self::EDUCATION_LEGISLATION => 'Təhsil qanunvericiliyi',
        };
    }

    function toColor(): string
    {
        return match ($this) {
            self::EMPLOYMENT_CONTRACT => 'warning',
            self::EDUCATION_LEGISLATION => 'info',
        };
    }
}