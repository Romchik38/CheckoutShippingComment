<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Customer\Block\Address;

use Romchik38\CheckoutShippingComment\Plugin\Customer\Block\Address\Book as Plugin;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Escaper;
use \Magento\Customer\Block\Address\Book as Subject;
use Magento\Customer\Model\Data\Address;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomer;

class BookTest extends \PHPUnit\Framework\TestCase
{
    private $shippingCommentCustomerRepository;
    private $escaper;
    private $subject;
    private $address;
    private $shippingCommentCustomer;

    public function setUp(): void
    {
        $this->shippingCommentCustomerRepository = $this->createMock(ShippingCommentCustomerRepository::class);
        $this->escaper = $this->createMock(Escaper::class);
        $this->subject = $this->createMock(Subject::class);
        $this->address = $this->createMock(Address::class);
        $this->shippingCommentCustomer = $this->createMock(ShippingCommentCustomer::class);
    }

    public function testAfterGetAddressHtml()
    {
        $plugin = new Plugin(
            $this->shippingCommentCustomerRepository,
            $this->escaper
        );

        $subjectResult = '';
        $commentText = 'Test Comment 1';

        $this->address->expects($this->once())->method('getId')->willReturn(1);

        $this->shippingCommentCustomerRepository
            ->expects($this->once())
            ->method('getByCustomerAddressId')
            ->with($this->equalTo(1))
            ->willReturn($this->shippingCommentCustomer);

        $this->shippingCommentCustomer
            ->expects($this->once())
            ->method('getComment')
            ->willReturn($commentText);

        $this->escaper->expects($this->once())
            ->method('escapeHtml')
            ->with($this->equalTo($commentText))
            ->willReturn($commentText);

        $expectedResult = '<br><span class="comment">comment: ' . $commentText . '</span>';

        $result = $plugin->afterGetAddressHtml(
            $this->subject,
            $subjectResult,
            $this->address
        );

        $this->assertSame($expectedResult, $result);
    }
}
