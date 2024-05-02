<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer;

use Magento\Customer\Api\Data\AddressExtensionInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\AddressExtensionFactory;

class AddressAttributesLoad
{
    /**
     * @param \Magento\Customer\Api\Data\AddressExtensionFactory $extensionFactory
     */
    public function __construct(
        private AddressExtensionFactory $extensionFactory
    ) {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Creates extension
     *
     * @param \Magento\Customer\Api\Data\AddressInterface $entity
     * @param \Magento\Customer\Api\Data\AddressExtensionInterface|null $extension
     */
    public function afterGetExtensionAttributes(
        AddressInterface $entity,
        AddressExtensionInterface $extension = null
    ) {
        if ($extension === null) {
            $extension = $this->extensionFactory->create();
        }
        return $extension;
    }
}
