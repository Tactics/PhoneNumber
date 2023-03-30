<?php

declare(strict_types=1);

namespace Tactics\PhoneNumber;

use Tactics\PhoneNumber\Enum\PhoneNumberFormat;
use Tactics\PhoneNumber\Enum\PhoneNumberType;
use Tactics\PhoneNumber\Enum\PhoneStorageFormat;

interface PhoneNumber
{
    public function alpha2CountryCode(): string;

    public function alpha3CountryCode(): string;

    public function numericCountryCode(): string;

    public function format(PhoneNumberFormat $format);

    public function type(): PhoneNumberType;

    public function toStorage(PhoneStorageFormat $format);
}
