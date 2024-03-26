# Tasks

## Steps to develop

1. [+] Create a component and check how it works. Deside how to save information on server side.
2. [-] Create a database table and save comment ( for guests and customer with new addresses )
3. [-] Deside how to show comment on frontend and backend. Create new steps.
4. [?] add comment for address book

### [+] Step 1

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

### [-] Step 2 Create a database table and save comment ( for guests and customer with new addresses )

1. [+] Create a db_schema.xml  
2. [+] create a sql query to select order + quote + comment  
3. [+] check the query ( create an order )  
4. [-] create a comment repository  

#### 2.1 Create a db_schema.xml

[doc](https://developer.adobe.com/commerce/php/development/components/declarative-schema/configuration/)

- entity_id             *comment id*, *autoincrement*, *pk*
- quote_address_id      *address_id*, *fk*, *ondelete cascade*
- comment               *varchar*, *255*, *not null*

1. [+] create db_schema.xml
2. [+] check validity
3. [+] run setup:upgrade
4. [+] generate the db_schema_whitelist.json file
5. [+] [check table](./tasks/2_1_5_check_table.sql)

#### 2.2  create a sql query to select order + quote + comment

Use tables:

- sales_order ( shipping_address_id ) => sales_order_address ( entity_id )
- sales_order_address ( quote_address_id ) => quote_address ( address_id )
- quote_address ( address_id ) => rm38_checkout_shipping_comment ( quote_address_id )
- save_in_address_book = 0

1. [+] add address vie "Next" button
2. [+] add comment into database
3. [+] complete an order
4. [+] construct a query
5. [+] create 3 orders to check rows
        [+] guest
        [+] customer without save in address book checkbox
        [+] customer with default address
        [+] customer with save in address book checkbox

#### 2.4 create a comment repository

[+] model  
        [+] interface  
        [+] class  
[+] resource  
[+] collection  
[-] repository  
        [-] interface  
        [-] class  
[-] search results  
        [-] interface  
        [-] class  
