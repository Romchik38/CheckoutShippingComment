<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Model;

use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerFactory;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer\CollectionFactory;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer as ShippingCommentCustomerResource;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerSearchResultsFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterFactory;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomer;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class ShippingCommentCustomerRepositoryTest extends \PHPUnit\Framework\TestCase
{

    private $shippingCommentCustomerFactory;
    private $collectionFactory;
    private $shippingCommentCustomerResource;
    private $collectionProcessor;
    private $shippingCommentCustomerSearchResultsFactory;
    private $searchCriteriaBuilder;
    private $filterFactory;

    public function setUp(): void
    {
        $this->shippingCommentCustomerFactory = $this->createMock(ShippingCommentCustomerFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->shippingCommentCustomerResource = $this->createMock(ShippingCommentCustomerResource::class);
        $this->collectionProcessor = $this->createMock(CollectionProcessor::class);
        $this->shippingCommentCustomerSearchResultsFactory = $this->createMock(ShippingCommentCustomerSearchResultsFactory::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->filterFactory = $this->createMock(FilterFactory::class);
    }

    public function createComment(): ShippingCommentCustomerRepository
    {
        return (
            new ShippingCommentCustomerRepository(
                $this->shippingCommentCustomerFactory,
                $this->collectionFactory,
                $this->shippingCommentCustomerResource,
                $this->collectionProcessor,
                $this->shippingCommentCustomerSearchResultsFactory,
                $this->searchCriteriaBuilder,
                $this->filterFactory
            )
        );
    }
    public function testDelete()
    {
        $shippingCommentCustomerRepository = $this->createComment();

        $comment = $this->createMock(ShippingCommentCustomer::class);
        $this->shippingCommentCustomerResource->method('delete')
            ->with($this->callback(function ($obj) use ($comment) {
                $this->assertSame($comment, $obj);
                return true;
            }))
            ->willReturn('true');

        $shippingCommentCustomerRepository->delete($comment);
    }

    public function testDeleteThrowError()
    {
        $shippingCommentCustomerRepository = $this->createComment();

        $comment = $this->createMock(ShippingCommentCustomer::class);
        $this->shippingCommentCustomerResource->method('delete')
            ->will($this->throwException(new \Exception('')));

        $this->expectException(CouldNotDeleteException::class);
        $shippingCommentCustomerRepository->delete($comment);
    }
}
