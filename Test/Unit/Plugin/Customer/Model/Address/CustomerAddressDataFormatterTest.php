<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Customer\Model\Address;

use Romchik38\CheckoutShippingComment\Plugin\Customer\Model\Address\CustomerAddressDataFormatter as Plugin;
use \Magento\Customer\Model\Address\CustomerAddressDataFormatter as Subject;
use \Magento\Customer\Api\Data\AddressExtension;

class CustomerAddressDataFormatterTest extends \PHPUnit\Framework\TestCase
{
    private $subject;
    private $addressExtension;

    public function setUp(): void
    {
        $this->subject = $this->createMock(Subject::class);
        $this->addressExtension = $this->createMock(AddressExtension::class);
    }

    /**
     * key 'extension_attributes' doesn't exist
     */
    public function testAfterPrepareAddressNoExtension()
    {
        $plugin = new Plugin();
        $arr = ['customerData' => 'some data'];

        $result = $plugin->afterPrepareAddress($this->subject, $arr);
        $this->assertSame($arr, $result);
    }

    /**
     * $extensionAttributes not instanceof \Magento\Customer\Api\Data\AddressExtension
     */
    public function testAfterPrepareAddressIsNotInstanceofExtension()
    {
        $plugin = new Plugin();

        $fakeExtension = (
            new class
            {
                public function __toArray()
                {
                    return 'I am not an array';
                }
            }
        );

        $arr =  ['extension_attributes' => $fakeExtension];
        $result = $plugin->afterPrepareAddress($this->subject, $arr);

        $this->assertNotEquals('I am not an array', $result['extension_attributes']);
    }

    /**
     * check __toArray
     */
    public function testAfterPrepareAddress()
    {
        $plugin = new Plugin();

        $this->addressExtension->expects($this->once())->method('__toArray')
            ->willReturn(['Yes, I am array']);


        $arr =  ['extension_attributes' =>  $this->addressExtension];
        $result = $plugin->afterPrepareAddress($this->subject, $arr);

        $this->assertSame(['Yes, I am array'], $result['extension_attributes']);
    }
}
