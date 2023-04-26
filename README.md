# Specific country vat number format validator config

![Code Coverage Badge](./badge.svg)

This package provides an abstract class for configuring a list of validators for a specific country.
It is one of the implementations of the&nbsp;**_rocketfellows\CountryVatNumberFormatValidatorsConfig\CountryVatNumberFormatValidatorsConfigInterface interface_**.&nbsp;

## Installation

```shell
composer require rocketfellows/specific-country-vat-number-format-validators-config
```

## Dependencies

- https://github.com/arslanim/iso-standard-3166 v1.0.2
- https://github.com/rocketfellows/country-vat-format-validator-interface v1.1.0
- https://github.com/rocketfellows/country-vat-number-format-validators-config v1.0.0

## List of package components

- **_SpecificCountryVatNumberFormatValidatorsConfig_** - an abstract class that implements CountryVatNumberFormatValidatorsConfigInterface interface for configuring a tuple of validators for a specific country;

## SpecificCountryVatNumberFormatValidatorsConfig description

SpecificCountryVatNumberFormatValidatorsConfig properties:
- rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\SpecificCountryVatNumberFormatValidatorsConfig::$validators - attribute to store a tuple of validators for specific country;

SpecificCountryVatNumberFormatValidatorsConfig functions:
- **_abstract public function getCountry(): Country_** - abstract public function which must be implemented in the child class, it defines specific country for concrete config;
- **_abstract protected function getDefaultValidator(): CountryVatFormatValidatorInterface_** - abstract protected function which must be implemented in the child class, it defines default vat number format validator for country, returns instance of \rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidatorInterface;

Class constructor takes two optional parameters:
- $defaultValidator - instance of rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidatorInterface or null, if this parameter is passed, then it replaces the validator returned by the **_getDefaultValidator_** function from the child class
- $additionalValidators - instance of \rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidators or null, additional validators tuple for specific country

A child class extending SpecificCountryVatNumberFormatValidatorsConfig must implement the following functions:
- **_public function getCountry(): Country_** - implementation of the interface function CountryVatNumberFormatValidatorsConfigInterface, returns instance of **_arslanimamutdinov\ISOStandard3166\Country_**, thus the child class defines the country for which the vat number format validators are configured
- **_protected function getDefaultValidator(): CountryVatFormatValidatorInterface_** - implementation of the abstract function of the class SpecificCountryVatNumberFormatValidatorsConfig, returns an object of type \rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidatorInterface - default vat number format validator for specific country

Depending on the parameters passed to the constructor of the child class, the $validators tuple is formed in the CountryVatFormatValidators tuple, the validators in which are typed according to the following rules:
- if $defaultValidator parameter is null and $additionalValidators parameter is null then $validators property stores only one validator which returns from implemented getDefaultValidator function
- if $defaultValidator parameter is null and $additionalValidators parameter not null then $validators property stores validator which returns from implemented getDefaultValidator function and passed $additionalValidators into constructor
- if $defaultValidator parameter not null and $additionalValidators parameter is null then $validators property stores passed $defaultValidator into constructor
- if $defaultValidator parameter not null and $additionalValidators parameter not null then $validators property stores passed $defaultValidator and $additionalValidators into constructor

## Usage example

Let's say we need to implement the configuration of vat number format validators for Germany.
To do this, you need to create a class (for example, DEVatNumberFormatValidatorsConfig) and inherit it from SpecificCountryVatNumberFormatValidatorsConfig.
The default vat number format validator for Germany can be used from the package https://packagist.org/packages/rocketfellows/de-vat-format-validator - there is already a validator class for Germany that implements the CountryVatFormatValidatorInterface interface.
For default vat number format validator for Germany we will use DEVatFormatValidator.
For getCountry function we can use arslanimamutdinov\ISOStandard3166\ISO3166 utility we already defined Country getter for Germany.

```php
class DEVatNumberFormatValidatorsConfig extends SpecificCountryVatNumberFormatValidatorsConfig
{
    public function getCountry(): Country
    {
        return ISO3166::DE();
    }

    protected function getDefaultValidator(): CountryVatFormatValidatorInterface
    {
        return new DEVatFormatValidator();
    }
}
```

And that's it, we created default pre-configured vat number format validators for Germany.

```php
$config = new DEVatNumberFormatValidatorsConfig();

// will return Country instance for Germany (according to standard ISO 3166)
$config->getCountry();

// will return CountryVatFormatValidators tuple with one item, which is DEVatFormatValidator instance
$config->getValidators();
```

Let's say we want to replace the implementation of the vat number format validator for Germany in the current config.
To do this, you need to create a new validator class that must implement the CountryVatFormatValidatorInterface interface.

```php
class AnotherDEVatFormatValidator implements CountryVatFormatValidatorInterface
{
    public function isValid(string $vatNumber): bool
    {
        // add some implementation
    }
}
```

Further, when instantiating DEVatNumberFormatValidatorsConfig to the constructor, as the first parameter, you need to pass the instance of the new validator, which will replace the default validator from the config.

```php
$config = new DEVatNumberFormatValidatorsConfig(new AnotherDEVatFormatValidator());

// will return Country instance for Germany (according to standard ISO 3166)
$config->getCountry();

// will return CountryVatFormatValidators tuple with one item, which is AnotherDEVatFormatValidator instance
$config->getValidators();
```

Suppose we do not want to replace the default validator in the config, but want to add AnotherDEVatFormatValidator as an additional one.
For example, the default validator checks the vat number format only for individuals, and we want the config to have a validator that checks the vat number format for legal entities.
To do this, when instantiating DEVatNumberFormatValidatorsConfig, pass null as the first parameter, and pass the CountryVatFormatValidators tuple consisting of one element as the second parameter.

```php
$config = new DEVatNumberFormatValidatorsConfig(
    null,
    (
        new CountryVatFormatValidators(
            new AnotherDEVatFormatValidator()
        )
    )
);

// will return Country instance for Germany (according to standard ISO 3166)
$config->getCountry();

// will return CountryVatFormatValidators tuple with two items:
// object instance of DEVatFormatValidator
// and object instance of AnotherDEVatFormatValidator
$config->getValidators();
```

If you want to completely change the DEVatNumberFormatValidatorsConfig configuration, then you need to pass your own as the default validator and as additional validators when instantiating the config.

More use case examples can be found in the package's unit tests: rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit\SpecificCountryVatNumberFormatValidatorsConfigTest.

## Contributing

Welcome to pull requests. If there is a major changes, first please open an issue for discussion.

Please make sure to update tests as appropriate.
