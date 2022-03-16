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

`DATABASE_URL="mysql://root:root@127.0.0.1:8889/snowtricks?serverVersion=5.7.34"`

Then, you can create your database as running the following command lines:

`symfony console doctrine:database:create`
`symfony console doctrine:fixtures: load`

## **Mail configuration**

To set up mailing, edit your `.env` as such:

`MAILER_DSN="smtp://my.email.address@mymail.com:Password1234@smtp.mymail.com:myport"`

## **Symfony packages**

- **Mailer**: manages emails
- **Twig**: our template engine

## **Run a local web server**

You can execute this command line to run a local web server: `symfony serve -d`

**NOW you can run the API!**
When the site is run, you can log using the following login and password:

- Login: **fonctionnaire**
- Password: **OpenClassrooms**

## **Code checking**

- **CodeClimate**: https://codeclimate.com/github/marclassort/Blog-PHP-Marc-Lassort
- **SonarCloud**: https://sonarcloud.io/project/overview?id=marclassort_Blog-PHP-Marc-Lassort
