<?php

namespace PyzTest\Zed\StringReverser\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StringReverserBuilder;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group StringReverser
 * @group Business
 * @group Facade
 * @group StringReverserFacadeTest
 * Add your own group annotations below this line
 */
class StringReverserFacadeTest extends Unit
{
    /**
     * @var \PyzTest\Zed\StringReverser\StringReverserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testStringIsReversedCorrectly(): void
    {
        // Arrange
        $stringReverserTransfer = (new StringReverserBuilder([
            'originalString' => 'Hello Spryker!'
        ]))->build();

        // Act
        $stringReverserResultTransfer = $this->tester->getFacade()->reverseString($stringReverserTransfer);

        // Assert
        $this->assertSame('!rekyrpS olleH', $stringReverserResultTransfer->getReversedString());
    }
}