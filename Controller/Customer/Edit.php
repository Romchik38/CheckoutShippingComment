<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Controller\Customer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Controller\Result\Json;
use \Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;

class Edit implements HttpGetActionInterface
{

    public function __construct(
        private JsonFactory $jsonFactory,
        private Session $customerSession,
        private RequestInterface $request,
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
        if (!$addressIdParam) {
            return $json->setData($this->error('id expected'));
        }
        if (strlen($addressIdParam) === 0) {
            return $json->setData($this->error('id expected'));
        }


        $customerId = $this->customerSession->getCustomerId();

        $arr = [1, 2, 3];
        $data = json_encode($arr);
        $json->setData($data);

        return $json;
    }
}
