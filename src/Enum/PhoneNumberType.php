<?php

declare(strict_types=1);

namespace Tactics\PhoneNumber\Enum;

enum PhoneNumberType: string
{
    case MOBILE = 'MOBILE';

    case FIXED_LINE = 'FIXED_LINE';

    case UNKNOWN = 'UNKNOWN';
}
