<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Model;

use Romchik38\CheckoutShippingComment\Model\ShippingComment;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment as ShippingCommentResource;
use \Magento\Framework\Model\Context;
use \Magento\Framework\Registry;
use PHPUnit\Framework\TestCase;

class ShippingCommentTest extends TestCase
{
    private $context;
    private $registry;
    private $data = [];
    private $shippingCommentResource;

    public function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->registry = $this->createMock(Registry::class);
        $this->shippingCommentResource = $this->createMock(ShippingCommentResource::class);
    }

    public function testGetMethods()
    {
        $comment = new ShippingComment(
            $this->context,
            $this->registry,
            $this->shippingCommentResource
        );

        $comment->setId('1');
        $this->assertSame('1', $comment->getId());

        $comment->setQuoteAddressId(1);
        $this->assertSame(1, $comment->getQuoteAddressId());

        $comment->setComment('some comment');
        $this->assertSame('some comment', $comment->getComment());
    }
}
