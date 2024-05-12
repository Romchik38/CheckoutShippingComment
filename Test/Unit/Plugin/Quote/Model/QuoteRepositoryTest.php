<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Quote\Model;

use Romchik38\CheckoutShippingComment\Plugin\Quote\Model\QuoteRepository as Plugin;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Logger\LoggerProxy;
use \Magento\Quote\Model\QuoteRepository as Subject;
use \Magento\Quote\Model\Quote as Result;
use \Magento\Quote\Model\Quote\Address;
use \Magento\Quote\Api\Data\AddressExtension;
use Romchik38\CheckoutShippingComment\Model\ShippingComment;

class QuoteRepositoryTest extends \PHPUnit\Framework\TestCase
{
    private $subject;
    private $result;
    private $address;
    private $addressExtension;
    private $shippingCommentRepository;
    private $logger;
    private $shippingComment;

    public function setUp(): void
    {
        $this->subject = $this->createMock(Subject::class);
        $this->result = $this->createMock(Result::class);
        $this->address = $this->createMock(Address::class);
        $this->addressExtension = $this->createMock(AddressExtension::class);
        $this->shippingCommentRepository = $this->createMock(ShippingCommentRepository::class);
        $this->logger = $this->createMock(LoggerProxy::class);
        $this->shippingComment = $this->createMock(ShippingComment::class);
    }

    /**
     * Comment already provided, so just return result
     *
     * @return void
     */
    public function testAfterGetActiveCommentAlreadySet() {
        $plugin = new Plugin($this->shippingCommentRepository, $this->logger);
        $quoteCommentField = 'some comment 1';

        $this->result->expects($this->once())->method('getShippingAddress')
            ->willReturn($this->address);
        
        $this->address->method('getExtensionAttributes')
            ->willReturn($this->addressExtension);
        
        $this->addressExtension->expects($this->once())->method('getCommentField')
            ->willReturn($quoteCommentField);

        // never execited
        $this->shippingCommentRepository->expects($this->never())
            ->method('getByQuoteAddressId');
        $this->addressExtension->expects($this->never())
            ->method('setCommentField');

        $result = $plugin->afterGetActive($this->subject, $this->result);

        $this->assertSame($this->result, $result);
    }

    /**
     * Comment wasn't provided, using repository to retrive it.
     *
     * @return void
     */
    public function testAfterGetActiveRetriveComment() {
        $plugin = new Plugin($this->shippingCommentRepository, $this->logger);
        $quoteCommentField = null;
        $repositoryCommentField = 'some text 1';
        $addressId = '1';

        $this->result->expects($this->once())->method('getShippingAddress')
            ->willReturn($this->address);
        
        $this->address->method('getExtensionAttributes')
            ->willReturn($this->addressExtension);

        $this->address->method('getId')
            ->willReturn($addressId);
        
        $this->addressExtension->expects($this->once())->method('getCommentField')
            ->willReturn($quoteCommentField);

        $this->shippingCommentRepository->expects($this->once())
            ->method('getByQuoteAddressId')
            ->with($this->equalTo($addressId))
            ->willReturn($this->shippingComment);

        $this->shippingComment->expects($this->once())->method('getComment')
            ->willReturn($repositoryCommentField);

        $this->addressExtension->expects($this->once())
            ->method('setCommentField')
            ->with($this->equalTo($repositoryCommentField));

        $result = $plugin->afterGetActive($this->subject, $this->result);

        $this->assertSame($this->result, $result);
    }

    /**
     * Comment wasn't provided, repository throw error.
     *
     * @return void
     */
    public function testAfterGetActiveRepositoryThrowError() {
        $plugin = new Plugin($this->shippingCommentRepository, $this->logger);
        $quoteCommentField = null;
        $repositoryCommentField = 'some text 1';
        $addressId = '1';

        $this->result->expects($this->once())->method('getShippingAddress')
            ->willReturn($this->address);
        
        $this->address->method('getExtensionAttributes')
            ->willReturn($this->addressExtension);

        $this->address->method('getId')
            ->willReturn($addressId);
        
        $this->shippingCommentRepository->expects($this->once())
            ->method('getByQuoteAddressId')
            ->with($this->equalTo($addressId))
            ->will($this->throwException(new NoSuchEntityException(__(''))));

        $result = $plugin->afterGetActive($this->subject, $this->result);

        $this->assertSame($this->result, $result);
    }
}
