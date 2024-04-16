<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Model;

use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentFactory;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment\CollectionFactory;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment as ShippingCommentResource;
use \Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentSearchResultsFactory;
use \Romchik38\CheckoutShippingComment\Model\ShippingCommentSearchResults;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\FilterFactory;
use \Magento\Framework\Api\Filter;
use Romchik38\CheckoutShippingComment\Model\ShippingComment;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class ShippingCommentRepositoryTest extends \PHPUnit\Framework\TestCase
{

    private $ShippingCommentFactory;
    private $collectionFactory;
    private $ShippingCommentResource;
    private $collectionProcessor;
    private $ShippingCommentSearchResultsFactory;
    private $searchCriteriaBuilder;
    private $filterFactory;
    private $collection;

    public function setUp(): void
    {
        $this->ShippingCommentFactory = $this->createMock(ShippingCommentFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->ShippingCommentResource = $this->createMock(ShippingCommentResource::class);
        $this->collectionProcessor = $this->createMock(CollectionProcessor::class);
        $this->ShippingCommentSearchResultsFactory = $this->createMock(ShippingCommentSearchResultsFactory::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->filterFactory = $this->createMock(FilterFactory::class);
        $this->collection = $this->createMock(Collection::class);
    }

    public function createCommentRepository(): ShippingCommentRepository
    {
        return (
            new ShippingCommentRepository(
                $this->ShippingCommentFactory,
                $this->collectionFactory,
                $this->ShippingCommentResource,
                $this->collectionProcessor,
                $this->ShippingCommentSearchResultsFactory,
                $this->searchCriteriaBuilder,
                $this->filterFactory
            )
        );
    }
    public function testDelete()
    {
        $ShippingCommentRepository = $this->createCommentRepository();

        $comment = $this->createMock(ShippingComment::class);
        $this->ShippingCommentResource->method('delete')
            ->with($this->callback(function ($obj) use ($comment) {
                $this->assertSame($comment, $obj);
                return true;
            }))
            ->willReturn('true');

        $result = $ShippingCommentRepository->delete($comment);
        $this->assertSame(true, $result);
    }

    public function testDeleteThrowError()
    {
        $ShippingCommentRepository = $this->createCommentRepository();

        $comment = $this->createMock(ShippingComment::class);
        $this->ShippingCommentResource->method('delete')
            ->will($this->throwException(new \Exception('')));

        $this->expectException(CouldNotDeleteException::class);
        $ShippingCommentRepository->delete($comment);
    }

    public function testGetById()
    {
        $ShippingCommentRepository = $this->createCommentRepository();
        $commentId = 20;
        $idFieldName = 'entity_id';
        $comment = $this->createMock(ShippingComment::class);

        $this->collectionFactory->method('create')->willReturn($this->collection);

        $this->ShippingCommentResource
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

        $result = $ShippingCommentRepository->getById($commentId);
        $this->assertSame($comment, $result);
    }

    public function testGetList()
    {
        $ShippingCommentRepository = $this->createCommentRepository();
        $items = ['1', '2', '3'];

        $this->collectionFactory->method('create')->willReturn($this->collection);
        $this->collection->method('getItems')->willReturn($items);

        $searchResults = $this->createMock(ShippingCommentSearchResults::class);
        $this->ShippingCommentSearchResultsFactory->method('create')->willReturn($searchResults);

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
        $result = $ShippingCommentRepository->getList($searchCriteria);

        $this->assertSame($searchResults, $result);
    }

    public function testSave()
    {
        $ShippingCommentRepository = $this->createCommentRepository();
        $comment = $this->createMock(ShippingComment::class);

        $this->ShippingCommentResource
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function ($paramComment) use ($comment) {
                    $this->assertSame($comment, $paramComment);
                    return true;
                })
            );

        $ShippingCommentRepository->save($comment);
    }

    public function testSaveThrowError()
    {
        $ShippingCommentRepository = $this->createCommentRepository();
        $comment = $this->createMock(ShippingComment::class);

        $this->ShippingCommentResource
            ->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \Exception('')));

        $this->expectException(CouldNotSaveException::class);
        $ShippingCommentRepository->save($comment);
    }

    public function testCreate()
    {
        $ShippingCommentRepository = $this->createCommentRepository();
        $comment = $this->createMock(ShippingComment::class);

        $this->ShippingCommentFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($comment);

        $result = $ShippingCommentRepository->create();
        $this->assertSame($comment, $result);
    }

    public function testGetByQuoteAddressId()
    {
        $ShippingCommentRepository = $this->createCommentRepository();
        $quoteAddressId = 1;

        $comment = $this->createMock(ShippingComment::class);
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

        $searchResults = $this->createMock(ShippingCommentSearchResults::class);
        $this->ShippingCommentSearchResultsFactory->method('create')->willReturn($searchResults);
        $searchResults
            ->expects($this->once())
            ->method('getItems')
            ->willReturn($comments);

        $result = $ShippingCommentRepository->getByQuoteAddressId($quoteAddressId);

        $this->assertSame($comment, $result);
    }

    public function testGetByQuoteAddressIdThrowError()
    {
        $ShippingCommentRepository = $this->createCommentRepository();
        $quoteAddressId = 1;

        $comment = $this->createMock(ShippingComment::class);
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

        $searchResults = $this->createMock(ShippingCommentSearchResults::class);
        $this->ShippingCommentSearchResultsFactory->method('create')->willReturn($searchResults);
        $searchResults
            ->expects($this->once())
            ->method('getItems')
            ->willReturn($comments);

        $this->expectException(NoSuchEntityException::class);
        $result = $ShippingCommentRepository->getByQuoteAddressId($quoteAddressId);
    }
}
