# bcd-cogip-api

## Overview

Our mission was to produce an API for the Cogip project. This API will returns data about the Cogip project.
It will returns informations about : 
- Cogip users
- cogip type
- cogip invoice
- cogip company
- cogip contact

## Stack used

![Symfony](https://img.shields.io/badge/symfony-%23000000.svg?style=for-the-badge&logo=symfony&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-black?style=for-the-badge&logo=JSON%20web%20tokens)


## API routes users

| Method | Route | Description |
| --- | --- | --- |
| GET | /api/users | Returns Cogip users |
| GET | /api/users/{id} | Returns Cogip user by id |
| post | /api/users | Create Cogip users |
| put | /api/users/{id} | Update Cogip users |
| delete | /api/users/{id} | Delete Cogip users |

## API Types routes

| Method | Route | Description |
| --- | --- | --- |
| GET | /api/types | Returns Cogip types |
| GET | /api/types/{id} | Returns Cogip type by id |
| post | /api/types | Create Cogip types |
| put | /api/types/{id} | Update Cogip types |
| delete | /api/types/{id} | Delete Cogip types |

## API Invoice routes

| Method | Route | Description |
| --- | --- | --- |
| GET | /api/invoices | Returns Cogip invoices |
| GET | /api/invoices/{id} | Returns Cogip invoice by id |
| post | /api/invoices | Create Cogip invoices |
| put | /api/invoices/{id} | Update Cogip invoices |
| delete | /api/invoices/{id} | Delete Cogip invoices |

## API contact routes

| Method | Route | Description |
| --- | --- | --- |
| GET | /api/contacts | Returns Cogip contacts |
| GET | /api/contacts/{id} | Returns Cogip contact by id |
| post | /api/contacts | Create Cogip contacts |
| put | /api/contacts/{id} | Update Cogip contacts |
| delete | /api/contacts/{id} | Delete Cogip contacts |

## API company routes

| Method | Route | Description |
| --- | --- | --- |
| GET | /api/companies | Returns Cogip companies |  
| GET | /api/companies/{id} | Returns Cogip company by id |
| post | /api/companies | Create Cogip companies |
| put | /api/companies/{id} | Update Cogip companies |
| delete | /api/companies/{id} | Delete Cogip companies |

## Credits 

This API was created by [Pinet Donatien](https://github.com/tidjee-dev) and [de Sadeleer Jason](https://github.com/sakakara).