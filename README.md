<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# How to run #

Dependencies:

* docker. See [https://docs.docker.com/engine/installation](https://docs.docker.com/engine/installation)
* docker-compose. See [docs.docker.com/compose/install](https://docs.docker.com/compose/install/)

Once you're done, simply run `docker-compose up -d` in the root folder of the project. This will initialise and start
all the
containers, then leave them running in the background.

## Services exposed outside your environment ##

You can access your application via **`localhost`**. Mailhog and nginx both respond to any hostname, in case you want to
add your own hostname on your `/etc/hosts`

 Service    | Address outside containers                
------------|-------------------------------------------
 Webserver  | [localhost:62000](http://localhost:62000) 
 PostgreSQL | **host:** `localhost`; **port:** `62014`  

## Hosts within your environment ##
> The first time you run the containers, it might take a while for the database to get initialised and composer/npm packages to get installed. Check the logs (`docker-compose logs -f`) to know when your app is ready.

 Service  | Hostname | Port number    
----------|----------|----------------
 php-fpm  | php-fpm  | 9000           
 Postgres | postgres | 5432 (default) 
 Redis    | redis    | 6379 (default) 

## Docker compose cheatsheet ##

**Note:** you need to cd first to where your docker-compose.yml file lives.

* Start containers in the background: `docker-compose up -d`
### 
* Start containers on the foreground: `docker-compose up`. You will see a stream of logs for every container running.
  ctrl+c stops containers.
* To check the logs `docker-compose logs -f --tail=100`
* Stop containers: `docker-compose stop`
* Kill containers: `docker-compose kill`
* View container logs: `docker-compose logs` for all containers or `docker-compose logs SERVICE_NAME` for the logs of
  all containers in `SERVICE_NAME`.
* Execute command inside of container: `docker-compose exec SERVICE_NAME COMMAND` where `COMMAND` is whatever you want
  to run. Examples:
    * Shell into the PHP container, `docker-compose exec php-fpm bash`
    * Run tests, `docker-compose exec php-fpm ./vendor/bin/phpunit`

## Docker troubleshooting ##

* If you get any build errors, try running `docker-compose build --no-cache` to rebuild the images from scratch.
* If you change any service configuration on docker-compose.yml, you need to stop and run `docker-compose up build`
  again.

# DogHouse #

The project has got both api and web interfaces. You can reach the swagger documentations by visiting the following url:
**http://localhost:62000/** Also I've added a postman collection to the project root folder. You can import it to your
postman
**[doghouse.postman_collection.json](doghouse.postman_collection.json)**

## Web ##

* You can use the swagger documentation to test the api.
  ![img.png](img.png)

* You can get the dog list by visiting the following url:
    * http://localhost:62000/home
    * You should be logged in to see the list.
    * The list is a basic react component. You can find it under the following path:
        * resources/js/components/Dogs.jsx
          ![img_1.png](img_1.png)

## Api ##
* You can find all the api endpoints in the swagger documentation on http://localhost:62000 or postman collection.
* Api is authenticated by JWT bearer token. So you need to get a token from the login endpoint. You can use the
  following credentials:
    * email: `dummy.user@fake.com`
    * password: `123456`
    * http://localhost:62000/api/login
* Response example:
```json
  {
    "data": {
        "user": {
            "id": 7,
            "name": "Dummy User",
            "email": "dummy.user@fake.com",
            "email_verified_at": null,
            "deleted_at": null,
            "created_at": null,
            "updated_at": null
        },
        "authorisation": {
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjYyMDAwL2FwaS9sb2dpbiIsImlhdCI6MTY5MDAzOTI5NywiZXhwIjoxNjkwMDQyODk3LCJuYmYiOjE2OTAwMzkyOTcsImp0aSI6IkEwSlZSYUlNaTFWcUhZYUIiLCJzdWIiOiI3IiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.51H9nK3i-o5vxUaR3lBiQuU2hOiqd8BWc2KCemF6-tA",
            "type": "bearer"
        }
    },
    "status": "success",
    "message": "Successfully logged in!"
}
```


* or you can create a new user by using the register endpoint.
    * http://localhost:62000/api/register

## Tests ##

* I've added some basic tests to the project. You can run them by using the following command:
    * `docker-compose exec php-fpm ./vendor/bin/phpunit`
    * You can find the tests under the following path:
        * tests/Feature/AuthControllerTest.php
        * tests/Feature/DogControllerTest.php

 ## Task ##
Create a laravel project which makes two REST endpoints available. The protected endpoint
/adddog accepts a dog object and creates a new DB entry in the dogs table. A second
protected endpoint /listdogs returns a list of dogs stored in the DB.

Details:
● Authorization
Both endpoints should only accept a request in case the &quot;secret&quot; is sent and matches
the secret defined in the laravel app (env). The secret is sent as a HTTP header.
○ If the secrets do not match, the endpoints should return http 403.
○ The /adddog endpoint returns a 200 success message.
● Data Storage
○ Data is stored using PostgresDB.
○ Create the DB schema using Laravel migrations
○ All Dog data is stored in a JSON column.
○ Each Dog has at least a “name” property.
○ The DB is optimised so it can store and query a significant amount of data.
● /adddog Endpoint
○ Accepts a post request using the described authorization.
○ The post request includes a json Dog object.
○ The request is validated before it is processed.
○ The endpoint stores the Dog object in the respective DB column.
○ The storage operation is performed async, meaning the initial request returns
while the store operation is still running.
○ To simulate a long store operation, the application waits 10 seconds before
the store is executed.

● /listdogs Endpoint
○ Accepts a GET request using the described authorization.
○ The endpoint returns a list of Dog objects
○ The endpoint accepts a query parameter “name” which filters the returned
Dogs by their name.
○ The endpoint returns a max. of 30 Dogs.
● Error handling is implemented where necessary
