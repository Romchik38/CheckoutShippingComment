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
        AND save_in_address_book = 0
        AND address_id = 101;
--+------------+----------+-------------+---------------------+--------------+----------------------+
--| address_id | quote_id | customer_id | customer_address_id | address_type | save_in_address_book |
--+------------+----------+-------------+---------------------+--------------+----------------------+
--|        101 |       44 |        NULL |                NULL | shipping     |                    0 |
--+------------+----------+-------------+---------------------+--------------+----------------------+

INSERT INTO rm38_checkout_shipping_comment
    (quote_address_id, comment) 
    VALUES (101, 'some');
--Query OK, 1 row affected (0.011 sec)

SELECT * FROM rm38_checkout_shipping_comment;
--+-----------+------------------+---------+
--| entity_id | quote_address_id | comment |
--+-----------+------------------+---------+
--|         4 |              101 | some    |
--+-----------+------------------+---------+


