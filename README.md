# Laravel Support Package
Composer package for Laravel 5.0 and above to allow for feedback and support requests within an application.

This package adds the ability to accept support requests out of the box with minimal code updates. Information about the currently-authenticated user will also be included with the messages.

MySQL database functionality is enabled by default to promote storage and persistence of messages that are sent out. This functionality is optional, however, so this package does not require a database in order to perform the sending of requests.

**NOTE:** This package relies on the [mail functionality provided by Laravel](#mail-documentation) and the settings can differ based upon Laravel version. Please ensure your mail settings in your `.env` file are valid.

## Table of Contents

* [Installation](#installation)
    * [Composer, Environment, and Service Provider](#composer-environment-and-service-provider)
        * [Composer](#composer)
        * [Environment](#environment)
        * [Service Provider](#service-provider)
    * [Route Installation](#route-installation)
        * [Laravel 5.1 and above](#laravel-51-and-above)
        * [Laravel 5.0](#laravel-50)
    * [Publish Everything](#publish-everything)
* [Required Environment Variables](#required-environment-variables)
* [Optional Environment Variables](#optional-environment-variables)
* [Routing](#routing)
    * [Display Feedback Form](#display-feedback-form)
    * [Process Feedback Form](#process-feedback-form)
    * [Display Support Request Form](#display-support-request-form)
    * [Process Support Request Form](#process-support-request-form)
* [Migrations](#migrations)
    * [Feedback Submissions](#feedback-submissions)
    * [Support Request Submissions](#support-request-submissions)
* [Models](#models)
    * [Feedback Submissions](#feedback-submissions-1)
    * [Support Request Submissions](#support-request-submissions-1)
* [Custom Messages](#custom-messages)
* [Custom Form Requests](#custom-form-requests)
    * [Feedback Form Request](#feedback-form-request)
        * [Validation Rules](#validation-rules)
        * [Validation Messages](#validation-messages)
    * [Support Form Request](#support-form-request)
        * [Validation Rules](#validation-rules-1)
        * [Validation Messages](#validation-messages-1)
* [Sending Mail](#sending-mail)
    * [Custom Mailable Instances](#custom-mailable-instances)
        * [Feedback Mailable](#feedback-mailable)
        * [Support Request Mailable](#support-request-mailable)
    * [Mail Facade Fallback](#mail-facade-fallback)
* [Controllers](#controllers)
    * [Feedback Controller](#feedback-controller)
    * [Support Request Controller](#support-request-controller)
* [Views](#views)
    * [Emails](#emails)
    * [Forms](#forms)
* [Resources](#resources)
    * [Mail Documentation](#mail-documentation)
    * [Queue Documentation](#queue-documentation)
    * [Localization Documentation](#localization-documentation)
    * [Form Request Validation Documentation](#form-request-validation-documentation)

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
FEEDBACK_FROM_ADDR=donotreply@example.com
FEEDBACK_FROM_NAME=Do Not Reply

SUPPORT_FROM_ADDR=donotreply@example.com
SUPPORT_FROM_NAME=Do Not Reply

FEEDBACK_TITLE=New Feedback Submission
SUPPORT_TITLE=New Support Request

SUBMITTER_ID_ATTR=id
SUBMITTER_NAME_ATTR=name
SUBMITTER_EMAIL_ATTR=email

ALLOW_APPLICATION_NAME_OVERRIDE=false
SEND_COPY_TO_SUBMITTER=false

ENABLE_DB=true
```

If you want to keep the defaults for the given values, you do not need to include them in your `.env` file.

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

### Publish Everything

Finally, run the following Artisan command to publish everything:

```
php artisan vendor:publish
```

The following assets are published:

* Configuration (tagged as `config`) - these go into your `config` directory
* Migrations (tagged as `migrations`) - these go into your `database/migrations` directory
* Models (tagged as `models`) - these go into your `app` directory
* Messages (tagged as `lang`) - these go into your `resources/lang/en` directory as `support.php`
* Views (tagged as `views`) - these go into your `resources/views/vendor/support` directory

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

### FEEDBACK_FROM_ADDR

The email address to use as the sender when sending a feedback message.

If this value has not been specified, the value of `MAIL_FROM` in your `.env` value will be used instead. If there is no `MAIL_FROM` value to use as a fallback, an exception will be thrown upon sending the feedback message.

Default value is either the `MAIL_FROM` value or `null`.

### FEEDBACK_FROM_NAME

The email display name to use when sending a feedback message.

Default is `Do Not Reply`.

### SUPPORT_FROM_ADDR

The email address to use as the sender when sending a support request message.

If this value has not been specified, the value of `MAIL_FROM` in your `.env` value will be used instead. If there is no `MAIL_FROM` value to use as a fallback, an exception will be thrown upon sending the support request message.

Default value is either the `MAIL_FROM` value or `null`.

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

### ALLOW_APPLICATION_NAME_OVERRIDE

Determines whether the name of the application reported in the message can be overridden by a request input value with the name of
`application_name`.

If this is set to `true`, it can promote the creation of a central support request system that allows the user to pick the application where the issue arose, for example. If the request value does not exist, the value of the `app.name` configuration entry will be used instead.

Default value is `false`.

### SEND_COPY_TO_SUBMITTER

Boolean that describes whether the submitter should receive a copy of the message that was sent.

Default is `false`.

### ENABLE_DB

MySQL database support is enabled by default so you will need to have a valid database connection. Database support can be disabled by setting this value to `false`. This package can still perform the sending of email messages even with database support disabled.

The database tables where the feedback and support submissions will be stored is determined by the published models.

The migrations must be run prior to any database queries.

Default is `true`.

## Routing

In all cases for the routes exposed by the package, you are free to modify the path of the route but keep these two constraints in mind:

1. Please **do not** modify the HTTP method of the routes unless you are also planning to modify the published views.
2. Please **do not** modify the route names since both the underlying controller functionality as well as the published views use them.

### Display Feedback Form

* Path: `/feedback`
* HTTP method: `GET`
* Route name: `feedback.create`

### Process Feedback Form

* Path: `/feedback`
* HTTP method: `POST`
* Route name: `feedback.store`

### Display Support Request Form

* Path: `/support`
* HTTP method: `GET`
* Route name: `support.create`

### Process Support Request Form

* Path: `/support`
* HTTP method: `POST`
* Route name: `support.store`

## Migrations

There are two migrations that are included with this package. They are intended to store information about the feedback and support request submissions that have been received from the application.

The name of the application from which the message was submitted as well as the ID of the authenticated user are stored in both cases as well.

You are also not required to use these migrations. The associated models can be pointed at any database table as long as they match the same structure.

### Feedback Submissions

Table name is `feedback_submissions`.

* `submission_id`: auto-incrementing integer primary key
* `application_name`: nullable name of the application from where the message was submitted; defaults to the value of the `app.name` config element
* `user_id`: ID of the authenticated user that submitted the message
* `content`: body text of the feedback message
* `created_at`: timestamp describing when the message was sent
* `updated_at`: nullable timestamp describing when the message was updated, if at all

### Support Request Submissions

Table name is `support_submissions`.

* `submission_id`: auto-incrementing integer primary key
* `application_name`: nullable name of the application from where the message was submitted; defaults to the value of the `app.name` config element
* `user_id`: ID of the authenticated user that submitted the message
* `impact`: the impact of the issue resulting in the request (could be `low`, `medium`, or `high` for example)
* `content`: body text of the support request message
* `created_at`: timestamp describing when the message was sent
* `updated_at`: nullable timestamp describing when the message was updated, if at all

## Models

The models that will be used to store the feedback and support request submissions can be configured in the published `config/support.php` file within the `database.models` section.

Exceptions will be thrown if the models cannot be found when used in the controllers. However, the email messages themselves will still go out.

The tables and primary keys of each model can also be configured from within the models themselves.

### Feedback Submissions

The full namespace to the model is `CSUNMetaLab\Support\Models\FeedbackSubmission`.

* Table: `feedback_submissions`
* Primary key: `submission_id`
* Fillable: `application_name`, `user_id`, `content`

The exception that may be thrown is an instance of `CSUNMetaLab\Support\Exceptions\FeedbackModelNotFoundException`.

### Support Request Submissions

The full namespace to the model is `CSUNMetaLab\Support\Models\Supportubmission`.

* Table: `support_submissions`
* Primary key: `submission_id`
* Fillable: `application_name`, `user_id`, `impact`, `content`

The exception that may be thrown is an instance of `CSUNMetaLab\Support\Exceptions\SupportModelNotFoundException`.

## Custom Messages

The custom messages for this package can be found in `resources/lang/en/support.php` by default. The messages can also be overridden as needed.

You may also translate the messages in that file to other languages to promote localization, as well.

The package reads from this file (using the configured localization) for all messages it must display to the user or write to any logs.

## Custom Form Requests

The controllers leverage custom form request classes in order to accept and process the input. Each form request exposes custom validation rules and error messages.

### Feedback Form Request

This class is namespaced as `CSUNMetaLab\Support\Http\Requests\FeedbackFormRequest`.

Most of the data required for processing will be added by the matching controller so there are not many validation rules for this request.

#### Validation Rules

* `content.required`: the `content` field must have a non-null value in the request

#### Validation Messages

* `support.errors.v.feedback.content.required`: the `content` field has no input

### Support Form Request

This class is namespaced as `CSUNMetaLab\Support\Http\Requests\SupportFormRequest`.

#### Validation Rules

* `impact.required`: the `impact` field must have a non-null value in the request
* `impact.in`: the value of the `impact` field be within the array values in the `impact` key within `config/support.php`
* `content.required`: the `content` field must have a non-null value in the request

#### Validation Messages

* `support.errors.v.support.impact.required`: the `impact` field has no input
* `support.errors.v.support.impact.in`: the value of the `impact` field is invalid
* `support.errors.v.support.content.required`: the `content` field has no input

## Sending Mail

The feedback and support request messages will be sent differently depending on the version of Laravel in which the package has been installed.

In all cases, however, the messages will always request to be queued so this functionality can be used in conjunction with [some kind of Laravel queue](#queue-documentation) if necessary.

### Custom Mailable Instances

Please note that the custom queueable instances of `Illuminate\Mail\Mailable` will only be used if that class exists.

Therefore, these instances will only be used in Laravel 5.3 and above. 

#### Feedback Mailable

This class is namespaced as `CSUNMetaLab\Support\Mail\FeedbackMailMessage`.

When building this mailable, the `emails.feedback` view will be used inside of the `resources/views/vendor/support` directory.

The following variables are exposed to the view via public properties in the class:

* `$submitter_name`: the name of the individual submitting the message
* `$submitter_email`: the email address of the individual submitting the message
* `$application_name`: the name of the application from where the message is being submitted
* `$content`: the body content of the message

#### Support Request Mailable

This class is namespaced as `CSUNMetaLab\Support\Mail\SupportMailMessage`.

When building this mailable, the `emails.support` view will be used inside of the `resources/views/vendor/support` directory.

The following variables are exposed to the view via public properties in the class:

* `$submitter_name`: the name of the individual submitting the message
* `$submitter_email`: the email address of the individual submitting the message
* `$application_name`: the name of the application from where the message is being submitted
* `$impact`: the impact of the issue that resulted in the support request
* `$content`: the body content of the message

### Mail Facade Fallback

The `Mail` facade will be used in conjunction with its `queue()` method in Laravel 5.0 - 5.2 since the concept of mailables did not yet exist.

## Controllers

### Feedback Controller

TBD

### Support Request Controller

TBD

## Views

### Emails

TBD

### Forms

TBD

## Resources

### Mail Documentation

* [Mail in Laravel 5.0](https://laravel.com/docs/5.0/mail)
* [Mail in Laravel 5.1](https://laravel.com/docs/5.1/mail)
* [Mail in Laravel 5.2](https://laravel.com/docs/5.2/mail)
* [Mail in Laravel 5.3](https://laravel.com/docs/5.3/mail)
* [Mail in Laravel 5.4](https://laravel.com/docs/5.4/mail)
* [Mail in Laravel 5.5](https://laravel.com/docs/5.5/mail)

### Queue Documentation

* [Queues in Laravel 5.0](https://laravel.com/docs/5.0/queues)
* [Queues in Laravel 5.1](https://laravel.com/docs/5.1/queues)
* [Queues in Laravel 5.2](https://laravel.com/docs/5.2/queues)
* [Queues in Laravel 5.3](https://laravel.com/docs/5.3/queues)
* [Queues in Laravel 5.4](https://laravel.com/docs/5.4/queues)
* [Queues in Laravel 5.5](https://laravel.com/docs/5.5/queues)

### Localization Documentation

* [Localization in Laravel 5.0](https://laravel.com/docs/5.0/localization)
* [Localization in Laravel 5.1](https://laravel.com/docs/5.1/localization)
* [Localization in Laravel 5.2](https://laravel.com/docs/5.2/localization)
* [Localization in Laravel 5.3](https://laravel.com/docs/5.3/localization)
* [Localization in Laravel 5.4](https://laravel.com/docs/5.4/localization)
* [Localization in Laravel 5.5](https://laravel.com/docs/5.5/localization)

### Form Request Validation Documentation

* [Form Request Validation in Laravel 5.0](https://laravel.com/docs/5.0/validation#form-request-validation)
* [Form Request Validation in Laravel 5.1](https://laravel.com/docs/5.1/validation#form-request-validation)
* [Form Request Validation in Laravel 5.2](https://laravel.com/docs/5.2/validation#form-request-validation)
* [Form Request Validation in Laravel 5.3](https://laravel.com/docs/5.3/validation#form-request-validation)
* [Form Request Validation in Laravel 5.4](https://laravel.com/docs/5.4/validation#form-request-validation)
* [Form Request Validation in Laravel 5.5](https://laravel.com/docs/5.5/validation#form-request-validation)