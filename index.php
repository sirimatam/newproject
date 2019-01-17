<?php
$db = pg_connect("host=ec2-107-22-162-8.compute-1.amazonaws.com port=5432 dbname=d8kmurmr59kdsg user=kdozgixaxyediw password=0fad69adabf7a1c52fec6765c9331e776845abe09fc5a3b7c9c5ae1ccc9f6531");


echo $db;

pg_query($db,"CREATE TABLE product (
prod_id varchar(10) NOT NULL,
prod_name varchar(40) NOT NULL,
prod_size varchar(10) NOT NULL,
prod_type varchar(40) NOT NULL,
prod_color varchar(40) NOT NULL,
prod_description varchar(200) NOT NULL,
prod_price_per_unit INT NOT NULL,
prod_stock INT NOT NULL,
prod_pro_price INT NOT NULL,
PRIMARY KEY(prod_id))");
/*
pg_query($db,"CREATE TABLE createcart (
cartp_id varchar(10) NOT NULL,
cus_id varchar(50) NOT NULL,
FOREIGN KEY (cus_id) REFERENCES customer(cus_id),
PRIMARY KEY(cartp_id))");

pg_query($db,"CREATE TABLE cart_product (
cart_prod_id varchar(10) NOT NULL,
cartp_id varchar(10) NOT NULL,
prod_id varchar(10) NOT NULL,
prod_qtt varchar(10) NOT NULL,
FOREIGN KEY (cartp_id) REFERENCES createcart(cartp_id),
FOREIGN KEY (prod_id) REFERENCES product(prod_id),
PRIMARY KEY(cart_prod_id))");

pg_query($db,"CREATE TABLE order (
order_id varchar(10) NOT NULL,
cartp_id varchar(40) NOT NULL,
total__price varchar(10) NOT NULL,
date varchar(40) NOT NULL,
time varchar(40) NOT NULL,
order_status varchar(40) NOT NULL,
FOREIGN KEY (cartp_id) REFERENCES createcart(cartp_id),
PRIMARY KEY(order_id))");

pg_query($db,"CREATE TABLE customer (
cus_id varchar(50) NOT NULL,
cus_name varchar(40) NOT NULL,
cus_address varchar(100) NOT NULL,
cus_tel varchar(10) NOT NULL,
PRIMARY KEY(cus_id))");

pg_query($db,"CREATE TABLE payment (
pay_id varchar(10) NOT NULL,
pay_slip varchar(100) NOT NULL,
pay_date varchar(10) NOT NULL,
pay_time varchar(10) NOT NULL,
order_id varchar(10) NOT NULL,
check varchar(10) NOT NULL,
FOREIGN KEY (order_id) REFERENCES order(order_id),
PRIMARY KEY(pay_id))");
*/
/*
pg_query($db,"CREATE TABLE staff (
staff_id varchar(10) NOT NULL,
staff_status varchar(10) NOT NULL,
PRIMARY KEY(staff_id))");

*/
?>
