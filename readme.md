# **BileMo - Marc Lassort**

This API is the 7th project of the OpenClassrooms PHP/Symfony Web Developer training.  
The API allows a customer to display all his smartphones products, & display and manage his users.

# **How to install the API**

## **Pre-Requisites**

- **Database**: MySQL
- **PHP version**: >=8.0.8
- **Softwares**:

* **NPM**: `npm install`
* **Composer**: `composer install`

## **Installation**

1. Download or clone the GitHub repository.
2. Run `composer install`. (It is possible that some bundles do not have the right version. In that case, run `composer update`.)
3. Make the following configuration settings.

## **Database configuration**

To set up your database, edit your `.env` as such:

DATABASE_URL="mysql://root:root@127.0.0.1:8889/bilemo?serverVersion=5.7.34"

Then, you can create your database as running the following command lines:

`symfony console doctrine:database:create`
`symfony console doctrine:fixtures: load`

## **Symfony packages**

- **JMSSerializerBundle**: serializes the data
- **BazingaHateoasBundle**: implements the 3rd level of Richardson model
- **NelmioApiDocBundle**: allows to document the API

## **Run a local web server**

You can execute this command line to run a local web server: `symfony serve -d`

## **Use POSTMAN**

- You should first install Postman: https://www.postman.com/
- Then you have to go to /api/login_check with a POST method using these logins:
  {
  "email": "alix12@rey.com",
  "password": "password"
  }
- You will get a token.
- Go to Headers > Key. You will add "Authorization"
- On Headers > Value, add "Bearer" and paste your token.
- Go to https://localhost:yourport/api/doc.json.
- You can then get the different links of the API.

## **Documentation**

The JSON documentation is available here:

- https://localhost:yourpost/api/doc.json

**NOW you can run the API!**