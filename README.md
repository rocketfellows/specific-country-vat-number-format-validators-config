# Specific country vat number format validator config

![Code Coverage Badge](./badge.svg)

This package provides an abstract class for configuring a list of validators for a specific country.
It is one of the implementations of the **_CountryVatNumberFormatValidatorsConfigInterface_** interface.

## Installation

```shell
composer require rocketfellows/specific-country-vat-number-format-validators-config
```

## Dependencies

- https://github.com/arslanim/iso-standard-3166 v1.0.2;
- https://github.com/rocketfellows/country-vat-format-validator-interface v1.1.0;
- https://github.com/rocketfellows/country-vat-number-format-validators-config v1.0.0;

## List of package components

- **_SpecificCountryVatNumberFormatValidatorsConfig_** - an abstract class that implements **_CountryVatNumberFormatValidatorsConfigInterface_** interface for configuring a tuple of validators for a specific country;

## SpecificCountryVatNumberFormatValidatorsConfig description

**_SpecificCountryVatNumberFormatValidatorsConfig_** properties:
- **_$validators_** - attribute to store a tuple of validators for specific country;

**_SpecificCountryVatNumberFormatValidatorsConfig_** functions:
- **_abstract public function getCountry(): Country_** - abstract public function which must be implemented in the child class, it defines specific country for concrete config;
- **_abstract protected function getDefaultValidator(): CountryVatFormatValidatorInterface_** - abstract protected function which must be implemented in the child class, it defines default vat number format validator for country, returns instance of **_CountryVatFormatValidatorInterface_**;

Class constructor takes two optional parameters:
- **_$defaultValidator_** - instance of **_CountryVatFormatValidatorInterface_** or null, if this parameter is passed, then it replaces the validator returned by the **_getDefaultValidator_** function from the child class;
- **_$additionalValidators_** - instance of **_CountryVatFormatValidators_** or null, additional validators tuple for specific country;

A child class extending **_SpecificCountryVatNumberFormatValidatorsConfig_** must implement the following functions:
- **_public function getCountry(): Country_** - implementation of the interface function **_CountryVatNumberFormatValidatorsConfigInterface_**, returns instance of **_arslanimamutdinov\ISOStandard3166\Country_**, thus the child class defines the country for which the vat number format validators are configured;
- **_protected function getDefaultValidator(): CountryVatFormatValidatorInterface_** - implementation of the abstract function of the class **_SpecificCountryVatNumberFormatValidatorsConfig_**, returns an object of type **_CountryVatFormatValidatorInterface_** - default vat number format validator for specific country;

Depending on the parameters passed to the constructor of the child class, the **_$validators_** tuple is formed in the **_CountryVatFormatValidators_** tuple, the validators in which are typed according to the following rules:
- if **_$defaultValidator_** parameter is null and **_$additionalValidators_** parameter is null then **_$validators_** property stores only one validator which returns from implemented **_getDefaultValidator_** function;
- if **_$defaultValidator_** parameter is null and **_$additionalValidators_** parameter not null then **_$validators_** property stores validator which returns from implemented **_getDefaultValidator_** function and passed **_$additionalValidators_** into constructor;
- if **_$defaultValidator_** parameter not null and **_$additionalValidators_** parameter is null then **_$validators_** property stores passed **_$defaultValidator_** into constructor;
- if **_$defaultValidator_** parameter not null and **_$additionalValidators_** parameter not null then **_$validators_** property stores passed **_$defaultValidator_** and **_$additionalValidators_** into constructor;

## Usage example

Let's say we need to implement the configuration of vat number format validators for Germany.
To do this, you need to create a class (for example, **_DEVatNumberFormatValidatorsConfig_**) and inherit it from **_SpecificCountryVatNumberFormatValidatorsConfig_**.
The default vat number format validator for Germany can be used from the package https://packagist.org/packages/rocketfellows/de-vat-format-validator - there is already a validator class for Germany that implements the **_CountryVatFormatValidatorInterface_** interface.
For default vat number format validator for Germany we will use DEVatFormatValidator.
For **_getCountry_** function we can use arslanimamutdinov\ISOStandard3166\ISO3166 utility we already defined **_Country_** getter for Germany.

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
To do this, you need to create a new validator class that must implement the **_CountryVatFormatValidatorInterface_** interface.

```php
class AnotherDEVatFormatValidator implements CountryVatFormatValidatorInterface
{
    public function isValid(string $vatNumber): bool
    {
        // add some implementation
    }
}
```

Further, when instantiating **_DEVatNumberFormatValidatorsConfig_** to the constructor, as the first parameter, you need to pass the instance of the new validator, which will replace the default validator from the config.

```php
$config = new DEVatNumberFormatValidatorsConfig(new AnotherDEVatFormatValidator());

// will return Country instance for Germany (according to standard ISO 3166)
$config->getCountry();

// will return CountryVatFormatValidators tuple with one item, which is AnotherDEVatFormatValidator instance
$config->getValidators();
```

Suppose we do not want to replace the default validator in the config, but want to add **_AnotherDEVatFormatValidator_** as an additional one.
For example, the default validator checks the vat number format only for individuals, and we want the config to have a validator that checks the vat number format for legal entities.
To do this, when instantiating **_DEVatNumberFormatValidatorsConfig_**, pass null as the first parameter, and pass the **_CountryVatFormatValidators_** tuple consisting of one element as the second parameter.

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

If you want to completely change the **_DEVatNumberFormatValidatorsConfig_** configuration, then you need to pass your own as the default validator and as additional validators when instantiating the config.

More use case examples can be found in the package's unit tests: **_rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit\SpecificCountryVatNumberFormatValidatorsConfigTest_**.

## Contributing

Welcome to pull requests. If there is a major changes, first please open an issue for discussion.

Please make sure to update tests as appropriate.
