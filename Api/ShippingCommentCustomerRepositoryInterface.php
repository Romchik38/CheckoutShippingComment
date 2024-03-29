<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Api;

use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentCustomerInterface;
use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentCustomerSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 *  Shipping Comment Customer CRUD interface.
 * @api
 */
interface ShippingCommentCustomerRepositoryInterface
{

    /**
     * @param ShippingCommentCustomerInterface $comment
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ShippingCommentCustomerInterface $comment): bool;

    /**
     * @param int $commentId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $commentId): bool;

    /**
     * @param int $commentId
     * @return ShippingCommentCustomerInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $commentId): ShippingCommentCustomerInterface;

    /**
     * Retrieve comments matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ShippingCommentCustomerSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): ShippingCommentCustomerSearchResultsInterface;

    /**
     * SAVE comment
     * @param ShippingCommentCustomerInterface $comment
     * @return ShippingCommentCustomerInterface
     * @throws CouldNotSaveException
     */

    public function save(ShippingCommentCustomerInterface $comment): ShippingCommentCustomerInterface;

    /**
     * Create a new instance of ShippingCommentCustomerInterface
     * @return ShippingCommentCustomerInterface
     */
    public function create(): ShippingCommentCustomerInterface;

    /**
     * @param int $customerAddressId
     * @return ShippingCommentCustomerInterface
     * @throws NoSuchEntityException
     */
    public function getByCustomerAddressId(int $customerAddressId): ShippingCommentCustomerInterface;
}
