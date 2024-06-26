<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface as CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterFactory;
use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentInterface;
use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentSearchResultsInterface;
use Romchik38\CheckoutShippingComment\Api\ShippingCommentRepositoryInterface;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment\CollectionFactory;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment as ShippingCommentResource;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentSearchResultsFactory;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class ShippingCommentRepository implements ShippingCommentRepositoryInterface
{

    /**
     * @param \Romchik38\CheckoutShippingComment\Model\ShippingCommentFactory $commentFactory
     * @param CollectionFactory $collectionFactory
     * @param \Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment $commentResource
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Romchik38\CheckoutShippingComment\Model\ShippingCommentSearchResultsFactory $commentSearchResultsFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterFactory $filterFactory
     */
    public function __construct(
        private ShippingCommentFactory $commentFactory,
        private CollectionFactory $collectionFactory,
        private ShippingCommentResource      $commentResource,
        private CollectionProcessor $collectionProcessor,
        private ShippingCommentSearchResultsFactory $commentSearchResultsFactory,
        private SearchCriteriaBuilder   $searchCriteriaBuilder,
        private FilterFactory           $filterFactory
    ) {
    }

    /**
     * Delete comment entity
     *
     * @param ShippingCommentInterface $comment
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ShippingCommentInterface $comment): bool
    {
        try {
            $this->commentResource->delete($comment);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }
        return true;
    }

    /**
     * Delete comment entity by provided id
     *
     * @param int $commentId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $commentId): bool
    {
        return $this->delete($this->getById($commentId));
    }

    /**
     * Load Shipping Comment data by given Identity
     *
     * @param int $commentId
     * @return ShippingCommentInterface
     */
    public function getById(int $commentId): ShippingCommentInterface
    {
        /** @var \Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment\Collection $collection */
        $collection = $this->collectionFactory->create();

        $idFieldName = $this->commentResource->getIdFieldName();
        $collection->addFieldToFilter($idFieldName, $commentId);
        $collection->load();
        if ($collection->getSize() === 0) {
            throw new NoSuchEntityException(__('The Shipping Comment with the "%1" ID doesn\'t exist.', $commentId));
        }
        return $collection->getFirstItem();
    }

    /**
     * Retrive a list of comments by provided search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ShippingCommentSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): ShippingCommentSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->commentSearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Saves comment entity
     *
     * @param ShippingCommentInterface $comment
     * @return ShippingCommentInterface
     */
    public function save(ShippingCommentInterface $comment): ShippingCommentInterface
    {
        try {
            $this->commentResource->save($comment);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $comment;
    }

    /**
     * Creates a new comment entity
     *
     * @return ShippingCommentInterface
     */
    public function create(): ShippingCommentInterface
    {
        return $this->commentFactory->create();
    }

    /**
     * Retrives a comment entity by provided Quote Address Id
     *
     * @param int $quoteAddressId
     * @return ShippingCommentInterface
     * @throws NoSuchEntityException
     */
    public function getByQuoteAddressId(int $quoteAddressId): ShippingCommentInterface
    {
        $filter = $this->filterFactory->create();
        $filter
            ->setField('quote_address_id')
            ->setValue($quoteAddressId)
            ->setConditionType('eq');
        $this->searchCriteriaBuilder->addFilters([$filter]);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $comments = $this->getList($searchCriteria)->getItems();
        if (count($comments) === 0) {
            throw new NoSuchEntityException(__(
                'The Shipping Comment with the Quote Address Id "%1" doesn\'t exist.',
                $quoteAddressId
            ));
        } else {
            $comment = array_shift($comments);
            return $comment;
        }
    }
}
