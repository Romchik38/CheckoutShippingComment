# Add extension attribute to checkout shipping address

## Description

Add a comment to shipping address

## Docs

[Add a new field in the address form](https://developer.adobe.com/commerce/php/tutorials/frontend/custom-checkout/add-address-field/)

## Steps to develop

1. [+] Create a component and check how it works. Deside how to save information on server side.
2. [-] Create a database table and save comment
3. [-] Deside how to show comment on frontend and backend. Create new steps.

### Step 1

1. Add the field to layout
2. Add a JS mixin to modify data submission
3. Load your mixin
4. Add field to address model
5. Access the value of the custom field on server side
6. Run CLI commands

#### 1.1 Add the field to layout

[+] create a plugin or layout
[+] declare it in the di.xml  
[+] check how it via *checkoutProvider.get('shippingAddress')*  

#### 1.2. Add a JS mixin to modify data submission

`Magento_Checkout/js/action/set-shipping-information` - component responsible for data submission.

[+] define a mixin  

#### 1.3. Load your mixin

[+] create *requirejs-config.js*

#### 1.4. Add field to address model

[+] create */etc/extension_attributes.xml*  
[+] run setup:di:compile  
[+] check getter/setter in `generated/code/Magento/Quote/Api/Data/AddressExtension.php`
[+] create order on storefront

#### 1.5. Access the value of the custom field on server side

##### 1.5.1 Shipping step

[+] create a plugin for `Magento\Checkout\Model\ShippingInformationManagement`
[+] check how it works

##### 1.5.2 Billing step

[+] check billing step

It isn't possible to use billing step, because method `savePaymentInformationAndPlaceOrder` in `PaymentInformationManagement` class doesn't take shipping address. Instead it takes billing address. Unfortunately we do not place extension attribute here.

##### 1.5.3 Decide which step to use for save comment