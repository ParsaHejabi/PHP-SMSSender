
# PHP SMSSender

This repository was created for developing an SMS sender simulator which works with 2 SMS sender APIs and has a logging and report system.

## Technologies used:

* **Symfony Skeleton** and also used Symfony Components like: **"Messenger"**
* **Doctrine**
* **RabbitMQ**

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Installing

1- Download and install [RabbitMQ](https://www.rabbitmq.com/) for your platform.

2- Download and install a PHP development environment like [XAMPP](https://www.apachefriends.org/index.html).

3- Install amqp PHP extenstion using terminal command:

```
pecl install amqp
```

or manually download the `Thread Safe (TS) x86` version for your PHP version from [this link](https://pecl.php.net/package/amqp) and install:

* Extract the zip file.
* Copy `rabbitmq.4.dll` in `C:/Windows/System32` and `C:/Windows/SysWOW64` folders.
* Copy `php_amqp.dll` in `C:/xampp/php/ext` folder.
* Open `C:/xampp/php/php.ini` file and in the `Extension` section add the following line:

```
extension=amqp
```

4- Download and install [Composer](https://getcomposer.org/).

5- Create `digi` folder in `C:/xampp/htdocs/` and clone the repository in there.

6- Go to the project directory and customize `DATABASE_URL` in `.env` file. `db_user` and `db_password` have to be changed to your database username and password.

```
# customize this line!
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/digi"
```

7- In terminal enter these commands:

```
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

8- To run the project first open XAMPP and start Apache and MySQL and then enter 2 terminal sessions:

```
# terminal 1:
php bin/console server:run
# terminal 2:
php bin/console messenger:consume-messages amqp
```

## Running the tests

For sending an sms use:

```
localhost:8000/sms/send?number=&body=
```

This api sends the SMS to the first API if the first API can't handle then the second API will handle it.

For seeing the report page use:

```
localhost:8000/digi/src/View
```

**Important note:** to use the report page you have to Enable `Cross-Origin Resource Sharing` in your browser and Server `.conf` files. for example in Chrome you can download `Allow-Control-Allow-Origin: *` extension or use [this link](https://enable-cors.org/).
