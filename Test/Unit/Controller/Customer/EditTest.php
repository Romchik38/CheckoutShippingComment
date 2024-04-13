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
            $addressRepositor,
            $json
        ] = $this->createClasses();

        $controller = new Edit($jsonFactory, $session, $request, $addressRepositor);

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
            $addressRepositor,
            $json
        ] = $this->createClasses();

        $controller = new Edit($jsonFactory, $session, $request, $addressRepositor);

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
}
