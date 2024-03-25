-- sales_order ( shipping_address_id ) => sales_order_address ( entity_id )
-- sales_order_address ( quote_address_id ) => quote_address ( address_id )
-- quote_address ( address_id ) => rm38_checkout_shipping_comment ( quote_address_id )
-- save_in_address_book = 0

--cardId - 44
--shippingAddressId - 101
--comment - 'some'
--saveInAddressBook - '0'

--orderId - 000000005

--[ #1 ] 
SELECT entity_id,
    customer_id,
    customer_is_guest,
    quote_address_id,
    quote_id,
    shipping_address_id,
    increment_id
    FROM sales_order
        WHERE entity_id = 5;
--+-----------+-------------+-------------------+------------------+----------+---------------------+--------------+
--| entity_id | customer_id | customer_is_guest | quote_address_id | quote_id | shipping_address_id | increment_id |
--+-----------+-------------+-------------------+------------------+----------+---------------------+--------------+
--|         5 |        NULL |                 1 |             NULL |       44 |                   9 | 000000005    |
--+-----------+-------------+-------------------+------------------+----------+---------------------+--------------+


SELECT entity_id, quote_address_id
    FROM sales_order_address
        WHERE entity_id = 9;
--+-----------+------------------+
--| entity_id | quote_address_id |
--+-----------+------------------+
--|         9 |              101 |
--+-----------+------------------+

SELECT quote_address.address_id AS `quote_address`,
    rm38_checkout_shipping_comment.comment `comment`
    FROM quote_address, rm38_checkout_shipping_comment
    WHERE 
        quote_address.address_id = 101 AND
        quote_address.address_id = rm38_checkout_shipping_comment.quote_address_id;

--[ #2 ] 
SELECT 
    sales_order.entity_id AS `order`,
    sales_order_address.quote_address_id
    FROM sales_order
        LEFT JOIN sales_order_address
        ON sales_order.shipping_address_id = sales_order_address.entity_id
        WHERE sales_order.entity_id = 5;

-- [RESULT]
SELECT oa.order, qa.comment 
    FROM (
        SELECT 
        sales_order.entity_id AS `order`,
        sales_order_address.quote_address_id AS `order_address`
        FROM sales_order
            LEFT JOIN sales_order_address
            ON sales_order.shipping_address_id = sales_order_address.entity_id
            WHERE sales_order.entity_id = 5
    ) AS oa
        LEFT JOIN (
            SELECT quote_address.address_id AS `quote_address`,
            rm38_checkout_shipping_comment.comment `comment`
            FROM quote_address, rm38_checkout_shipping_comment
            WHERE 
                -- quote_address.address_id = 101 AND
                quote_address.address_id = rm38_checkout_shipping_comment.quote_address_id

        ) as qa
        ON oa.order_address = qa.quote_address
        ;