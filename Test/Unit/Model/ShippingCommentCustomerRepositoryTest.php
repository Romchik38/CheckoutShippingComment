<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Model;

use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerFactory;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer\CollectionFactory;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer as ShippingCommentCustomerResource;
use \Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerSearchResultsFactory;
use \Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerSearchResults;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\FilterFactory;
use \Magento\Framework\Api\Filter;
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
    private $collection;

    public function setUp(): void
    {
        $this->shippingCommentCustomerFactory = $this->createMock(ShippingCommentCustomerFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->shippingCommentCustomerResource = $this->createMock(ShippingCommentCustomerResource::class);
        $this->collectionProcessor = $this->createMock(CollectionProcessor::class);
        $this->shippingCommentCustomerSearchResultsFactory = $this->createMock(ShippingCommentCustomerSearchResultsFactory::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->filterFactory = $this->createMock(FilterFactory::class);
        $this->collection = $this->createMock(Collection::class);
    }

    public function createCommentRepository(): ShippingCommentCustomerRepository
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
        $shippingCommentCustomerRepository = $this->createCommentRepository();

        $comment = $this->createMock(ShippingCommentCustomer::class);
        $this->shippingCommentCustomerResource->method('delete')
            ->with($this->callback(function ($obj) use ($comment) {
                $this->assertSame($comment, $obj);
                return true;
            }))
            ->willReturn('true');

        $result = $shippingCommentCustomerRepository->delete($comment);
        $this->assertSame(true, $result);
    }

    public function testDeleteThrowError()
    {
        $shippingCommentCustomerRepository = $this->createCommentRepository();

        $comment = $this->createMock(ShippingCommentCustomer::class);
        $this->shippingCommentCustomerResource->method('delete')
            ->will($this->throwException(new \Exception('')));

        $this->expectException(CouldNotDeleteException::class);
        $shippingCommentCustomerRepository->delete($comment);
    }

    public function testGetById()
    {
        $shippingCommentCustomerRepository = $this->createCommentRepository();
        $commentId = 20;
        $idFieldName = 'entity_id';
        $comment = $this->createMock(ShippingCommentCustomer::class);

        $this->collectionFactory->method('create')->willReturn($this->collection);

        $this->shippingCommentCustomerResource
            ->expects($this->once())
            ->method('getIdFieldName')
            ->willReturn($idFieldName);

        $this->collection
            ->expects($this->once())
            ->method('addFieldToFilter')
            ->with(
                $this->callback(function ($paramFieldId)
                use ($idFieldName) {
                    $this->assertSame($idFieldName, $paramFieldId);
                    return true;
                }),
                $this->callback(function ($paramCommentId)
                use ($commentId) {
                    $this->assertSame($commentId, $paramCommentId);
                    return true;
                })
            );

        $this->collection
            ->expects($this->once())
            ->method('load');

        $this->collection
            ->expects($this->once())
            ->method('getSize')
            ->willReturn(1);

        $this->collection
            ->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($comment);

        $result = $shippingCommentCustomerRepository->getById($commentId);
        $this->assertSame($comment, $result);
    }

    public function testGetList()
    {
        $shippingCommentCustomerRepository = $this->createCommentRepository();
        $items = ['1', '2', '3'];

        $this->collectionFactory->method('create')->willReturn($this->collection);
        $this->collection->method('getItems')->willReturn($items);

        $searchResults = $this->createMock(ShippingCommentCustomerSearchResults::class);
        $this->shippingCommentCustomerSearchResultsFactory->method('create')->willReturn($searchResults);

        $searchResults
            ->expects($this->once())
            ->method('setItems')
            ->with(
                $this->callback(function ($paramItems)
                use ($items) {
                    $this->assertSame($items, $paramItems);
                    return true;
                })
            );

        $searchCriteria = $this->createMock(SearchCriteria::class);
        $result = $shippingCommentCustomerRepository->getList($searchCriteria);

        $this->assertSame($searchResults, $result);
    }

    public function testSave()
    {
        $shippingCommentCustomerRepository = $this->createCommentRepository();
        $comment = $this->createMock(ShippingCommentCustomer::class);

        $this->shippingCommentCustomerResource
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function ($paramComment) use ($comment) {
                    $this->assertSame($comment, $paramComment);
                    return true;
                })
            );

        $shippingCommentCustomerRepository->save($comment);
    }

    public function testSaveThrowError()
    {
        $shippingCommentCustomerRepository = $this->createCommentRepository();
        $comment = $this->createMock(ShippingCommentCustomer::class);

        $this->shippingCommentCustomerResource
            ->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \Exception('')));

        $this->expectException(CouldNotSaveException::class);
        $shippingCommentCustomerRepository->save($comment);
    }

    public function testCreate()
    {
        $shippingCommentCustomerRepository = $this->createCommentRepository();
        $comment = $this->createMock(ShippingCommentCustomer::class);

        $this->shippingCommentCustomerFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($comment);

        $result = $shippingCommentCustomerRepository->create();
        $this->assertSame($comment, $result);
    }

    public function testGetByCustomerAddressId()
    {
        $shippingCommentCustomerRepository = $this->createCommentRepository();
        $customerAddressId = 1;

        $comment = $this->createMock(ShippingCommentCustomer::class);
        $comments = [$comment];

        $filter = $this->createMock(Filter::class);
        $filter->method('setField')->willReturn($filter);
        $filter->method('setValue')->willReturn($filter);
        $filter->method('setConditionType')->willReturn($filter);
        $this->filterFactory->method('create')->willReturn($filter);

        $this->collectionFactory->method('create')->willReturn($this->collection);
        $this->collection->method('getItems')->willReturn([]);

        $searchCriteria = $this->createMock(SearchCriteria::class);
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteria);

        $searchResults = $this->createMock(ShippingCommentCustomerSearchResults::class);
        $this->shippingCommentCustomerSearchResultsFactory->method('create')->willReturn($searchResults);
        $searchResults
            ->expects($this->once())
            ->method('getItems')
            ->willReturn($comments);

        $result = $shippingCommentCustomerRepository->getByCustomerAddressId($customerAddressId);

        $this->assertSame($comment, $result);
    }

    public function testGetByCustomerAddressIdThrowError()
    {
        $shippingCommentCustomerRepository = $this->createCommentRepository();
        $customerAddressId = 1;

        $comment = $this->createMock(ShippingCommentCustomer::class);
        $comments = [];

        $filter = $this->createMock(Filter::class);
        $filter->method('setField')->willReturn($filter);
        $filter->method('setValue')->willReturn($filter);
        $filter->method('setConditionType')->willReturn($filter);
        $this->filterFactory->method('create')->willReturn($filter);

        $this->collectionFactory->method('create')->willReturn($this->collection);
        $this->collection->method('getItems')->willReturn([]);

        $searchCriteria = $this->createMock(SearchCriteria::class);
        $this->searchCriteriaBuilder->method('create')->willReturn($searchCriteria);

        $searchResults = $this->createMock(ShippingCommentCustomerSearchResults::class);
        $this->shippingCommentCustomerSearchResultsFactory->method('create')->willReturn($searchResults);
        $searchResults
            ->expects($this->once())
            ->method('getItems')
            ->willReturn($comments);

        $this->expectException(NoSuchEntityException::class);
        $result = $shippingCommentCustomerRepository->getByCustomerAddressId($customerAddressId);
    }
}
