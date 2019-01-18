<?php
require_once('connection.php');
echo $db;

pg_query($db,"CREATE TABLE Product (
prod_id varchar(10) NOT NULL PRIMARY KEY,
prod_name varchar(40) NOT NULL,
prod_size varchar(10) NOT NULL,
prod_type varchar(40) NOT NULL,
prod_color varchar(40) NOT NULL,
prod_description varchar(200) NOT NULL,
prod_price_per_unit INT NOT NULL,
prod_stock INT NOT NULL,
prod_pro_price INT NOT NULL
)");
/*
pg_query($db,"CREATE TABLE Createcart (
cartp_id varchar(10) NOT NULL,
cus_id varchar(50) NOT NULL,
FOREIGN KEY (cus_id) REFERENCES Customer(cus_id),
PRIMARY KEY(cartp_id))");

pg_query($db,"CREATE TABLE Cart_product (
cart_prod_id varchar(10) NOT NULL,
cartp_id varchar(10) NOT NULL,
prod_id varchar(10) NOT NULL,
prod_qtt varchar(10) NOT NULL,
FOREIGN KEY (cartp_id) REFERENCES Createcart(cartp_id),
FOREIGN KEY (prod_id) REFERENCES Croduct(prod_id),
PRIMARY KEY(cart_prod_id))");

pg_query($db,"CREATE TABLE Order (
order_id varchar(10) NOT NULL,
cartp_id varchar(40) NOT NULL,
total__price varchar(10) NOT NULL,
date varchar(40) NOT NULL,
time varchar(40) NOT NULL,
order_status varchar(40) NOT NULL,
FOREIGN KEY (cartp_id) REFERENCES Createcart(cartp_id),
PRIMARY KEY(order_id))");

pg_query($db,"CREATE TABLE Customer (
cus_id varchar(50) NOT NULL,
cus_name varchar(40) NOT NULL,
cus_address varchar(100) NOT NULL,
cus_tel varchar(10) NOT NULL,
PRIMARY KEY(cus_id))");

pg_query($db,"CREATE TABLE Payment (
pay_id varchar(10) NOT NULL,
pay_slip varchar(100) NOT NULL,
pay_date varchar(10) NOT NULL,
pay_time varchar(10) NOT NULL,
order_id varchar(10) NOT NULL,
check varchar(10) NOT NULL,
FOREIGN KEY (order_id) REFERENCES Order(order_id),
PRIMARY KEY(pay_id))");
*/
/*
pg_query($db,"CREATE TABLE Staff (
staff_id varchar(10) NOT NULL,
staff_status varchar(10) NOT NULL,
PRIMARY KEY(staff_id))");

*/
?>
