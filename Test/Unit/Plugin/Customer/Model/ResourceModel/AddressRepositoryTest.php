<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Customer\Model\ResourceModel;

use Romchik38\CheckoutShippingComment\Plugin\Customer\Model\ResourceModel\AddressRepository as Plugin;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use Magento\Framework\Message\Manager;
use \Psr\Log\LoggerInterface\Proxy;
use Magento\Customer\Model\ResourceModel\AddressRepository as Subject;
use \Magento\Customer\Model\Data\Address as Result;
use \Magento\Customer\Api\Data\AddressExtension;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomer;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;

class AddressRepositoryTest extends \PHPUnit\Framework\TestCase
{
    private $ShippingCommentCustomerRepository;
    private $messageManager;
    private $logger;
    private $subject;
    private $result;
    private $addressId = '1';
    private $addressExtension;
    private $comment;
    private $address;

    public function setUp(): void
    {
        $this->ShippingCommentCustomerRepository = $this->createMock(
            ShippingCommentCustomerRepository::class
        );
        $this->messageManager = $this->createMock(Manager::class);
        $this->logger = $this->createMock(Proxy::class);
        $this->subject = $this->createMock(Subject::class);
        $this->result = $this->createMock(Result::class);
        $this->addressExtension = $this->createMock(AddressExtension::class);
        $this->comment = $this->createMock(ShippingCommentCustomer::class);
        $this->address = $this->createMock(Result::class);
    }

    private function createNewPlugin()
    {
        return (
            new Plugin(
                $this->ShippingCommentCustomerRepository,
                $this->messageManager,
                $this->logger
            )
        );
    }

    /**
     * Do nothing, because Comment field already exists
     */
    public function testAfterGetByIdCommentAlreadyExists()
    {
        $plugin = $this->createNewPlugin();

        $this->result->method('getExtensionAttributes')->willReturn($this->addressExtension);
        $this->addressExtension->method('getCommentField')->willReturn('some comment');

        $this->ShippingCommentCustomerRepository->expects($this->never())
            ->method('getByCustomerAddressId');

        $result = $plugin->afterGetById($this->subject, $this->result, $this->addressId);
        $this->assertSame($this->result, $result);
    }

    /**
     * Find a comment for provided customer address
     */
    public function testAfterGetById()
    {
        $plugin = $this->createNewPlugin();
        $commentText = 'some comment 1';

        $this->result->method('getExtensionAttributes')->willReturn($this->addressExtension);
        $this->addressExtension->method('getCommentField')->willReturn(null);

        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('getByCustomerAddressId')->with($this->equalTo($this->addressId))
            ->willReturn($this->comment);

        $this->comment->method('getComment')->willReturn($commentText);

        $this->addressExtension->expects($this->once())->method('setCommentField')
            ->with($this->equalTo($commentText));

        $this->result->expects($this->once())->method('setExtensionAttributes')
            ->with($this->callback(function ($param) {
                $this->assertSame($this->addressExtension, $param);
                return true;
            }));

        $result = $plugin->afterGetById($this->subject, $this->result, $this->addressId);
        $this->assertSame($this->result, $result);
    }

    public function testAfterGetByIdThrowError()
    {
        $plugin = $this->createNewPlugin();

        $this->result->method('getExtensionAttributes')->willReturn($this->addressExtension);
        $this->addressExtension->method('getCommentField')->willReturn(null);

        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('getByCustomerAddressId')
            ->willThrowException(new NoSuchEntityException(__('')));

        $result = $plugin->afterGetById($this->subject, $this->result, $this->addressId);
        $this->assertSame($this->result, $result);
    }

    /**
     * SAVE METHOD
     */

    public function testAfterSaveCommentFieldNotProvided()
    {
        $plugin = $this->createNewPlugin();

        $this->address->method('getExtensionAttributes')
            ->willReturn($this->addressExtension);

        $this->addressExtension->method('getCommentField')->willReturn(null);

        $this->ShippingCommentCustomerRepository->expects($this->never())
            ->method('getByCustomerAddressId');

        $result = $plugin->afterSave($this->subject, $this->result, $this->address);
        $this->assertSame($this->result, $result);
    }

    public function testAfterSave()
    {
        $plugin = $this->createNewPlugin();
        $customerAddressId = '1';
        $commentText = 'some comment save 1';

        $this->result->method('getId')->willReturn($customerAddressId);

        $this->address->method('getExtensionAttributes')
            ->willReturn($this->addressExtension);

        $this->addressExtension->method('getCommentField')->willReturn($commentText);

        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('getByCustomerAddressId')
            ->with($this->equalTo($customerAddressId))
            ->willReturn($this->comment);

        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($param) {
                $this->assertSame($this->comment, $param);
                return true;
            }));

        $result = $plugin->afterSave($this->subject, $this->result, $this->address);
        $this->assertSame($this->result, $result);
    }

    public function testAfterSaveThrowError()
    {
        $plugin = $this->createNewPlugin();
        $customerAddressId = '2';
        $commentText = 'some comment save 2';

        $this->result->method('getId')->willReturn($customerAddressId);

        $this->address->method('getExtensionAttributes')
            ->willReturn($this->addressExtension);

        $this->addressExtension->method('getCommentField')->willReturn($commentText);

        $this->ShippingCommentCustomerRepository->method('getByCustomerAddressId')
            ->willReturn($this->comment);

        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('save')
            ->willThrowException(new CouldNotSaveException(__('')));

        $result = $plugin->afterSave($this->subject, $this->result, $this->address);
        $this->assertSame($this->result, $result);
    }

    public function testAfterSaveNewComment()
    {
        $plugin = $this->createNewPlugin();
        $customerAddressId = '3';
        $commentText = 'some comment save 3';

        $this->result->method('getId')->willReturn($customerAddressId);

        $this->address->method('getExtensionAttributes')
            ->willReturn($this->addressExtension);

        $this->addressExtension->method('getCommentField')->willReturn($commentText);



        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('getByCustomerAddressId')
            ->willThrowException(new NoSuchEntityException(__('')));


        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('create')->willReturn($this->comment);

        $this->comment->method('setComment')->with($this->equalTo($commentText));
        $this->comment->method('setCustomerAddressId')->with($this->equalTo($customerAddressId));


        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($param) {
                $this->assertSame($this->comment, $param);
                return true;
            }));

        $result = $plugin->afterSave($this->subject, $this->result, $this->address);
        $this->assertSame($this->result, $result);
    }

    public function testAfterSaveNewCommentRepositoryThrowError()
    {
        $plugin = $this->createNewPlugin();
        $customerAddressId = '4';
        $commentText = 'some comment save 4';

        $this->result->method('getId')->willReturn($customerAddressId);

        $this->address->method('getExtensionAttributes')
            ->willReturn($this->addressExtension);

        $this->addressExtension->method('getCommentField')->willReturn($commentText);



        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('getByCustomerAddressId')
            ->willThrowException(new NoSuchEntityException(__('')));


        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('create')->willReturn($this->comment);

        $this->comment->method('setComment')->with($this->equalTo($commentText));
        $this->comment->method('setCustomerAddressId')->with($this->equalTo($customerAddressId));


        $this->ShippingCommentCustomerRepository->expects($this->once())
            ->method('save')
            ->willThrowException(new CouldNotSaveException(__('')));

        $result = $plugin->afterSave($this->subject, $this->result, $this->address);
        $this->assertSame($this->result, $result);
    }
}
