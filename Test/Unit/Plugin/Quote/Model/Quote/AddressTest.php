<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Quote\Model\Quote;

use Romchik38\CheckoutShippingComment\Plugin\Quote\Model\Quote\Address as Plugin;
use \Magento\Quote\Model\Quote\Address as Subject;
use \Magento\Customer\Model\Data\Address as Result;
use \Magento\Quote\Api\Data\AddressExtension as QuoteExtension;
use \Magento\Customer\Api\Data\AddressExtension as CustomerExtension;

class AddressTest extends \PHPUnit\Framework\TestCase
{
    private $subject;
    private $result;
    private $quoteExtension;
    private $customerExtension;

    public function setUp(): void
    {
        $this->subject = $this->createMock(Subject::class);
        $this->result = $this->createMock(Result::class);
        $this->quoteExtension = $this->createMock(QuoteExtension::class);
        $this->customerExtension = $this->createMock(CustomerExtension::class);
    }

    public function testAfterExportCustomerAddressCommentExists()
    {
        $plugin = new Plugin();
        $commentText = 'some comment 1';

        $this->subject->method('getExtensionAttributes')
            ->willReturn($this->quoteExtension);
        $this->quoteExtension->method('getCommentField')
            ->willReturn($commentText);
        $this->result->method('getExtensionAttributes')
            ->willReturn($this->customerExtension);

        $this->customerExtension->expects($this->once())
            ->method('setCommentField')->with($this->equalTo($commentText));

        $result = $plugin->afterExportCustomerAddress($this->subject, $this->result);

        $this->assertSame($this->result, $result);
    }

    /**
     * The plugin do nothing, because
     *  a comment was not provided
     */
    public function testAfterExportCustomerAddressNoComment()
    {
        $plugin = new Plugin();
        $commentText = null;

        $this->subject->method('getExtensionAttributes')
            ->willReturn($this->quoteExtension);
        $this->quoteExtension->method('getCommentField')
            ->willReturn($commentText);
        $this->result->method('getExtensionAttributes')
            ->willReturn($this->customerExtension);

        $this->customerExtension->expects($this->never())
            ->method('setCommentField');

        $result = $plugin->afterExportCustomerAddress($this->subject, $this->result);

        $this->assertSame($this->result, $result);
    }
}
