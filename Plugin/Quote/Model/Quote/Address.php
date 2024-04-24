<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Quote\Model\Quote;

/**
 * Move a comment from Quote extension attributes to
 * Customer extension attributes
 * while app saving new customer address 
 * before placing an order
 * 
 * Works only for customer
 * 
 * area - storefront
 * url - checkout/index/index
 */
class Address
{
  /**
   * @param \Magento\Quote\Model\Quote\Address $subject,
   * @param \Magento\Customer\Model\Data\Address $result
   * @return \Magento\Customer\Model\Data\Address
   */
  public function afterExportCustomerAddress($subject, $result)
  {
    /** @var \Magento\Quote\Api\Data\AddressExtension */
    $extensionAttributes = $subject->getExtensionAttributes();
    $commentField = $extensionAttributes->getCommentField();
    if ($commentField !== null) {
      /** @var \Magento\Customer\Api\Data\AddressExtension */
      $resultExtensionAttributes = $result->getExtensionAttributes();
      $resultExtensionAttributes->setCommentField($commentField);
    }
    return $result;
  }
}
