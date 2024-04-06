<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Controller\Customer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Controller\Result\Json;
use \Magento\Customer\Model\Session;

class Edit implements HttpGetActionInterface
{

    public function __construct(
        private JsonFactory $jsonFactory,
        private Session $customerSession
    ) {
    }

    public function execute(): Json
    {

        $customerId = $this->customerSession->getCustomerId();

        $json = $this->jsonFactory->create();
        $arr = [1, 2, 3];
        $data = json_encode($arr);
        $json->setData($data);

        return $json;
    }
}
