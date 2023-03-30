<?php

namespace Tactics\PhoneNumber\Exception;

use LogicException;

class InvalidPhoneNumber extends LogicException
{
    public const NOT_A_PHONE_NUMBER = 1;

    public const NOT_IN_INTERNATIONAL_PHONE_NUMBER_FORMAT = 2;

    public const NOT_IN_NATIONAL_PHONE_NUMBER_FORMAT = 3;

    public const NO_COUNTRY_CODE = 4;

    public const INVALID_COUNTRY_CODE = 5;

    public static function notAPhoneNumber(): self
    {
        return new self(
            'The provided string is not a valid phone number',
            self::NOT_A_PHONE_NUMBER
        );
    }

    public static function notInInternationalPhoneNumberFormat(): self
    {
        return new self(
            'The provided string is not in a valid international phone number format',
            self::NOT_IN_INTERNATIONAL_PHONE_NUMBER_FORMAT
        );
    }

    public static function notInNationalPhoneNumberFormat(): self
    {
        return new self(
            'The provided string is not in a valid national phone number format',
            self::NOT_IN_NATIONAL_PHONE_NUMBER_FORMAT
        );
    }

    public static function noCountryCode(): self
    {
        return new self(
            'The phone number is not linked to a country and therefor not a valid international phone number',
            self::NO_COUNTRY_CODE
        );
    }

    public static function invalidCountryCode(string $code): self
    {
        return new self(
            sprintf('The alpha2 country code "%s" is invalid and therefor the provided number is invalid', $code),
            self::INVALID_COUNTRY_CODE
        );
    }
}
