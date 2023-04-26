<?php

namespace rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig;

use arslanimamutdinov\ISOStandard3166\Country;
use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidatorInterface;
use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidators;
use rocketfellows\CountryVatNumberFormatValidatorsConfig\CountryVatNumberFormatValidatorsConfigInterface;

abstract class SpecificCountryVatNumberFormatValidatorsConfig implements CountryVatNumberFormatValidatorsConfigInterface
{
    protected $validators;

    public function __construct(
        ?CountryVatFormatValidatorInterface $defaultValidator = null,
        ?CountryVatFormatValidators $additionalValidators = null
    ) {
        $this->validators = $this->assembleValidators($defaultValidator, $additionalValidators);
    }

    abstract public function getCountry(): Country;
    abstract protected function getDefaultValidator(): CountryVatFormatValidatorInterface;

    public function getValidators(): CountryVatFormatValidators
    {
        return $this->validators;
    }

    private function assembleValidators(
        ?CountryVatFormatValidatorInterface $defaultValidator = null,
        ?CountryVatFormatValidators $additionalValidators = null
    ): CountryVatFormatValidators {
        /** @var CountryVatFormatValidatorInterface[] $validators */
        $validators = [];

        if ($defaultValidator instanceof CountryVatFormatValidatorInterface) {
            $validators[] = $defaultValidator;
        } else {
            $validators[] = $this->getDefaultValidator();
        }

        if ($additionalValidators instanceof CountryVatFormatValidators) {
            foreach ($additionalValidators as $additionalValidator) {
                $validators[] = $additionalValidator;
            }
        }

        return new CountryVatFormatValidators(...$validators);
    }
}
