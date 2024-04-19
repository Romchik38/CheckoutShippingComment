<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Customer\Model;

use Romchik38\CheckoutShippingComment\Plugin\Customer\Model\Address as Plugin;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomer;
use Magento\Framework\Exception\NoSuchEntityException;
use \Magento\Customer\Model\Address as Subject;
use \Magento\Customer\Model\Data\Address as Result;
use \Magento\Customer\Api\Data\AddressExtension;

class AddressTest extends \PHPUnit\Framework\TestCase
{
    private $shippingCommentCustomerRepository;
    private $shippingCommentCustomer;
    private $subject;
    private $result;
    private $addressExtension;

    public function setUp(): void
    {
        $this->shippingCommentCustomerRepository = $this->createMock(
            ShippingCommentCustomerRepository::class
        );
        $this->shippingCommentCustomer = $this->createMock(ShippingCommentCustomer::class);
        $this->subject = $this->createMock(Subject::class);
        $this->result = $this->createMock(Result::class);
        $this->addressExtension = $this->createMock(AddressExtension::class);
    }

    protected function createNewPlugin()
    {
        return (new Plugin($this->shippingCommentCustomerRepository));
    }

    public function testAfterGetDataModel()
    {
        $plugin = $this->createNewPlugin();
        $commentText = 'some comment';

        $this->result->method('getExtensionAttributes')
            ->willReturn($this->addressExtension);

        $this->shippingCommentCustomerRepository->method('getByCustomerAddressId')
            ->willReturn($this->shippingCommentCustomer);
        $this->shippingCommentCustomer->method('getComment')->willReturn($commentText);

        $this->addressExtension->expects($this->once())->method('setCommentField')
            ->with($this->equalTo($commentText));
        $this->result->expects($this->once())->method('setExtensionAttributes')
            ->with($this->callback(function ($param) {
                $this->assertSame($this->addressExtension, $param);
                return true;
            }));

        $result = $plugin->afterGetDataModel($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }

    public function testAfterGetDataModelRepositoryThrowError()
    {
        $plugin = $this->createNewPlugin();

        $this->shippingCommentCustomerRepository->method('getByCustomerAddressId')
            ->willThrowException(new NoSuchEntityException(__('')));


        $result = $plugin->afterGetDataModel($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }
}
