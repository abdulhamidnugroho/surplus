<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Surplus
## Stack

**Server:** PHP >= 8.0

**Database:** MySQL

**Framework:** Laravel 9


## Installation

Clone this project, go to the project directory and run these command

```bash
  composer install
  cp .env.example .env
```
Setup and create the database based the keys in the .env and .env.testing file

Database migration & seeding for dummy data

```bash
  php artisan migrate --seed
```

If you're on Mac OS, it's highly recommended to run this project with  [Laravel Valet](https://laravel.com/docs/9.x/valet)

To run this project real quick

```bash
  php artisan serve
```


Running test

```bash
  php artisan test --env=testing
```
        
## API Reference (Postman Collection Attached)

## Category
### Get All Categories

```http
  GET /api/categories
```
----
### Create Category

```http
  POST /api/categories
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`       | `string` | **Required**. |
| `enable`     | `int`    | **Required**. |

----

### Get Category (Detail)

```http
  GET /api/categories/{id}
```
----
### Update Category

```http
  PUT /api/categories/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`       | `string` | **Required**. |
| `enable`     | `int`    | **Required**. |

----

### Delete Category

```http
  DELETE /api/categories/{id}
```
----
----
## Image
### Get All Images

```http
  GET /api/images
```
----
### Create Image

```http
  POST /api/images
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`       | `string` | **Required**. |
| `file`       | `file`   | **Required**. |
| `enable`     | `int`    | **Required**. |

----

### Get Image (Detail)

```http
  GET /api/images/{id}
```
----
### Update Image

```http
  POST /api/images/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`       | `string` | **Required**. |
| `file`       | `file`   | **Required**. |
| `enable`     | `int`    | **Required**. |

----

### Delete Image

```http
  DELETE /api/images/{id}
```
-----
----
## Product
### Get All Products

```http
  GET /api/products
```
----
### Create Product

```http
  POST /api/products
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`         | `string` | **Required**. |
| `description`  | `string` | **Required**. |
| `category_ids` | `string` | **Required**. |
| `image_ids`    | `string` | **Required**. |
| `enable`       | `int`    | **Required**. |

----

### Get Product (Detail)

```http
  GET /api/products/{id}
```
----
### Update Product

```http
  POST /api/products/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`         | `string` | **Required**. |
| `description`  | `string` | **Required**. |
| `category_ids` | `string` | **Required**. |
| `image_ids`    | `string` | **Required**. |
| `enable`       | `int`    | **Required**. |

----

### Delete Product

```http
  DELETE /api/products/{id}
```
-----