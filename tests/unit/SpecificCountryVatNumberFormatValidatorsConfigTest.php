<?php

namespace rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit;

use arslanimamutdinov\ISOStandard3166\ISO3166;
use PHPUnit\Framework\TestCase;
use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidatorInterface;
use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidators;
use rocketfellows\CountryVatNumberFormatValidatorsConfig\CountryVatNumberFormatValidatorsConfigInterface;
use rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit\mocks\configs\RUVatNumberFormatValidatorsConfig;
use rocketfellows\SpecificCountryVatNumberFormatValidatorsConfig\tests\unit\mocks\validators\RUVatFormatValidator;

class SpecificCountryVatNumberFormatValidatorsConfigTest extends TestCase
{
    private const EXPECTED_INTERFACE_IMPLEMENTATION = CountryVatNumberFormatValidatorsConfigInterface::class;

    public function testSpecificConfigImplementedInterface(): void
    {
        $this->assertEquals(self::EXPECTED_INTERFACE_IMPLEMENTATION, (new RUVatNumberFormatValidatorsConfig()));
    }

    /**
     * @param CountryVatFormatValidatorInterface|null $defaultValidator
     * @param CountryVatFormatValidators|null $additionalValidators
     * @param CountryVatFormatValidatorInterface[] $expectedCountryValidators
     */
    public function testInitializeSpecificConfigByDifferentUseCases(
        ?CountryVatFormatValidatorInterface $defaultValidator,
        ?CountryVatFormatValidators $additionalValidators,
        array $expectedCountryValidators
    ): void {
        $config = new RUVatNumberFormatValidatorsConfig($defaultValidator, $additionalValidators);

        $actualValidators = [];

        foreach ($config->getValidators() as $actualValidator) {
            $actualValidators[] = $actualValidator;
        }

        $this->assertEquals(ISO3166::RU(), $config->getCountry());
        $this->assertEquals($actualValidators, $expectedCountryValidators);
    }

    public function getSpecificConfigValidators(): array
    {
        $defaultValidator = $this->createMock(CountryVatFormatValidatorInterface::class);

        $firstAdditionalValidator = $this->createMock(CountryVatFormatValidatorInterface::class);
        $secondAdditionalValidator = $this->createMock(CountryVatFormatValidatorInterface::class);
        $thirdAdditionalValidator = $this->createMock(CountryVatFormatValidatorInterface::class);

        return [
            'default validator not set and additional validators not set' => [
                'defaultValidator' => null,
                'additionalValidators' => null,
                'expectedCountryValidators' => [
                    (new RUVatFormatValidator()),
                ],
            ],
            'default validator set and additional validators not set' => [
                'defaultValidator' => $defaultValidator,
                'additionalValidators' => null,
                'expectedCountryValidators' => [
                    $defaultValidator,
                ],
            ],
            'default validator set and additional validators set and not empty' => [
                'defaultValidator' => $defaultValidator,
                'additionalValidators' => new CountryVatFormatValidators(
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator
                ),
                'expectedCountryValidators' => [
                    $defaultValidator,
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator,
                ],
            ],
        ];
    }
}
