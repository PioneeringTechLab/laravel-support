<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Email recipient addresses
    |--------------------------------------------------------------------------
    |
    | The various addresses to use as recipients for the emails. Multiple
    | addresses can be specified if separated with a pipe character.
    |
    | Ex: info@example.com|help@example.com|feedback@example.com
    |
    */
    'recipients' => [

        'feedback' => env("FEEDBACK_RECIPIENT"),

        'support' => env("SUPPORT_RECIPIENT"),

    ],

    /*
    |--------------------------------------------------------------------------
    | Email titles
    |--------------------------------------------------------------------------
    |
    | The titles to use for the various emails that will be sent.
    |
    */
    'titles' => [

        'feedback' => env("FEEDBACK_TITLE", "New Feedback Submission"),

        'support' => env("SUPPORT_TITLE", "New Support Request"),

    ],

    /*
    |--------------------------------------------------------------------------
    | Submitter (User model) metadata
    |--------------------------------------------------------------------------
    |
    | The properties of the configured User model that will be used when adding
    | information to the feedback and support emails. The following are the
    | default attribute names to work with Laravel's default User model:
    |
    | Submitter ID (primary key): "id"
    | Submitter display name attribute: "name"
    | Submitter email attribute: "email"
    |
    */
    'submitter' => [

        'id' => env("SUBMITTER_ID_ATTR", "id"),

        'name' => env("SUBMITTER_NAME_ATTR", "name"),

        'email' => env("SUBMITTER_EMAIL_ATTR", "email"),

    ],

    /*
    |--------------------------------------------------------------------------
    | Should submitter be CC'd?
    |--------------------------------------------------------------------------
    |
    | Determines whether the user submitting the form(s) should also be CC'd
    | by default. Default value is false.
    |
    */
    'send_copy_to_submitter' => env("SEND_COPY_TO_SUBMITTER", false),

    /*
    |--------------------------------------------------------------------------
    | Views
    |--------------------------------------------------------------------------
    |
    | The views that will be used when rendering the email messages as well as
    | the front-end for the support forms. These can be customized further by
    | the implementing application. They are also in dot-notation format so
    | these values can be passed directly into the view() helper function.
    |
    */
    'views' => [

        'email' => [

            'feedback' => env("FEEDBACK_EMAIL_VIEW", "emails.support.feedback"),

            'support' => env("SUPPORT_EMAIL_VIEW", "emails.support.support"),

        ],

        'forms' => [

            'feedback' => env("FEEDBACK_FORM_VIEW", "pages.support.feedback"),

            'support' => env("SUPPORT_FORM_VIEW", "pages.support.support"),

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Support Impact Selections
    |--------------------------------------------------------------------------
    |
    | The values that will populate the "impact" drop-down field on the support
    | form view.
    |
    */
    'impact' => serialize(
        [
            'high' => 'High - Unable to work',
            'medium' => 'Medium - Can work, difficult workaround',
            'low' => 'Low - Minor issue',
        ]
    ),

    /*
    |--------------------------------------------------------------------------
    | Database support (optional)
    |--------------------------------------------------------------------------
    |
    | Database support is enabled by default so you will need to have a valid
    | database connection. This can be changed with the ENABLE_DB environment
    | value.
    |
    | The database tables where the feedback and support submissions will be
    | stored is configurable here. Make sure the migraitons are published and
    | run before attempting to save. The schema will be checked before attempting
    | to save to the database.
    |
    */
    'database' => [

        'enabled' => env("ENABLE_DB", true),

        'tables' => [

            'feedback' => env("FEEDBACK_DB_TABLE", 'feedback_submissions'),

            'support' => env("SUPPORT_DB_TABLE", 'support_submissions'),

        ],

    ],
];