# Create 3 orders to check rows

1. [+] guest
2. [+] customer without save in address book checkbox
3. [-] customer with default address
4. [-] customer with save in address book checkbox

## 1. guest

cardId  45
saveAddressBook '0'
shippingAddressId 104
commentField "comment1"
order # is: 000000006

```sql
--1
INSERT INTO rm38_checkout_shipping_comment
    (quote_address_id, comment) 
    VALUES (104, 'comment1');

--2
SELECT oa.order, qa.comment 
    FROM (
        SELECT 
        sales_order.entity_id AS `order`,
        sales_order_address.quote_address_id AS `order_address`
        FROM sales_order
            LEFT JOIN sales_order_address
            ON sales_order.shipping_address_id = sales_order_address.entity_id
            WHERE sales_order.entity_id = 6
    ) AS oa
        LEFT JOIN (
            SELECT quote_address.address_id AS `quote_address`,
            rm38_checkout_shipping_comment.comment `comment`
            FROM quote_address, rm38_checkout_shipping_comment
            WHERE 
                quote_address.address_id = rm38_checkout_shipping_comment.quote_address_id

        ) as qa
        ON oa.order_address = qa.quote_address
        ;
```

Result: 
+-------+----------+
| order | comment  |
+-------+----------+
|     6 | comment1 |
+-------+----------+

All works

## 2. customer without save in address book checkbox

cardId  35
saveAddressBook '0'
shippingAddressId 95
commentField "comment2"
order # is: 000000007


```sql
--1
INSERT INTO rm38_checkout_shipping_comment
    (quote_address_id, comment) 
    VALUES (95, 'comment2');

--2
SELECT oa.order, qa.comment 
    FROM (
        SELECT 
        sales_order.entity_id AS `order`,
        sales_order_address.quote_address_id AS `order_address`
        FROM sales_order
            LEFT JOIN sales_order_address
            ON sales_order.shipping_address_id = sales_order_address.entity_id
            WHERE sales_order.entity_id = 7
    ) AS oa
        LEFT JOIN (
            SELECT quote_address.address_id AS `quote_address`,
            rm38_checkout_shipping_comment.comment `comment`
            FROM quote_address, rm38_checkout_shipping_comment
            WHERE 
                quote_address.address_id = rm38_checkout_shipping_comment.quote_address_id

        ) as qa
        ON oa.order_address = qa.quote_address
        ;
```

All works

## 3. customer with default address

cardId  46
saveAddressBook '0'
shippingAddressId 108
commentField `uninitialized`
order # is: 000000008

```sql
SELECT 
    address_id,
    quote_id,
    customer_id,
    customer_address_id,
    address_type,
    save_in_address_book,
    street,
    city,
    postcode,
    telephone
    FROM quote_address
    WHERE address_type = 'shipping'
        AND address_id = 108;
```

## 4. customer with save in address book checkbox

cardId  47
saveAddressBook 1
shippingAddressId 111
commentField "comment4"
order # is: 000000009

```sql
--1
INSERT INTO rm38_checkout_shipping_comment
    (quote_address_id, comment) 
    VALUES (111, 'comment4');

--2
SELECT oa.order, qa.comment 
    FROM (
        SELECT 
        sales_order.entity_id AS `order`,
        sales_order_address.quote_address_id AS `order_address`
        FROM sales_order
            LEFT JOIN sales_order_address
            ON sales_order.shipping_address_id = sales_order_address.entity_id
            WHERE sales_order.entity_id = 9
    ) AS oa
        LEFT JOIN (
            SELECT quote_address.address_id AS `quote_address`,
            rm38_checkout_shipping_comment.comment `comment`
            FROM quote_address, rm38_checkout_shipping_comment
            WHERE 
                quote_address.address_id = rm38_checkout_shipping_comment.quote_address_id

        ) as qa
        ON oa.order_address = qa.quote_address
        ;
```
+-------+----------+
| order | comment  |
+-------+----------+
|     9 | comment4 |
+-------+----------+

