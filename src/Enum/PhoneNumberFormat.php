<?php

declare(strict_types=1);

namespace Tactics\PhoneNumber\Enum;

enum PhoneNumberFormat: string
{
    case E164 = 'E164';

    case INTERNATIONAL = 'INTERNATIONAL';

    case NATIONAL = 'NATIONAL';

    case RFC3966 = 'RFC3966';
}
