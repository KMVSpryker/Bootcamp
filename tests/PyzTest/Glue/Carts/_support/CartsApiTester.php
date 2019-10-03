<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts;

use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class CartsApiTester extends ApiEndToEndTester
{
    use _generated\CartsApiTesterActions;

    public const TEST_USERNAME = 'test username';
    public const TEST_PASSWORD = 'test password';

    public const QUANTITY_FOR_ITEM_UPDATE = 33;
    public const STORE_DE = 'DE';
    public const TEST_CART_NAME = 'Test cart name';
    public const TEST_GUEST_CART_NAME = 'Test guest cart name';
    public const CURRENCY_EUR = 'EUR';
    public const GROSS_MODE = 'GROSS_MODE';

    public const ANONYMOUS_CUSTOMER_REFERENCE1 = 'anonymous:666';
    public const ANONYMOUS_CUSTOMER_REFERENCE2 = 'anonymous:777';
    public const ANONYMOUS_CUSTOMER_REFERENCE3 = 'anonymous:888';

    public const VALUE_FOR_ANONYMOUS1 = '666';
    public const VALUE_FOR_ANONYMOUS2 = '777';
    public const VALUE_FOR_ANONYMOUS3 = '888';

    /**
     * @param int $quantity
     * @param string $resourceName
     * @param string $itemSku
     *
     * @return void
     */
    public function seeCartItemQuantityEqualsToQuantityInRequest(int $quantity, string $resourceName, string $itemSku): void
    {
        $jsonPath = sprintf(
            '$..included[?(@.type == \'%s\' and @.id == \'%s\')].attributes.quantity',
            $resourceName,
            $itemSku
        );

        $this->assertEquals(
            $quantity,
            $this->grabDataFromResponseByJsonPath($jsonPath)[0]
        );
    }
}
