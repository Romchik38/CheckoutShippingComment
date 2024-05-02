<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Api;

use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentInterface;
use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 *  Shipping Comment CRUD interface.
 * @api
 */
interface ShippingCommentRepositoryInterface
{

    /**
     * Delete comment entity
     *
     * @param ShippingCommentInterface $comment
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ShippingCommentInterface $comment): bool;

    /**
     * Delete comment entity by provided id
     *
     * @param int $commentId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $commentId): bool;

    /**
     * Retrive a comment entity from database by provided id
     *
     * @param int $commentId
     * @return ShippingCommentInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $commentId): ShippingCommentInterface;

    /**
     * Retrieve comments matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ShippingCommentSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): ShippingCommentSearchResultsInterface;

    /**
     * Save a comment entity
     *
     * @param ShippingCommentInterface $comment
     * @return ShippingCommentInterface
     * @throws CouldNotSaveException
     */

    public function save(ShippingCommentInterface $comment): ShippingCommentInterface;

    /**
     * Create a new instance of ShippingCommentInterface
     *
     * @return ShippingCommentInterface
     */
    public function create(): ShippingCommentInterface;

    /**
     * Retrive a comment by provided quote address id
     *
     * @param int $quoteAddressId
     * @return ShippingCommentInterface
     * @throws NoSuchEntityException
     */
    public function getByQuoteAddressId(int $quoteAddressId): ShippingCommentInterface;
}
