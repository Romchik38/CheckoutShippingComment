<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Customer\Model\Address;

use Romchik38\CheckoutShippingComment\Plugin\Customer\Model\Address\CustomerAddressDataFormatter as Plugin;
use \Magento\Customer\Model\Address\CustomerAddressDataFormatter as Subject;
use \Magento\Customer\Api\Data\AddressExtension;

class CustomerAddressDataFormatterTest extends \PHPUnit\Framework\TestCase
{
    private $subject;

    public function setUp(): void
    {
        $this->subject = $this->createMock(Subject::class);
    }

    /**
     * 'extension_attributes' doesn't exist
     */
    public function testAfterPrepareAddressNoExtension()
    {
        $plugin = new Plugin();
        $arr = ['customerData' => 'some data'];

        $result = $plugin->afterPrepareAddress($this->subject, $arr);
        $this->assertSame($arr, $result);
    }

    // 2 $extensionAttributes not instanceof \Magento\Customer\Api\Data\AddressExtension
    // 3 check __toArray and new result
}
