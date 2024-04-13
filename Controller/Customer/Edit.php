<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Controller\Customer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Controller\Result\Json;
use \Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use \Magento\Framework\Exception\LocalizedException;


/**
 * rest api for customer address
 * area - storefront
 * url - /customer/address/edit/id/1
 * 
 * takes id from get request 
 * return a json object:
 *      {error: "text"}
 *          or
 *      {data: "text"}
 */
class Edit implements HttpGetActionInterface
{

    public function __construct(
        private JsonFactory $jsonFactory,
        private Session $customerSession,
        private RequestInterface $request,
        private AddressRepositoryInterface $addressRepository,
    ) {
    }

    public function error(string $message)
    {
        return ['error' => $message];
    }

    public function data(string $comment)
    {
        return ['data' => $comment];
    }

    public function execute(): Json
    {
        $json = $this->jsonFactory->create();

        $addressIdParam = $this->request->getParam('id');
        if ($addressIdParam === null) {
            return $json->setData($this->error('id expected'));
        }
        if (strlen($addressIdParam) === 0) {
            return $json->setData($this->error('id value expected'));
        }

        // customer authentication check
        $customerId = $this->customerSession->getCustomerId();
        if (!$customerId) {
            return $json->setData($this->error('authentication required'));
        }
        // customer authorization check
        try {
            /** @var \Magento\Customer\Api\Data\AddressInterface $address */
            $address = $this->addressRepository->getById((int)$addressIdParam);
            $customerAddressId = $address->getCustomerId();
            if ($customerId !== $customerAddressId) {
                return $json->setData($this->error('access denied'));
            }
            // get comment
            $extensionAttributes = $address->getExtensionAttributes();
            $commentField = $extensionAttributes->getCommentField();
            if (!$commentField) {
                $commentField = '';
            }
        } catch (LocalizedException) {
            return $json->setData($this->error('address with id ' . $addressIdParam . ' was not found'));
        }
        // success ( send comment )
        return $json->setData($this->data($commentField));
    }
}
