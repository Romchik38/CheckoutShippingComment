<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Model;

use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomer;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer as ShippingCommentCustomerResource;
use \Magento\Framework\Model\Context;
use \Magento\Framework\Registry;

class ShippingCommentCustomerTest extends \PHPUnit\Framework\TestCase
{
    private $context;
    private $registry;
    private $shippingCommentCustomerResource;

    public function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->registry = $this->createMock(Registry::class);
        $this->shippingCommentCustomerResource = $this->createMock(ShippingCommentCustomerResource::class);
    }

    public function testGetSetMethods(): void
    {
        $comment = new ShippingCommentCustomer(
            $this->context,
            $this->registry,
            $this->shippingCommentCustomerResource
        );

        $comment->setId('1');
        $this->assertSame('1', $comment->getId());

        $comment->setCustomerAddressId(1);
        $this->assertSame(1, $comment->getCustomerAddressId());

        $comment->setComment('some comment');
        $this->assertSame('some comment', $comment->getComment());
    }
}
