<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Checkout\Model;

use Romchik38\CheckoutShippingComment\Plugin\Checkout\Model\ShippingInformationManagement;
use \Magento\Checkout\Model\ShippingInformationManagement as Subject;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Checkout\Model\PaymentDetails;
use Magento\Quote\Model\QuoteRepository;
use \Magento\Quote\Model\Quote\Address;
use Magento\Checkout\Model\ShippingInformation;


use PHPUnit\Framework\TestCase;

class ShippingInformationManagementTest extends TestCase
{
    private $quoteRepository;
    private $shippingCommentRepository;
    private $subject;
    private $result;
    private $cartId = 1;
    private $addressInformation;
    private $address;

    public function setUp(): void
    {
        $this->quoteRepository = $this->createMock(QuoteRepository::class);
        $this->shippingCommentRepository = $this->createMock(ShippingCommentRepository::class);
        $this->subject = $this->createMock(Subject::class);
        $this->result = $this->createMock(PaymentDetails::class);
        $this->addressInformation = $this->createMock(ShippingInformation::class);
        $this->address = $this->createMock(Address::class);
    }

    public function getNewShippingInformationManagement()
    {
        return (
            new ShippingInformationManagement(
                $this->quoteRepository,
                $this->shippingCommentRepository
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
}
