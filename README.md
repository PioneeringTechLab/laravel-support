# Laravel Support Package
Composer package for Laravel 5.0 and above to allow for feedback and support requests within an application.

This package adds the ability to accept support requests out of the box with minimal code updates. Information about the currently-authenticated user will also be included with the messages.

MySQL database functionality is enabled by default to promote storage and persistence of messages that are sent out. This functionality is optional, however, so this package does not require a database in order to perform the sending of requests.

**NOTE:** This package relies on the [mail functionality provided by Laravel](https://laravel.com/docs/5.3/mail). Please ensure your mail settings in your `.env` file are valid.

## Table of Contents

* [Installation](#installation)
* [Required Environment Variables](#required-environment-variables)
* [Optional Environment Variables](#optional-environment-variables)
* [Routing](#routing)
* [Migrations](#migrations)
* [Models](#models)
* [Controllers](#controllers)
* [Views](#views)

## Installation

### Composer, Environment, and Service Provider

#### Composer

To install from Composer, use the following command:

```
composer require csun-metalab/laravel-support
```

#### Environment

Now, add the following line(s) to your `.env` file:

```
FEEDBACK_RECIPIENT=
SUPPORT_RECIPIENT=
```

You may also elect to add the following optional line(s) to your `.env` file to customize the functionality further. These values are shown with their defaults. They are explained in detail within the [Optional Environment Variables](#optional-environment-variables) section further down:

```
FEEDBACK_FROM_NAME=Do Not Reply
SUPPORT_FROM_NAME=Do Not Reply

FEEDBACK_TITLE=New Feedback Submission
SUPPORT_TITLE=New Support Request

SUBMITTER_ID_ATTR=id
SUBMITTER_NAME_ATTR=name
SUBMITTER_EMAIL_ATTR=email

SEND_COPY_TO_SUBMITTER=false

ENABLE_DB=true
```

#### Service Provider

Add the service provider to your `providers` array in `config/app.php` in Laravel as follows:

```
'providers' => [
   //...

   CSUNMetaLab\Support\Providers\SupportServiceProvider::class,

   // You can also use this based on Laravel convention:
   // 'CSUNMetaLab\Support\Providers\SupportServiceProvider',

   //...
],
```

### Route Installation

You will now need to add the various routes for the package. They are named routes since you can then customize the route paths based upon your own application. The package will use the route names instead of the paths when performing operations.

#### Laravel 5.1 and above

Add the following group to your `routes.php` or `routes/web.php` file depending on Laravel version to enable the routes:

```
Route::group(['middleware' => ['auth'], 'namespace' => '\CSUNMetaLab\Support\Http\Controllers'], function () {
  Route::get('support', 'SupportController@create')->name('support.create');
  Route::post('support', 'SupportController@store')->name('support.store');

  Route::get('feedback', 'FeedbackController@create')->name('feedback.create');
  Route::post('feedback', 'FeedbackController@store')->name('feedback.store');
});
```

#### Laravel 5.0

Add the following group to your `routes.php` file to enable the routes:

```
Route::group(['middleware' => ['auth'], 'namespace' => '\CSUNMetaLab\Support\Http\Controllers'], function () {
  Route::get('support', [
    'uses' => 'SupportController@create',
    'as' => 'support.create',
  ]);
  Route::post('support', [
    'uses' => 'SupportController@store',
    'as' => 'support.store',
  ]);
  Route::get('feedback', [
    'uses' => 'FeedbackController@create',
    'as' => 'feedback.create',
  ]);
  Route::post('feedback', [
    'uses' => 'FeedbackController@store',
    'as' => 'feedback.store',
  ]);
});
```

### Publish Configuration

Finally, run the following Artisan command to publish everything:

```
php artisan vendor:publish
```

The following assets are published:

* Configuration (tagged as `config`)
* Migrations (tagged as `migrations`)
* Models (tagged as `models`)
* Views (tagged as `views`)

## Required Environment Variables

You added two environment variables to your `.env` file that control the sending of messages via email.

### FEEDBACK_RECIPIENT

The address(es) to use as the recipient(s) for the feedback emails. Multiple recipients can be specified if separated with a pipe character.

Example (single): `feedback@example.com`

Example (multiple): `info@example.com|feedback@example.com`

### SUPPORT_RECIPIENT

The address(es) to use as the recipient(s) for the support request emails. Multiple recipients can be specified if separated with a pipe character.

Example (single): `support@example.com`

Example (multiple): `help@example.com|support@example.com`

## Optional Environment Variables

There are several optional environment variables that may be added to customize the functionality of the package even further.

### FEEDBACK_FROM_NAME

The email display name to use when sending a feedback message.

The "From" address will be determined by the `MAIL_FROM` environment value outside of this package.

Default is `Do Not Reply`.

### SUPPORT_FROM_NAME

The email display name to use when sending a support request message.

The "From" address will be determined by the `MAIL_FROM` environment value outside of this package.

Default is `Do Not Reply`.

### FEEDBACK_TITLE

The title of the email when sending a feedback message.

Default is `New Feedback Submission`.

### SUPPORT_TITLE

The title of the email when sending a support request message.

Default is `New Support Request`.

### SUBMITTER_ID_ATTR

The attribute in the configured `User` model that serves as its primary key.

Default is `id`.

### SUBMITTER_NAME_ATTR

The attribute in the configured `User` model that serves as its human-readable display name.

This value will be included when email messages are sent.

Default is `name`.

### SUBMITTER_EMAIL_ATTR

The attribute in the configured `User` model that serves as its email address.

This value will be included when email messages are sent.

Default is `email`.

### SEND_COPY_TO_SUBMITTER

Boolean that describes whether the submitter should receive a copy of the message that was sent.

Default is `false`.

### ENABLE_DB

MySQL database support is enabled by default so you will need to have a valid database connection. Database support can be disabled by setting this value to `false`. This package can still perform the sending of email messages even with database support disabled.

The database tables where the feedback and support submissions will be stored is determined by a published model.

The migrations must be run prior to any database queries.

Default is `true`.

## Routing

TBD

## Migrations

TBD

## Models

TBD

## Controllers

TBD

## Views

TBD