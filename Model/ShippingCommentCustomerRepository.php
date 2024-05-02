<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface as CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterFactory;
use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentCustomerInterface;
use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentCustomerSearchResultsInterface;
use Romchik38\CheckoutShippingComment\Api\ShippingCommentCustomerRepositoryInterface;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer\CollectionFactory;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer as ShippingCommentCustomerResource;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerSearchResultsFactory;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * ShippingCommentCustomer Repository
 *
 * CRUD interface, create entity, getList
 */
class ShippingCommentCustomerRepository implements ShippingCommentCustomerRepositoryInterface
{
    
    /**
     * Method __construct
     *
     * @param ShippingCommentCustomerFactory $commentFactory
     * @param ShippingCommentCustomer\CollectionFactory $collectionFactory
     * @param ShippingCommentCustomerResource $commentResource
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param ShippingCommentCustomerSearchResultsFactory $commentSearchResultsFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterFactory $filterFactory
     * @return void
     */
    public function __construct(
        private ShippingCommentCustomerFactory $commentFactory,
        private CollectionFactory $collectionFactory,
        private ShippingCommentCustomerResource      $commentResource,
        private CollectionProcessor $collectionProcessor,
        private ShippingCommentCustomerSearchResultsFactory $commentSearchResultsFactory,
        private SearchCriteriaBuilder   $searchCriteriaBuilder,
        private FilterFactory           $filterFactory
    ) {
    }

    /**
     * Delete comment entity from database
     *
     * @param ShippingCommentCustomerInterface $comment
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ShippingCommentCustomerInterface $comment): bool
    {
        try {
            $this->commentResource->delete($comment);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }
        return true;
    }

    /**
     * Delete comment entity from database by provided id
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
     * Load Shipping Comment Customer data by given Identity
     *
     * @param int $commentId
     * @return ShippingCommentCustomerInterface
     */
    public function getById(int $commentId): ShippingCommentCustomerInterface
    {
        /** @var \Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer\Collection $collection */
        $collection = $this->collectionFactory->create();

        $idFieldName = $this->commentResource->getIdFieldName();
        $collection->addFieldToFilter($idFieldName, $commentId);
        $collection->load();
        if ($collection->getSize() === 0) {
            throw new NoSuchEntityException(__(
                'The Shipping Comment Customer with the "%1" ID doesn\'t exist.',
                $commentId
            ));
        }
        return $collection->getFirstItem();
    }

    /**
     * Get list of comments by provided search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ShippingCommentCustomerSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): ShippingCommentCustomerSearchResultsInterface
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
     * Method save
     *
     * @param ShippingCommentCustomerInterface $comment
     * @throws CouldNotSaveException
     * @return ShippingCommentCustomerInterface
     */
    public function save(ShippingCommentCustomerInterface $comment): ShippingCommentCustomerInterface
    {
        try {
            $this->commentResource->save($comment);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $comment;
    }
    
    /**
     * Method create
     *
     * @return ShippingCommentCustomerInterface
     */
    public function create(): ShippingCommentCustomerInterface
    {
        return $this->commentFactory->create();
    }

    /**
     * Get comment entity by provided Customer Address Id
     *
     * @param int $customerAddressId
     * @return ShippingCommentCustomerInterface
     * @throws NoSuchEntityException
     */
    public function getByCustomerAddressId(int $customerAddressId): ShippingCommentCustomerInterface
    {
        $filter = $this->filterFactory->create();
        $filter
            ->setField('customer_address_id')
            ->setValue($customerAddressId)
            ->setConditionType('eq');
        $this->searchCriteriaBuilder->addFilters([$filter]);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $comments = $this->getList($searchCriteria)->getItems();
        if (count($comments) === 0) {
            throw new NoSuchEntityException(__(
                'The Shipping Comment Customer with the Customer Address Id "%1" doesn\'t exist.',
                $customerAddressId
            ));
        } else {
            $comment = array_shift($comments);
            return $comment;
        }
    }
}
