# bcd-cogip-api

<!-- Admonition Disclaimer -->
> [!WARNING] All data is fake and not real. This project is only for learning purpose.

## Overview

Our mission was to produce an API for the Cogip project. This API will returns data about the Cogip project.
It will returns informations about :

- Cogip users
- Cogip type
- Cogip invoice
- Cogip company
- Cogip contact

## Stack used

[![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/) [![Symfony](https://img.shields.io/badge/symfony-%23000000.svg?style=for-the-badge&logo=symfony&logoColor=white)](https://symfony.com/) [![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/) [![JWT](https://img.shields.io/badge/Lexik_JWT-black?style=for-the-badge&logo=JSON%20web%20tokens)](https://github.com/lexik/LexikJWTAuthenticationBundle)

## API routes users

| Method | Route           | Description              |
|--------|-----------------|--------------------------|
| POST   | /api/users      | Create Cogip users       |
| GET    | /api/users      | Returns Cogip users      |
| GET    | /api/users/{id} | Returns Cogip user by id |
| PUT    | /api/users/{id} | Update Cogip users       |
| DELETE | /api/users/{id} | Delete Cogip users       |

## API Types routes

| Method | Route           | Description              |
|--------|-----------------|--------------------------|
| POST   | /api/types      | Create Cogip types       |
| GET    | /api/types      | Returns Cogip types      |
| GET    | /api/types/{id} | Returns Cogip type by id |
| PUT    | /api/types/{id} | Update Cogip types       |
| DELETE | /api/types/{id} | Delete Cogip types       |

## API Invoice routes

| Method | Route              | Description                 |
|--------|--------------------|-----------------------------|
| POST   | /api/invoices      | Create Cogip invoices       |
| GET    | /api/invoices      | Returns Cogip invoices      |
| GET    | /api/invoices/{id} | Returns Cogip invoice by id |
| PUT    | /api/invoices/{id} | Update Cogip invoices       |
| DELETE | /api/invoices/{id} | Delete Cogip invoices       |

## API contact routes

| Method | Route              | Description                 |
|--------|--------------------|-----------------------------|
| POST   | /api/contacts      | Create Cogip contacts       |
| GET    | /api/contacts      | Returns Cogip contacts      |
| GET    | /api/contacts/{id} | Returns Cogip contact by id |
| PUT    | /api/contacts/{id} | Update Cogip contacts       |
| DELETE | /api/contacts/{id} | Delete Cogip contacts       |

## API company routes

| Method | Route               | Description                 |
|--------|---------------------|-----------------------------|
| POST   | /api/companies      | Create Cogip companies      |
| GET    | /api/companies      | Returns Cogip companies     |
| GET    | /api/companies/{id} | Returns Cogip company by id |
| PUT    | /api/companies/{id} | Update Cogip companies      |
| DELETE | /api/companies/{id} | Delete Cogip companies      |

## Credits

This API was created by [Pinet Donatien](https://github.com/tidjee-dev) and [de Sadeleer Jason](https://github.com/sakakara).
