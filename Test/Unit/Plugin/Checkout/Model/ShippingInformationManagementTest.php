<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Checkout\Model;

use Romchik38\CheckoutShippingComment\Plugin\Checkout\Model\ShippingInformationManagement;
use \Magento\Checkout\Model\ShippingInformationManagement as Subject;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Checkout\Model\PaymentDetails;
use Magento\Quote\Model\QuoteRepository;
use \Magento\Quote\Model\Quote\Address;
use \Magento\Quote\Model\Quote;
use Magento\Checkout\Model\ShippingInformation;
use \Romchik38\CheckoutShippingComment\Model\ShippingComment;
use Magento\Framework\Logger\LoggerProxy;


use PHPUnit\Framework\TestCase;

class ShippingInformationManagementTest extends TestCase
{
    private $address;
    private $addressInformation;
    private $cartId = 1;
    private $logger;
    private $quote;
    private $quoteRepository;
    private $result;
    private $shippingComment;
    private $shippingCommentRepository;
    private $subject;

    public function setUp(): void
    {
        $this->address = $this->createMock(Address::class);
        $this->addressInformation = $this->createMock(ShippingInformation::class);
        $this->logger = $this->createMock(LoggerProxy::class);
        $this->quote = $this->createMock(Quote::class);
        $this->quoteRepository = $this->createMock(QuoteRepository::class);
        $this->result = $this->createMock(PaymentDetails::class);
        $this->shippingComment = $this->createMock(ShippingComment::class);
        $this->shippingCommentRepository = $this->createMock(ShippingCommentRepository::class);
        $this->subject = $this->createMock(Subject::class);
    }

    public function getNewShippingInformationManagement()
    {
        return (
            new ShippingInformationManagement(
                $this->quoteRepository,
                $this->shippingCommentRepository,
                $this->logger
            )
        );
    }

    public function testCommentFieldIsNotSet()
    {
        $shippingInformationManagement = $this->getNewShippingInformationManagement();

        $this->addressInformation->method('getShippingAddress')->willReturn($this->address);
        $this->address
            ->expects($this->once())
            ->method('getExtensionAttributes')->willReturn(
                new class
                {
                    function getCommentField()
                    {
                        return null;
                    }
                }
            );

        $this->shippingCommentRepository->expects($this->never())->method('getByQuoteAddressId');
        $this->shippingCommentRepository->expects($this->never())->method('create');
        $this->shippingCommentRepository->expects($this->never())->method('save');

        $result = $shippingInformationManagement->afterSaveAddressInformation(
            $this->subject,
            $this->result,
            $this->cartId,
            $this->addressInformation
        );

        $this->assertSame($this->result, $result);
    }

    public function testCommentFieldFindAndSave()
    {
        $shippingInformationManagement = $this->getNewShippingInformationManagement();

        $this->addressInformation->method('getShippingAddress')->willReturn($this->address);
        $this->address
            ->expects($this->once())
            ->method('getExtensionAttributes')->willReturn(
                new class
                {
                    function getCommentField()
                    {
                        return 'comment 1';
                    }
                }
            );

        $this->shippingCommentRepository
            ->expects($this->once())
            ->method('getByQuoteAddressId')
            ->willReturn($this->shippingComment);
        $this->shippingCommentRepository->expects($this->never())->method('create');
        $this->shippingCommentRepository->expects($this->once())->method('save');

        $this->quoteRepository
            ->method('getActive')
            ->with($this->equalTo($this->cartId))
            ->willReturn($this->quote);

        $this->quote
            ->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($this->address);

        $this->address->expects($this->once())->method('getId');

        $this->shippingComment
            ->expects($this->once())
            ->method('setComment')
            ->with($this->callback(function ($paramComment) {
                $this->assertSame('comment 1', $paramComment);
                return true;
            }));

        $result = $shippingInformationManagement->afterSaveAddressInformation(
            $this->subject,
            $this->result,
            $this->cartId,
            $this->addressInformation
        );

        $this->assertSame($this->result, $result);
    }

    public function testCommentFieldDoNotFindAndCreateNew()
    {
        $shippingInformationManagement = $this->getNewShippingInformationManagement();

        $this->addressInformation->method('getShippingAddress')->willReturn($this->address);
        $this->address
            ->expects($this->once())
            ->method('getExtensionAttributes')->willReturn(
                new class
                {
                    function getCommentField()
                    {
                        return 'comment 1';
                    }
                }
            );

        $this->shippingCommentRepository
            ->expects($this->once())
            ->method('getByQuoteAddressId')
            ->will($this->throwException(new NoSuchEntityException(__(''))));

        $this->shippingCommentRepository
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->shippingComment);

        $this->shippingCommentRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($paramObj) {
                $this->assertSame($this->shippingComment, $paramObj);
                return true;
            }));

        $this->quoteRepository
            ->method('getActive')
            ->with($this->equalTo($this->cartId))
            ->willReturn($this->quote);

        $this->quote
            ->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($this->address);

        $this->address->expects($this->once())->method('getId');

        $this->shippingComment
            ->expects($this->once())
            ->method('setComment')
            ->with($this->callback(function ($paramComment) {
                $this->assertSame('comment 1', $paramComment);
                return true;
            }));

        $result = $shippingInformationManagement->afterSaveAddressInformation(
            $this->subject,
            $this->result,
            $this->cartId,
            $this->addressInformation
        );

        $this->assertSame($this->result, $result);
    }

    public function testCommentFieldFindButSaveThrowError()
    {
        $shippingInformationManagement = $this->getNewShippingInformationManagement();

        $this->addressInformation->method('getShippingAddress')->willReturn($this->address);
        $this->address->method('getExtensionAttributes')->willReturn(
                new class
                {
                    function getCommentField() {
                        return 'comment 1';
                    }
                }
            );

        $this->shippingCommentRepository->method('getByQuoteAddressId')
            ->willReturn($this->shippingComment);
        
        $this->shippingCommentRepository
            ->expects($this->once())
            ->method('save')
            ->will($this->throwException(new CouldNotSaveException(__(''))));

        $this->quoteRepository->method('getActive')->willReturn($this->quote);
        $this->quote->method('getShippingAddress')->willReturn($this->address);

        $shippingInformationManagement->afterSaveAddressInformation(
            $this->subject,
            $this->result,
            $this->cartId,
            $this->addressInformation
        );

    }
}
