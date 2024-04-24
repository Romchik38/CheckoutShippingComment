<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Quote;

use Romchik38\CheckoutShippingComment\Plugin\Quote\Model\QuoteManagement as Plugin;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use \Psr\Log\LoggerInterface\Proxy;
use \Magento\Framework\Exception\NoSuchEntityException;
use \Magento\Quote\Model\QuoteManagement as Subject;
use \Magento\Quote\Model\Quote;
use \Magento\Quote\Model\Quote\Address;
use Romchik38\CheckoutShippingComment\Model\ShippingComment;
use \Magento\Quote\Api\Data\AddressExtension;

class QuoteManagementTest extends \PHPUnit\Framework\TestCase
{
    private $shippingCommentRepository;
    private $logger;
    private $subject;
    private $quote;
    private $address;
    private $shippingComment;
    private $addressExtension;

    public function setUp(): void
    {
        $this->shippingCommentRepository = $this->createMock(
            ShippingCommentRepository::class
        );
        $this->logger = $this->createMock(Proxy::class);
        $this->subject = $this->createMock(Subject::class);
        $this->quote = $this->createMock(Quote::class);
        $this->address = $this->createMock(Address::class);
        $this->shippingComment = $this->createMock(ShippingComment::class);
        $this->addressExtension = $this->createMock(AddressExtension::class);
    }

    protected function createNewPlugin()
    {
        return (new Plugin(
            $this->shippingCommentRepository,
            $this->logger
        ));
    }

    /**
     * Do not save comment in repository
     * because $saveInAddressBook is set to 0
     */
    public function testBeforeSubmitDoNotSave()
    {
        $plugin = $this->createNewPlugin();

        $this->quote->method('getShippingAddress')->willReturn(
            new class
            {
                function getSaveInAddressBook()
                {
                    return 0;
                }
            }
        );

        $result = $plugin->beforeSubmit($this->subject, $this->quote);
        $this->assertSame(null, $result);
    }

    /**
     * Add a comment to provided quote address
     */
    public function testBeforeSubmit()
    {
        $plugin = $this->createNewPlugin();
        $shippingAddressId = 1;
        $shippingComment = 'some comment 1';

        $this->quote->method('getShippingAddress')->willReturn($this->address);

        $this->address->method('getSaveInAddressBook')->willReturn(1);
        $this->address->method('getId')->willReturn($shippingAddressId);
        $this->address->method('getExtensionAttributes')->willReturn($this->addressExtension);
        $this->address->expects($this->once())->method('setExtensionAttributes')
            ->with($this->callback(
                function ($param) {
                    $this->assertSame($this->addressExtension, $param);
                    return true;
                }
            ));

        $this->shippingCommentRepository->expects($this->once())
            ->method('getByQuoteAddressId')
            ->with($this->equalTo($shippingAddressId))
            ->willReturn($this->shippingComment);

        $this->shippingComment->method('getComment')->willReturn($shippingComment);

        $this->addressExtension->expects($this->once())->method('setCommentField')
            ->with($this->equalTo($shippingComment));

        $result = $plugin->beforeSubmit($this->subject, $this->quote);
        $this->assertSame([$this->quote], $result);
    }

    /**
     * Shipping Comment Repository method getByQuoteAddressId() throw an 
     *  exception
     */
    public function testBeforeSubmitGetByQuoteAddressIdThrowException()
    {
        $plugin = $this->createNewPlugin();
        $shippingAddressId = 1;
        $shippingComment = 'some comment 1';

        $this->quote->method('getShippingAddress')->willReturn($this->address);

        $this->address->method('getSaveInAddressBook')->willReturn(1);
        $this->address->method('getId')->willReturn($shippingAddressId);
        $this->address->method('getExtensionAttributes')->willReturn($this->addressExtension);
        $this->address->method('setExtensionAttributes');

        $this->shippingCommentRepository->expects($this->once())
            ->method('getByQuoteAddressId')
            ->willThrowException(new NoSuchEntityException(__('')));

        $result = $plugin->beforeSubmit($this->subject, $this->quote);
        $this->assertSame(null, $result);
    }
}
