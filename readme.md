# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Status Code and Reason Phrase

Status code | About Information Code
------------|------------------------------------------------------------------------------
1xx:        | Informational - Request received, continuing process
2xx:        | Success - The action was successfully received,
3xx:        | Redirection - Further action must be taken in order to   complete the request
4xx:        | Client Error - The request contains bad syntax or cannot be fulfilled
5xx:        | Server Error - The server failed to fulfill an apparently  valid request

## Status Code and Response  

Status code | About Information Code
------------|---------------------------------------------------
"100"       | Section 10.1.1: Continue
"101"       |  Section 10.1.2: Switching Protocols
"200"       |  Section 10.2.1: OK
"201"       |  Section 10.2.2: Created
"202"       |  Section 10.2.3: Accepted
"203"       |  Section 10.2.4: Non-Authoritative Information
"204"       |  Section 10.2.5: No Content
"205"       |  Section 10.2.6: Reset Content
"206"       |  Section 10.2.7: Partial Content
"300"       |  Section 10.3.1: Multiple Choices
"301"       |  Section 10.3.2: Moved Permanently
"302"       |  Section 10.3.3: Found
"303"       |  Section 10.3.4: See Other
"304"       |  Section 10.3.5: Not Modified
"305"       |  Section 10.3.6: Use Proxy
"307"       |  Section 10.3.8: Temporary Redirect
"400"       |  Section 10.4.1: Bad Request
"401"       |  Section 10.4.2: Unauthorized
"402"       |  Section 10.4.3: Payment Required
"403"       |  Section 10.4.4: Forbidden
"404"       |  Section 10.4.5: Not Found
"405"       |  Section 10.4.6: Method Not Allowed
"406"       |  Section 10.4.7: Not Acceptable
"407"       |  Section 10.4.8: Proxy Authentication Required
"408"       |  Section 10.4.9: Request Time-out
"409"       |  Section 10.4.10: Conflict
"410"       |  Section 10.4.11: Gone
"411"       |  Section 10.4.12: Length Required
"412"       |  Section 10.4.13: Precondition Failed
"413"       |  Section 10.4.14: Request Entity Too Large
"414"       |  Section 10.4.15: Request-URI Too Large
"415"       |  Section 10.4.16: Unsupported Media Type
"416"       |  Section 10.4.17: Requested range not satisfiable
"417"       |  Section 10.4.18: Expectation Failed
"500"       |  Section 10.5.1: Internal Server Error
"501"       |  Section 10.5.2: Not Implemented
"502"       |  Section 10.5.3: Bad Gateway
"503"       |  Section 10.5.4: Service Unavailable
"504"       |  Section 10.5.5: Gateway Time-out
"505"       |  Section 10.5.6: HTTP Version not supported

## Public

composer dump-autoload -o

php -S localhost:8000 -t public

php -S 0.0.0.0:8000 -t public

- php artisan make:migration create_users_table
- php artisan migrate
- php artisan db:seed

php artisan migrate:refresh
php artisan migrate:refresh --seed

## migrate 

# Install package
sudo apt-get update
sudo apt-get install pgloader
 
sudo -u gitlab-psql pgloader commands.load

## clean cache
php artisan cache:clear


pdfcrowd/pdfcrowd": "^4.11"
"nordsoftware/lumen-file-manager": "^2.2"
composer require ktquez/lumen-image