-- [ #1 ]
SELECT 
    address_id,
    quote_id,
    customer_id,
    customer_address_id,
    address_type,
    save_in_address_book
    FROM quote_address
    WHERE address_type = 'shipping'
        AND save_in_address_book = 0;
 

INSERT INTO rm38_checkout_shipping_comment
    (quote_address_id, comment) 
    VALUES (199, 'some comment');

SELECT * FROM rm38_checkout_shipping_comment;

--address was added but address_id with number 199  does not exist
CONSTRAINT 
    `RM38_CHKT_SHPP_COMMENT_ENTT_ID_QUOTE_ADDR_ADDR_ID` 
    FOREIGN KEY (`entity_id`) 
    REFERENCES `quote_address` (`address_id`) 
    ON DELETE CASCADE

-- it was an error in FOREIGN KEY (`entity_id`)
-- replace with FOREIGN KEY (`quote_address_id`)
-- try again

-- [ #2 ]

SELECT 
    address_id,
    quote_id,
    customer_id,
    customer_address_id,
    address_type,
    save_in_address_book
    FROM quote_address
    WHERE address_type = 'shipping'
        AND save_in_address_book = 0
        AND address_id = 99;
--+------------+----------+-------------+---------------------+--------------+----------------------+
--| address_id | quote_id | customer_id | customer_address_id | address_type | save_in_address_book |
--+------------+----------+-------------+---------------------+--------------+----------------------+
--|         99 |       43 |        NULL |                NULL | shipping     |                    0 |
--+------------+----------+-------------+---------------------+--------------+----------------------+

INSERT INTO rm38_checkout_shipping_comment
    (quote_address_id, comment) 
    VALUES (199, 'some comment');
--ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`shop_magento2`.`rm38_checkout_shipping_comment`, CONSTRAINT `RM38_CHKT_SHPP_COMMENT_QUOTE_ADDR_ID_QUOTE_ADDR_ADDR_ID` FOREIGN KEY (`quote_address_id`) REFERENCES `quote_address` (`address_id`)
--so all works as expected

INSERT INTO rm38_checkout_shipping_comment
    (quote_address_id, comment) 
    VALUES (99, 'some comment');
--Query OK, 1 row affected (0.006 sec)

SELECT * FROM rm38_checkout_shipping_comment;
--+-----------+------------------+--------------+
--| entity_id | quote_address_id | comment      |
--+-----------+------------------+--------------+
--|         3 |               99 | some comment |
--+-----------+------------------+--------------+

--now let's sign in. the address_id 99 will be deleted
--so entity_id 3 must be deleted too

SELECT 
    address_id,
    quote_id,
    customer_id,
    customer_address_id,
    address_type,
    save_in_address_book
    FROM quote_address
    WHERE address_type = 'shipping'
        AND save_in_address_book = 0
        AND address_id = 99;
--Empty set (0.001 sec)

SELECT * FROM rm38_checkout_shipping_comment
    WHERE quote_address_id = 99;
--Empty set (0.001 sec)


-- [ Conclusion ]

--all works as expected. rows were deleted from all 2 tables