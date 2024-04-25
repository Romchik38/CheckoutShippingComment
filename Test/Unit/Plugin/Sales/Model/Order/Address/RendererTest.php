<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Sales\Model\Order\Address;

use Romchik38\CheckoutShippingComment\Plugin\Sales\Model\Order\Address\Renderer as Plugin;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Escaper;
use \Magento\Sales\Model\Order\Address\Renderer as Subject;
use \Magento\Sales\Model\Order\Address;
use Romchik38\CheckoutShippingComment\Model\ShippingComment;

class RendererTest extends \PHPUnit\Framework\TestCase
{
    private $shippingCommentRepository;
    private $searchCriteriaBuilder;
    private $filterFactory;
    private $escaper;
    private $subject;
    private $address;
    private $shippingComment;

    public function setUp(): void
    {
        $this->shippingCommentRepository = $this->createMock(
            ShippingCommentRepository::class
        );
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->filterFactory = $this->createMock(FilterFactory::class);
        $this->escaper = $this->createMock(Escaper::class);
        $this->subject = $this->createMock(Subject::class);
        $this->address = $this->createMock(Address::class);
        $this->shippingComment = $this->createMock(ShippingComment::class);
    }

    protected function createNewPlugin()
    {
        return (new Plugin(
            $this->shippingCommentRepository,
            $this->searchCriteriaBuilder,
            $this->filterFactory,
            $this->escaper,
        ));
    }

    /**
     * Do nothing, because $result is null
     */
    public function testAfterFormatResultNull()
    {
        $plugin = $this->createNewPlugin();
        $result = $plugin->afterFormat($this->subject, null, $this->address);
        $this->assertSame(null, $result);
    }

    /**
     * Do nothing
     * Address type is not 'shipping'
     */
    public function testAfterFormatOnlyShipping()
    {
        $plugin = $this->createNewPlugin();
        $resultText = 'some string';
        $addressType = 'billing';

        $this->address->method('getAddressType')->willReturn($addressType);

        $this->shippingCommentRepository->expects($this->never())
            ->method('getByQuoteAddressId');

        $result = $plugin->afterFormat($this->subject, $resultText, $this->address);
        $this->assertSame($resultText, $result);
    }

    public function testAfterFormat()
    {
        $plugin = $this->createNewPlugin();
        $resultText = 'some string 1';
        $addressType = 'shipping';
        $quoteAddressId = 0;
        $commentText = 'some comment text 1';

        $this->address->method('getAddressType')->willReturn($addressType);

        /**
         * Method getQuoteAddressId can't be specified, because it not present:
         *  $this->address->method('getQuoteAddressId')->willReturn($quoteAddressId);
         * 
         * That is why $quoteAddressId is set to 0 in the test
         *  ( null will be converted to 0 )
         */
        $this->shippingCommentRepository->expects($this->once())
            ->method('getByQuoteAddressId')
            ->with($this->equalTo($quoteAddressId))
            ->willReturn($this->shippingComment);

        $this->shippingComment->expects($this->once())
            ->method('getComment')
            ->willReturn($commentText);

        $this->escaper->expects($this->once())->method('escapeHtml')
            ->with($this->equalTo($commentText));

        $result = $plugin->afterFormat($this->subject, $resultText, $this->address);
        $this->assertNotEquals($resultText, $result);
    }

    /**
     * Repository throw an error
     */
    public function testAfterFormatRepositoryThrowError()
    {
        $plugin = $this->createNewPlugin();
        $resultText = 'some string 1';
        $addressType = 'shipping';

        $this->address->method('getAddressType')->willReturn($addressType);

        $this->shippingCommentRepository->expects($this->once())
            ->method('getByQuoteAddressId')
            ->willThrowException(new NoSuchEntityException(__('')));

        $result = $plugin->afterFormat($this->subject, $resultText, $this->address);
        $this->assertEquals($resultText, $result);
    }
}
