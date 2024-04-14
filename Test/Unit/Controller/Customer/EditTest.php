<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Controller\Customer;

use Romchik38\CheckoutShippingComment\Controller\Customer\Edit;
use Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Controller\Result\Json;
use \Magento\Customer\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Customer\Model\ResourceModel\AddressRepository;
use \Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;

class EditTest extends TestCase
{
    public function createClasses(): array
    {
        return [
            $this->createMock(JsonFactory::class),
            $this->createMock(Session::class),
            $this->createMock(Http::class),
            $this->createMock(AddressRepository::class),
            $this->createMock(Json::class),
        ];
    }

    public function testError(): void
    {
        $controller = new Edit(...$this->createClasses());
        $message = 'some error message';

        $result = $controller->error($message);

        $this->assertSame(['error' => $message], $result);
    }

    public function testData(): void
    {
        $controller = new Edit(...$this->createClasses());
        $comment = 'some data comment';

        $result = $controller->data($comment);

        $this->assertSame(['data' => $comment], $result);
    }

    public function testExecuteIdNull()
    {
        [
            $jsonFactory,
            $session,
            $request,
            $addressRepository,
            $json
        ] = $this->createClasses();

        $controller = new Edit($jsonFactory, $session, $request, $addressRepository);

        $jsonFactory->method('create')->willReturn($json);

        $json->method('setData')->willReturnSelf();
        $json->method('setData')->with($this->equalTo(['error' => 'id expected']));

        $request->method('getParam')->willReturn(null);

        $controller->execute();
    }

    public function testExecuteIdEmpty()
    {
        [
            $jsonFactory,
            $session,
            $request,
            $addressRepository,
            $json
        ] = $this->createClasses();

        $controller = new Edit($jsonFactory, $session, $request, $addressRepository);

        $jsonFactory->method('create')->willReturn($json);

        $json->method('setData')
            ->willReturnSelf()
            ->with(self::callback(function ($message): bool {
                self::assertSame(['error' => 'id value expected'], $message);
                return true;
            }));

        $request->method('getParam')->willReturn('');

        $controller->execute();
    }

    public function testExecuteCustomerId()
    {
        [
            $jsonFactory,
            $session,
            $request,
            $addressRepository,
            $json
        ] = $this->createClasses();

        $controller = new Edit($jsonFactory, $session, $request, $addressRepository);

        $jsonFactory->method('create')->willReturn($json);

        $json->method('setData')->willReturnSelf();
        $json->method('setData')->with($this->equalTo(['error' => 'authentication required']));

        $request->method('getParam')->willReturn('100');

        $session->method('getCustomerId')->willReturn(null);

        $controller->execute();
    }

    public function testExecuteAddressIdNotFound()
    {
        [
            $jsonFactory,
            $session,
            $request,
            $addressRepository,
            $json
        ] = $this->createClasses();

        $controller = new Edit($jsonFactory, $session, $request, $addressRepository);

        $jsonFactory->method('create')->willReturn($json);

        $json->method('setData')->willReturnSelf();
        $json->method('setData')->with($this->equalTo(['error' => 'address with id 46 was not found']));

        $request->method('getParam')->willReturn('46');

        $session->method('getCustomerId')->willReturn('1');

        $addressRepository
            ->method('getById')
            ->will($this->throwException(new LocalizedException(__('some'))));

        $controller->execute();
    }

    public function testExecuteCustomerIdNotEqual()
    {
        [
            $jsonFactory,
            $session,
            $request,
            $addressRepository,
            $json
        ] = $this->createClasses();

        $controller = new Edit($jsonFactory, $session, $request, $addressRepository);

        $jsonFactory->method('create')->willReturn($json);

        $json->method('setData')->willReturnSelf();
        $json->method('setData')->with($this->equalTo(['error' => 'access denied']));

        $request->method('getParam')->willReturn('100');

        $session->method('getCustomerId')->willReturn('1');

        $addressRepository->method('getById')->willReturn(
            new class()
            {
                public function getCustomerId()
                {
                    return '2';
                }
            }
        )->with($this->equalTo('100'));

        $controller->execute();
    }

    public function testExecuteSendData()
    {
        [
            $jsonFactory,
            $session,
            $request,
            $addressRepository,
            $json
        ] = $this->createClasses();

        $controller = new Edit($jsonFactory, $session, $request, $addressRepository);

        $jsonFactory->method('create')->willReturn($json);

        $json->method('setData')->willReturnSelf();
        $json->method('setData')->with($this->equalTo(['data' => 'some comment']));

        $request->method('getParam')->willReturn('100');

        $session->method('getCustomerId')->willReturn('1');

        $addressRepository->method('getById')->willReturn(
            new class
            {
                public function getCustomerId()
                {
                    return '1';
                }
                public function getExtensionAttributes()
                {
                    return new class
                    {
                        public function getCommentField()
                        {
                            return 'some comment';
                        }
                    };
                }
            }
        );

        $controller->execute();
    }
}
