# Shipping Calculator Task


Requirements
----------------------------

* We recommend picking your favorite programming language. No constraints here. We want you to show us what you're able to do with the tools you already know well.
* Your solution should match the philosophy described above.
* Using additional libraries is prohibited. That constraint is not applied for unit tests and build.
* There should be an easy way to start the solution and tests. (in Ruby case, it could be something like: "rake run input.txt", "rake test")
* A short documentation of design decisions and assumptions can be provided in the code itself.
* Make sure your input data is loaded from a file (default name 'input.txt' is assumed)
* Make sure your solution outputs data to the screen (STDOUT) in a format described below
* Your design should be flexible enough to allow adding new rules and modifying existing ones easily

Problem
----------------------------
Each item, depending on its size gets an appropriate package size assigned to it:

  * S - Small, a popular option to ship jewelry
  * M - Medium - clothes and similar items
  * L - Large - mostly shoes

Shipping price depends on package size and a provider:

| Provider     | Package Size | Price  |
|--------------|--------------|--------|
| LP           | S            | 1.50 € |
| LP           | M            | 4.90 € |
| LP           | L            | 6.90 € |
| MR           | S            | 2 €    |
| MR           | M            | 3 €    |
| MR           | L            | 4 €    |

Usually, the shipping price is covered by the buyer, but sometimes, in order to promote one or another provider, Vinted covers part of the shipping price.

**Your task is to create a shipment discount calculation module.**

First, you have to implement such rules:
  * All S shipments should always match the lowest S package price among the providers.
  * The third L shipment via LP should be free, but only once a calendar month.
  * Accumulated discounts cannot exceed 10 € in a calendar month. If there are not enough funds to fully
  cover a discount this calendar month, it should be covered partially.

**Your design should be flexible enough to allow adding new rules and modifying existing ones easily.**

Member's transactions are listed in a file 'input.txt', each line containing: date (without hours, in ISO format), package size code, and carrier code, separated with whitespace:
```
2015-02-01 S MR
2015-02-02 S MR
2015-02-03 L LP
2015-02-05 S LP
2015-02-06 S MR
2015-02-06 L LP
2015-02-07 L MR
2015-02-08 M MR
2015-02-09 L LP
2015-02-10 L LP
2015-02-10 S MR
2015-02-10 S MR
2015-02-11 L LP
2015-02-12 M MR
2015-02-13 M LP
2015-02-15 S MR
2015-02-17 L LP
2015-02-17 S MR
2015-02-24 L LP
2015-02-29 CUSPS
2015-03-01 S MR
```
Your program should output transactions and append reduced shipment price and a shipment discount (or '-' if there is none). The program should append 'Ignored' word if the line format is wrong or carrier/sizes are unrecognized.
```
2015-02-01 S MR 1.50 0.50
2015-02-02 S MR 1.50 0.50
2015-02-03 L LP 6.90 -
2015-02-05 S LP 1.50 -
2015-02-06 S MR 1.50 0.50
2015-02-06 L LP 6.90 -
2015-02-07 L MR 4.00 -
2015-02-08 M MR 3.00 -
2015-02-09 L LP 0.00 6.90
2015-02-10 L LP 6.90 -
2015-02-10 S MR 1.50 0.50
2015-02-10 S MR 1.50 0.50
2015-02-11 L LP 6.90 -
2015-02-12 M MR 3.00 -
2015-02-13 M LP 4.90 -
2015-02-15 S MR 1.50 0.50
2015-02-17 L LP 6.90 -
2015-02-17 S MR 1.90 0.10
2015-02-24 L LP 6.90 -
2015-02-29 CUSPS Ignored
2015-03-01 S MR 1.50 0.50
```

Main Project Architecture
----------------------------
This is the main logic architecture of the application where you will find most of the logic implemented.

```
project
├── app
│   ├── Modules
│   │   ├── Discount
│   │   │   ├── Interfaces
│   │   │   │   ├── Providers
│   │   │   │   ├── Rules
│   │   │   │   ├── Services
│   │   │   │   └── Transformer
│   │   │   ├── Providers
│   │   │   ├── Rules
│   │   │   ├── Services
│   │   │   └── Transformer
│   │   └── File
│   │       ├── Interfaces
│   │       │   ├── Processors
│   │       │   └── Services
│   │       ├── Processors
│   │       └── Services
│   └── Modules
│       └── Output
│           └── Interfaces
├── storage
│   └── app
│       ├── input.txt
│       └── providers.txt
└── tests
    └── Unit
        ├── Modules
        │   ├── Discount
        │   │   ├── Providers
        │   │   └── Rules
        │   ├── File
        │   │   ├── Processors
        │   │   └── Services
        │   └── Output
        └── Modules
            └── Output


```

About
----------------------------
**Information**

  * Project is done without any external libraries except for testing (PHPUnit) and building the application (Docker).
  * Application is done on PHP language (Laravel framework).
  * There is a comments included in some of the classes about the coding decisions.
  * Application's logic is covered with Unit tests.

Project setup
----------------------------
**Requirements**

  * Docker must be installed in your machine.
  * Docker Compose is also needed.

**Installation**

Follow these steps in order to run the environment:
  * Clone the repository to your local machine.
  * Navigate to the root directory of the application.
  * Build the Docker containers using the following command: `docker-compose up --build`
  * Once the containers have been built, enter the Docker container by running the following command: `docker exec -it shipping-calculator-backend-1 bash`
  * Once inside the container, copy the .env.example file to .env using the following command: `cp .env.example .env`
  * Install composer packages: `composer install`
  * Generate a new key for the application by running the following command: `php artisan key:generate`

**Usage**

  * To run the application, enter this command: `php artisan app:discount`
  * To run the tests for the application, enter this command: `php artisan test`

NOTE: You must be inside the Docker container to run the application (You can enter container by running command: `docker exec -it shipping-calculator-backend-1 bash`)

**EXTRA**

  * There is a CI Pipeline setup in the repository in order to run tests before every merge request.
