<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Controller\Address;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Romchik38\CheckoutShippingComment\Api\ShippingCommentCustomerRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Save a customer comment
 * area - storefront
 * url - /customer/address/edit/id/1/
 */
class FormPost
{
    public function __construct(
        private ManagerInterface $messageManager,
        private RequestInterface $request,
        private ShippingCommentCustomerRepositoryInterface $shippingCommentCustomerRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param \Magento\Customer\Controller\Address\FormPost $subject
     * @param \Magento\Framework\Controller\Result\Redirect $result
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function afterExecute(
        $subject,
        $result
    ) {
        /** @var \Magento\Framework\Message\Collection $messages */
        $messages = $this->messageManager->getMessages();
        $items = $messages->getItems();
        $isSuccess = false;
        foreach ($items as $item) {
            $isSuccess = $item instanceof \Magento\Framework\Message\Success;
            if ($isSuccess) {
                break;
            }
        }
        if (!$isSuccess) {
            return $result;
        }
        // do job

        $addressIdParam = (int)$this->request->getParam('id');
        $commentParam = $this->request->getParam('comment');
        if (!$commentParam) {
            return $result;
        }

        try {
            $comment = $this->shippingCommentCustomerRepository->getByCustomerAddressId($addressIdParam);
            $comment->setComment($commentParam);
            try {
                $this->shippingCommentCustomerRepository->save($comment);
            } catch (CouldNotSaveException $e) {
                $this->logger->critical('Error while updating shipping comment customer with customer address id: ' . $addressIdParam . ' ( request from customer/address/edit )');
            }
        } catch (NoSuchEntityException $e) {
            $this->logger->critical('Comment for customer address id: ' . $addressIdParam . ' doesn\'t exist ( request from customer/address/edit )');
        }

        return $result;
    }
}
