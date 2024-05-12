# Add a comment to shipping address

## Description

Magento 2 module adds a comment to checkout shipping address form.

## Features

1. [*shipping step* - a comment field is displayed for guests and customers ( with new address )](#1)
2. [*shipping step* - the comment displayed for existing customer address](#2)
3. [*shipping step* - in address list added *edit* link for each customer address](#3)
4. [*address book* - the comment shown in Default Addresses section](#4)
5. [*address book* the comment field is added for new or existing customer address](#5)
6. [*admin* - the comment is displayed on the Order page ( Sales => Orders => click View on any Order => check Shipping Address )](#6)
7. [*admin* - there are some comment settings](#7)

## Status

The Module is working, testing it. Last preperance.

## Versions

* 1.0.0-beta1   Magento 2.4.7   PHP 8.2.17

## <a name="1"></a> 1. Comment field is displayed for guests and customers ( with new address )

Area - *shipping step* 

### Field is displayed for guests
- ![shipping step](./Doc/01-1-shipping-step.png)  
- ![billing step](./Doc/01-2-billing-step.png)  

### Field is displayed for customers with new address
- ![new address](./Doc/01-3-customer-new-address.png)
- ![new address](./Doc/01-4-customer-new-address.png)

## <a name="2"></a> 2. The comment is displayed for existing customer address

Area - *shipping step*

Saved comment is displayed in address fields list. Customer created the address manually or already placed order.

- ![existing address](./Doc/02-1-customer-existing-address.png)

## <a name="3"></a> 3. In the address list added edit link for each customer address

Area - *shipping step*

*Edit* link to customer edit address form

- ![edit link](./Doc/03-1-customer-address-edit-link.png)
- ![edit from](./Doc/03-2-customer-address-edit-form.png)

## <a name="4"></a> 4. The comment shown in the Default Addresses section

Area - *address book*

- ![default address](./Doc/04-1-default-address.png)

## <a name="5"></a> 5. The comment field is added for new or existing customer address

Area - *address book*

- ![existing customer address](./Doc/05-1-existing-customer-address.png)
- ![existing customer address](./Doc/05-2-new-customer-address.png)

## <a name="6"></a> 6. The comment is displayed on the Order page 

Area - *admin*

Admin path - Sales => Orders => click View on any Order => check Shipping Address.

- ![admin order view](./Doc/06-1-admin-order-view.png)

## <a name="7"></a> 7. There are some comment settings

Area - *admin*

- ![admin order view](./Doc/07-1-admin-comment-settings.png)

### Field sort order on checkout page

Change field position in the address fileds list