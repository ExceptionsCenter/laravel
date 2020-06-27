# Laravel Exceptions' Center
Package to send the exceptions to the Exceptions' Center from a Laravel website.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Installation
You can quickly add this package in your application using Composer. **Be careful** to use the correct version of the package regarding your Laravel application version:

### Version
For now, this package supports **all Laravel versions from 5.3**.

### Composer
In a Bash terminal:
```bash
composer require exceptions-center/laravel
```

## Usage
In this section, we will see how to use the current package features.
You need to add some lines into your **exceptions handler** in ```app/Exceptions/Handler.php``` :
```php
public function render($request, Throwable $throwable) {
    // ...

    if($this->shouldReport($throwable)) {
        ExceptionSender::make($throwable, $request)->send() ;
    }

    return parent::render($request, $throwable);
}
```

If an error occurred during the sending, the error will be saved in a ```storage/logs/exceptions.log```.

## Configuration
You have two different ways to configure your application:

### Ways
##### Using config file
You can access to a part of the available configuration using your ```config/app.php``` file:
```php
/*
|--------------------------------------------------------------------------
| Exceptions' Center environment data
|--------------------------------------------------------------------------
|
| This array is used in the Exception's Center to correctly use the
| sender. These values are not required here: you could manage your
| own sender using the publish command. You will able to define in
| the generated file the following parameters:
|
*/
'exceptions' => [
    /*
     * Key of the project.
     */
    'key' => 'project-key',

    /*
     * Determine whether an incoming exception will
     * be sent to the Exceptions' Center.
     */
    'enabled' => true,

    /*
     * Determine whether the threads are allowed to
     * send the request to the Exceptions' Center.
     */
    'multithreading' => true,
    
    /*
     * Determine whether an error during the sending
     * should be saved in the storage/logs/exceptions.log 
     * file.
     */
    'log' => true,

    /*
     * If you published the ExceptionSender, you need
     * to declare the path until the generated file,
     * wherever you put it.
     */
    'model' => \ExceptionsCenter\Laravel\ExceptionSender::class,
],
```

*Note :* don't forget to execute ```php artisan config:clear``` to take the changes into account.

##### Using published sender
Another way is to publish the exceptions sender to customize all the possible methods. You can generate a ```app/Exceptions/ExceptionSender.php``` file using:
```bash
php artisan vendor:publish --tag=exception-sender
```

*Note :* If you chose to publish the exceptions sender, you need to define the 'model' key in the previous app configurations, otherwise the test command won't use your updates.

### Customisation
If you defined the configurations (previous step) and published the sender, the config array will be taken into account.

##### Settings
You need to define the **project key**, available in the Exceptions' Center website.

##### Multithreading
The current package allows sending the exceptions using a thread to save execution time. You can enable or disable this feature in the app config file at the key *multithreading* or in your published ExceptionSender with the *multithreading* property. 

*Note :* if an error occurred during the starting of the thread, then the exceptions will be sent using cURL. 

##### User information
You can define all the information you want about the user with the methods ```getUserInformation()``` and ```getGuestInformation()```. Please, be careful regarding the data you send. Don't forget user's sensitive information should not leave your application, even for debuging.
If you are using another auth guardian than the Laravel default Auth, you need to override the ```userIsLoggedIn()``` method.

##### Event listener
If you are using an other package that fire events when an exception is caught, then you can use the ```IncomingExceptionListener``` to handle this event.
In your ```EventServiceProvider```, you just have to add:
```php
protected $listen = [
    IncomingExceptionEvent::class => [
        IncomingExceptionListener::class
    ],
];
```
where ```IncomingExceptionEvent``` is the event name. This event needs to implement ```getException()``` and ```getRequest()``` methods.

### Test
You can check whether the package is working with the Artisan command:
```bash
php artisan exception:test
```
*Note :* Did you execute ```php artisan config:clear``` ?

## Architecture
This is the files architecture of the package:

```
.
├── composer.json
├── LICENSE
├── README.md
└── src
    ├── API
    │   ├── Contracts
    │   │   ├── ExceptionUserContract.php
    │   │   └── Reportable.php
    │   ├── ExceptionLog.php
    │   ├── Receiver
    │   │   └── ExceptionResponse.php
    │   ├── Request
    │   │   └── cUrl.php
    │   ├── Sender
    │   │   ├── AbstractExceptionSender.php
    │   │   ├── ExceptionFormatter.php
    │   │   ├── HasExceptedParameters.php
    │   │   ├── LaravelExceptionSender.php
    │   │   └── ThreadSender.php
    │   └── UrlFormatter.php
    ├── Exceptions
    │   └── CheckExceptionCenterException.php
    ├── ExceptionsCenterServiceProvider.php
    ├── ExceptionSender.php
    └── Test
        └── ExceptionTestCommand.php

8 directories, 18 files
```

You can generate the previous tree using:
```bash
sudo apt install tree
tree -I '.git|vendor'
```

## Licence
This package is under the terms of the [MIT license](https://opensource.org/licenses/MIT).
