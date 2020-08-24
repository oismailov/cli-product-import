# Product Import CLI
 
### CLI to import products from a CSV file into a MySQL database

## Setup
(database config is in `.env` file  
`DATABASE_URL=mysql://root:root@127.0.0.1:3306/cli_product_import?serverVersion=5.7`)
- Pull the latest changes from master branch. 
- run `composer install`
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:migrations:migrate --no-interaction`

## Run import
- Test data is in `data/products.csv` file.
- `php bin/console product:import` -  cli command to run import

## Output
- `php bin/console product:import` - minimum data with basic info and no errors.
#### Here is an example
```$xslt
INFO      [app] Starting products import...
INFO      [app] Number of rows with errors: 1
INFO      [app] Number of products created: 0
INFO      [app] Number of products updated: 4
INFO      [app] Number of products skipped: 1
INFO      [app] Finished product import!
```
- `php bin/console product:import -v ` - basic info with errors.
#### Here is an example
```$xslt
INFO      [app] Starting products import...
INFO      [app] Number of rows with errors: 1
DEBUG     [app] Row: 5 - Property: description - Message: The string "<script>console.log('XSS')</script>" contains illegal characters.
INFO      [app] Number of products created: 0
INFO      [app] Number of products updated: 4
INFO      [app] Number of products skipped: 1
INFO      [app] Finished product import!
```

## Validation Rules
There is additional validation for length of `SKU` value.  
You can find environment variables in `.env` file to setup these rules:
- SKU_MIN_LENGTH=4
- SKU_MAX_LENGTH=16

## Tests
There are tests for product validation rules. Please, use this command to run them all.  
`php bin/phpunit`

# App Scaling Scenario

#### I am good at AWS, that's why I would use their tools when designing scaling scenario. 
## Scenario #1
1. Separate code that reads data from file and runs basic validation rules (`producer`) 
with code that runs data insertion to mysql database (`consumer`).  
2. Create SNS topic. 
3. Create SQS and subscribe to that SNS topic. 
4. Producer pushes all validated data into SNS topic (SQS picks all changes). 
5. Consumer picks data from SQS and process it (DB insert).

## Scenario #2
Move complex validation part (lookup to a third party system) into separate microservice.
We will have 3 separate peaces of code (microservices).
1. reads code from file,  runs basic validation (type validation, empty values, XSS etc.) and pushes that data to SNS. 
2. Picks data from SQS and process it (if there is no need to third party system lookup), otherwise - second microservice will push that row to SQS (second separate queue). 
3. This peace is responsible for third party system lookup. It reads data from second SQS and runs all required valdiation and if row is valid - pushes that valid row to SNS (from part 1 and then second microservice can write it to DB). 


###  Why SNS/SQS connection and not just SQS.

The power of SNS SQS approach is that if Microservice A writes to SNS and Microservice B reads from SQS (that is subscribed to SNS) - we can simply scale Microservice B by creating more instances of that Microservice and by launching more queues (SQS) which will be subscribed to SNS and traffic will be distributed equally.
Each new instance of Microservice B will be connected to separate SQS. And when we need to scale app down - we simply unsubscribe queues  and then remove instances of  Microservice B. 
All these infrastructure manipulations can be done via "infrastructure as a code" approach. Terraform/Terragrunt can be used here.



 





