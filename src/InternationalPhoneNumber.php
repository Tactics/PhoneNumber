<?php

declare(strict_types=1);

namespace Tactics\PhoneNumber;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber as LibPhoneNumber;
use libphonenumber\PhoneNumberFormat as LibPhoneNumberFormat;
use libphonenumber\PhoneNumberType as LibPhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use Tactics\ISO3166\CountryCode;
use Tactics\ISO3166\Enum\ISO3166_1\Alpha2;
use Tactics\PhoneNumber\Enum\PhoneNumberFormat;
use Tactics\PhoneNumber\Enum\PhoneNumberType;
use Tactics\PhoneNumber\Enum\PhoneStorageFormat;
use Tactics\PhoneNumber\Exception\InvalidPhoneNumber;

final class InternationalPhoneNumber implements PhoneNumber
{
    private function __construct(
        private readonly LibPhoneNumber $phoneNumber,
        private readonly CountryCode $countryCode
    ) {
    }

    public static function from(string $number): InternationalPhoneNumber
    {
        $util = PhoneNumberUtil::getInstance();

        if (!str_starts_with($number, '+')) {
            throw InvalidPhoneNumber::notInInternationalPhoneNumberFormat();
        }

        try {
            $parsed = $util->parse($number);
        } catch (NumberParseException) {
            throw InvalidPhoneNumber::notAPhoneNumber();
        }

        if (!$parsed || !$util->isValidNumber($parsed)) {
            throw InvalidPhoneNumber::notAPhoneNumber();
        }

        $code = $util->getRegionCodeForNumber($parsed);
        if (!$code) {
            throw InvalidPhoneNumber::noCountryCode();
        }

        $alpha2 = Alpha2::tryFrom($code);
        if (!$alpha2) {
            throw InvalidPhoneNumber::invalidCountryCode($code);
        }

        return new self($parsed, $alpha2);
    }

    public static function fromNational(string $number, string $alpha2): InternationalPhoneNumber
    {
        $util = PhoneNumberUtil::getInstance();
        if (str_starts_with($number, '+')) {
            throw InvalidPhoneNumber::notInNationalPhoneNumberFormat();
        }

        $countryCode = Alpha2::tryFrom($alpha2);
        if (!$countryCode) {
            throw InvalidPhoneNumber::invalidCountryCode($alpha2);
        }

        try {
            $parsed = $util->parse($number, $countryCode->value);
        } catch (NumberParseException) {
            throw InvalidPhoneNumber::notAPhoneNumber();
        }

        if (!$parsed || !$util->isValidNumber($parsed)) {
            throw InvalidPhoneNumber::notAPhoneNumber();
        }

        return new self($parsed, $countryCode);
    }

    public function alpha2CountryCode(): string
    {
        return $this->countryCode->asAlpha2()->value;
    }

    public function alpha3CountryCode(): string
    {
        return $this->countryCode->asAlpha3()->value;
    }

    public function numericCountryCode(): string
    {
        return $this->countryCode->asNumeric()->value;
    }

    public function format(PhoneNumberFormat $format): string
    {
        $util = PhoneNumberUtil::getInstance();
        return match ($format) {
            PhoneNumberFormat::E164 => $util->format($this->phoneNumber, LibPhoneNumberFormat::E164),
            PhoneNumberFormat::INTERNATIONAL => $util->format($this->phoneNumber, LibPhoneNumberFormat::INTERNATIONAL),
            PhoneNumberFormat::NATIONAL => $util->format($this->phoneNumber, LibPhoneNumberFormat::NATIONAL),
            PhoneNumberFormat::RFC3966 => $util->format($this->phoneNumber, LibPhoneNumberFormat::RFC3966),
        };
    }

    public function toStorage(PhoneStorageFormat $format): string
    {
        $util = PhoneNumberUtil::getInstance();
        return match ($format) {
            PhoneStorageFormat::E164 => $util->format($this->phoneNumber, LibPhoneNumberFormat::E164),
        };
    }

    public function type(): PhoneNumberType
    {
        $util = PhoneNumberUtil::getInstance();
        $type = $util->getNumberType($this->phoneNumber);
        return match ($type) {
            LibPhoneNumberType::MOBILE => PhoneNumberType::MOBILE,
            LibPhoneNumberType::FIXED_LINE => PhoneNumberType::FIXED_LINE,
            default => PhoneNumberType::UNKNOWN
        };
    }
}
