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
    | Email senders
    |--------------------------------------------------------------------------
    |
    | The various names to use as senders for the feedback and support messages.
    | The "From" value will be determined by the MAIL_FROM env value outside
    | of this package.
    |
    */
    'senders' => [

        'feedback' => [

            'name' => env("FEEDBACK_FROM_NAME", "Do Not Reply"),

        ],

        'support' => [

            'name' => env("SUPPORT_FROM_NAME", "Do Not Reply"),

        ],
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
    | stored is determined by a published model. The migrations must be run
    | prior to any database queries.
    |
    */
    'database' => [

        'enabled' => env("ENABLE_DB", true),

    ],
];