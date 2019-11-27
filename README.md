 # PRINTIFY PRODUCT API

This is an API which allows to add and view products.

It is also possible to create order consisting of products and view orders.

Below is each endpoint of the API and how to access it.

## PROJECT FOLDER STRUCTURE

```
printify-api-lauris
├── api [source files for each endpoint]
├── config [file to set up connection with mysql database within docker container]
├── mysql-dump [file with 3 empty tables - products, orders, orders_products]
├── php-apache [Dockerfile for php running on apache with mysqli, pdo support]
├── utils [two classes with functionality to manipulate 3 tables within printify-products database]
.gitignore
README.md
docker-compose.yml [used to create images for php, mysql, phpmyadmin and launch them]
```

## INSTALLATION

In root directory run

```
docker-compose up
```

## USAGE

Endpoints can be accessed at port 80:

http://localhost/api/endpoint

phpMyAdmin can be accessed at port 8080 with username root and password root

http://localhost:8080/

## ENDPOINTS

**add-product.php**

POST request

Add a product

http://localhost/api/add-product.php
```
{
	"price": 10,
	"productType": "socks",
	"color": "yellow",
	"size": "L"
}
```

**view-product.php**

GET request

View one product by it's id

http://localhost/api/view-product.php?id=1



**view-products.php**

GET request

View all products

http://localhost/api/view-products.php



**create-order.php**

POST request

Add an order

http://localhost/api/add-order.php

```
(product ID) : (quantity)

{
	"1" : "2",
	"2" : "2"
}
```


**view-orders.php**

GET request

Viewing orders

http://localhost/api/view-orders.php

Viewing orders that contain item of a specific type of product

http://localhost/api/view-orders.php?type=socks

