## Local setup

This application is built and tested against PHP 7.4 and MySQL 5.7. To verify that your local setup meets the requirements:

```
$ php -v
$ mysql -v
```

## Setup instructions

Clone this repository:

```
$ git clone git@github.com:earthyolafflutestick/mailerlite-legacy-app.git
```

Move into your repository folder:

```
$ cd mailerlite-legacy-app
```

Copy the `.env.example` file to `.env`:

```
$ cp .env.example .env
```

The application looks for a database named `mailerlite_legacy_app`. You can pick a different name — in that case, make sure to update the relevant entry in your `.env` file:

```
DB_DATABASE=mailerlite_legacy_app
```

Log into MySQL, and create the database:

```
$ mysql -u root;
mysql> CREATE DATABASE mailerlite_legacy_app;
mysql> exit;
```

Import the `api_keys.sql` file in your repository folder:

```
$ mysql -u root mailerlite_legacy_app < api_keys.sql
```

If you haven't yet, install [Composer](https://getcomposer.org/doc/00-intro.md). Then install the application dependencies:

```
$ composer install
```

Generate your application secret key:

```
$ php artisan key:generate
```

Finally, serve your application:

```
$ php artisan serve
```

## Notes
* The application has "legacy" in its name because it uses [this](https://developers-classic.mailerlite.com/docs) version of the MailerLite API, which is prefixed with `/v2`, but isn't quite the same as [this other](https://developers.mailerlite.com/docs/#mailerlite-api) "v2", which offers different endpoints and a better design.
* I actually started implementing this with the "better" version of the API, and then switched at a pretty advanced stage. I published everything I had (which is still decently functional) to a [separate repository](https://github.com/earthyolafflutestick/mailerlite-test-app).
* The application uses [Bulma](https://bulma.io/) as its CSS framework. That's also the name of my bulldog.
