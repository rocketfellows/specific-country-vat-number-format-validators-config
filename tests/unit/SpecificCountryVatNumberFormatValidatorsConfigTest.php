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
        $this->assertInstanceOf(self::EXPECTED_INTERFACE_IMPLEMENTATION, (new RUVatNumberFormatValidatorsConfig()));
    }

    public function testDefaultInitialization(): void
    {
        $config = new RUVatNumberFormatValidatorsConfig();

        $this->assertEquals(ISO3166::RU(), $config->getCountry());
        $this->assertEquals(
            (new CountryVatFormatValidators((new RUVatFormatValidator()))),
            $config->getValidators()
        );
    }

    /**
     * @dataProvider getSpecificConfigValidators
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
            'default validator not set and additional validators set' => [
                'defaultValidator' => null,
                'additionalValidators' => new CountryVatFormatValidators(
                    $defaultValidator,
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator
                ),
                'expectedCountryValidators' => [
                    (new RUVatFormatValidator()),
                    $defaultValidator,
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator
                ],
            ],
            'default validator set and additional validators not set' => [
                'defaultValidator' => $defaultValidator,
                'additionalValidators' => null,
                'expectedCountryValidators' => [
                    $defaultValidator,
                ],
            ],
            'default validator set and additional validators set' => [
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
            'default validator not set and additional validators set and empty' => [
                'defaultValidator' => null,
                'additionalValidators' => new CountryVatFormatValidators(),
                'expectedCountryValidators' => [
                    (new RUVatFormatValidator()),
                ],
            ],
            'default validator set and additional validators set and empty' => [
                'defaultValidator' => $defaultValidator,
                'additionalValidators' => new CountryVatFormatValidators(),
                'expectedCountryValidators' => [
                    $defaultValidator,
                ],
            ],
            'default validator not set and additional validators set with repetition' => [
                'defaultValidator' => null,
                'additionalValidators' => new CountryVatFormatValidators(
                    $defaultValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                ),
                'expectedCountryValidators' => [
                    (new RUVatFormatValidator()),
                    $defaultValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                ],
            ],
            'default validator set and additional validators set with repetition' => [
                'defaultValidator' => $defaultValidator,
                'additionalValidators' => new CountryVatFormatValidators(
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                ),
                'expectedCountryValidators' => [
                    $defaultValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                ],
            ],
            'default validator set and additional validators set with repetition and has default validator in additional' => [
                'defaultValidator' => $defaultValidator,
                'additionalValidators' => new CountryVatFormatValidators(
                    $defaultValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                ),
                'expectedCountryValidators' => [
                    $defaultValidator,
                    $defaultValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $firstAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $secondAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                    $thirdAdditionalValidator,
                ],
            ],
        ];
    }
}
