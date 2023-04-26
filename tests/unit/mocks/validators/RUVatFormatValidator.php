<?php

namespace rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit\mocks\validators;

use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidatorInterface;

class RUVatFormatValidator implements CountryVatFormatValidatorInterface
{
    public function isValid(string $vatNumber): bool
    {
        return true;
    }
}
