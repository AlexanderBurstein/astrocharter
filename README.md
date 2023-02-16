<<<<<<< HEAD
<h1 align="center">Welcome to kd_astro_glance 👋</h1>
<p>
</p>

> Not a commercial project, meant only to illustrate the author's professional skills

## Operating system

- The project is produced and tested only on Linux.
    Some of technologies used here are to be used on Windows slightly in different way

## Requirements

- PHP >= 7.4
- GD PHP Extension
- MYSQL >= 5
- And the [usual Symfony application requirements](https://symfony.com/doc/current/reference/requirements)
 (Make sure that doctrine, phpunit bundles, symfony/form are added properly)

## Install

1. Clone the project from GitHub:

        $ cd YOUR_WEBSITE_FOLDER
        $ git clone https://github.com/AlexanderBrovchenko/kd_astro_glance.git

 2a. (if needed) Install Composer (see http://getcomposer.org/download)

 2. Install the project via Composer

        $ cd kd_astro_glance
        $ composer install

 3. Make sure the swiss ephemerides library works properly:
    following command should give big table of float numbers without any error messages.
    The good thing: in case swetest is not ready it's to recommend a command line to activate the library ($ sudo apt install libswe-dev)

    $ swetest -edirsrc/Services/sweph -j2459172.5 -p0123456789DAttt -eswe -house46.6,24.72,p -ut16:45 -flsj -g, -head

  4. Create .env.local from .env file and fill in your database credentials

    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=db_version

    Make sure the user with given name/password has full MySql permission options

   5. Run

    $ php bin/console doctrine:database:create

    $ php bin/console doctrine:migrations:migrate

    $ php bin/console doctrine:fixtures:load

 6. Run

        $ sudo symfony server:start

 7. Open "http://127.0.0.1:8000/person" URL in your browser or connect to it from some mobile device

## Testing

After preparing env.local.test DATABASE_URL line and running command lines from part 5 above
(i suppose it's recommended to place the line in .env file) run

  $ ./bin/phpunit

 ## Author

👤 **Alexander Brovchenko**

* Github: [@AlexanderBrovchenko](https://github.com/AlexanderBrovchenko)
* LinkedIn: [@alexander-brovchenko-b48a8a6](https://linkedin.com/in/alexander-brovchenko-b48a8a6)

## Show your support

Give a ⭐️ if this project helped you!
=======
# kd_astro_glance
A simple natal horoscopes builder with some explanations giving
>>>>>>> a64fa74c4c8ff368d75b87c313db30e7e984d7f6
