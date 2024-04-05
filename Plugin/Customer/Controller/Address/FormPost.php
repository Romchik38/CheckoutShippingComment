<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Controller\Address;

use Magento\Framework\Message\ManagerInterface;

/**
 * Add extension attributes to customer address
 * area - storefront
 * url - /customer/address/edit/id/1/
 */
class FormPost
{
    public function __construct(
        private ManagerInterface $messageManager
    ) {
    }

    /**
     * @param Magento\Customer\Controller\Address\FormPost $subject
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
        if ($isSuccess) {
            // do job





        }
        return $result;
    }
}
