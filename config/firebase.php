<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

            // 'credentials' => env('FIREBASE_CREDENTIALS', env('GOOGLE_APPLICATION_CREDENTIALS')),
            'credentials' => [
                "type" => "service_account",
                "project_id" => "chat-pusher-3e2bc",
                "private_key_id" => "2eb3f1262a8f8516221faae330ecdd1a4c1d9a97",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC9/Gnund61DomX\nnCM6SnuSM95XzbZywwx3S8WPGdmiFPTeOZC+jAH7HHLXnPR9Yrb6LRQVBVazuv7o\nZOOHznyEWg65pPvRs9NCJeO41OEbAx/89vwkzZDJJ8M9s18TOXeBtX39QM37D696\nAV+HIvUHjiMlwrRQJLr3Ftbd+eXhHHBgzLCpAMNsz9NHpx0irt5Plm0GtefS6ng3\nablB1m66Ap6G9m/wzUuNa5LufSZqJWBP2mfqSgh9KnnSBJGp3qwZZrshGOaaSoBz\n/JEL5crBI2/yJwRCMxJkquTI48J1UtpKrRRmMEUsO8rAHujotPWQ62U+UM0khyuA\ng4XjDMF5AgMBAAECggEAS25fDtcOd/ucJJ5hHEE7W9qNMnZiNKF9nxXuC/ma/6/r\nYxoe/pCvLqjmbfYK2oepl53zRb9XY8fgcpuv/Rk+NfxdsVawhturiq1pg1a+Q4/T\nkQftJ52YmU1YxvQjUQo1iUHqknU68y4QoVPPPmPiPowwJ0ttdQ6ITxd52ytfmqcV\nSjHi1Dx4uEBzJkPklzUiDIoZCvO/R4LuKpoHz+LDqzzr1OWi0T39eYKqoDDh7ArF\nlAOZcmzjCjqenIKhRvooEtgI3e8PX5qKIaID6mWELnKFytrBY2bRFIXFV5xAJaS1\nmKCfQOeM5kkosqWUWGj4y36TWQHBKOaJbs8gdB8TCwKBgQDhixy+laK5R+zfBYfd\n1hp3Xbg+SRLPbbO+G9o3Q5j9Tmn9JC53+Ig+kz1Wq73j7a/fqKA5WeKUjsg+2FxW\nG9n4ZKmS1ltoaQ5btwjTXJnU9gku9ZQ/IzEPkEMhiftNihK4b/Iospauzfrma1/B\nyBR0+5T+0jJshGKLi2lBnAEHTwKBgQDXpBreezVq1WpMCMsWC7aDpYkGiSGyy+81\nvdaL+Zm3gk6o5PQJoYRCoEQf3lddeT/w7l3x4W9N4o0tMf1MKTH0A8KEagHhq/H4\nMq6NTOYO5DfNFirdQ1RGZX+ghbRH+mr2GsuvvwEsQLpWVdsPPWu7ZcowkjE3YahQ\nO8axHgT4twKBgQDOpzffKBvAyDYw4lBq4hG97REzgKDLjihyt/JpUHaNIZvbZZtu\nMTokm6RIFXQXs2DB/S9RXRpOI8U2T+fKvhopjZISCHYYZKnTPxHHF8GbbxNbA4ih\nkQ0SxYu830By+1/LvkaTI48M+444MYm7dCfKR/lswguRgnsIgOQgSWBn4QKBgEy4\nuUqQwYeaGLCmrELStgQOoDrwDUpE3n3bZfr1OiidNwlGNEE88waVaMeB3smF+ybo\nWApX77bhNONwlrNDMN+les6LvNGi0HtC5PGm+28u6V+RVHzWmKRXNlpRV7wIL4gS\nLof/yBYLjYBsGX1JmAvbgl1XliUjHLQ3IFEh4hjvAoGBAIhEUtOz0PN1IrU3l23h\nTlKf6zD+teCmGfHdjpsB8LsA1fbITd/9gnh3Ii0Ja/unhX0vsQp4EoPertr6lsi+\nFmpfcnJY8R9Q2cMqTt4YvzxRbfziixaH2V3JGSy6IIALP6DChWWoPPN5qv+5Th/6\nQhDcSSmQgTTzccHSCcXtUhpQ\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-e0j1p@chat-pusher-3e2bc.iam.gserviceaccount.com",
                "client_id" => "114292421627828506838",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-e0j1p%40chat-pusher-3e2bc.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"
            ],
            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firestore Component
             * ------------------------------------------------------------------------
             */

            'firestore' => [

                /*
                 * If you want to access a Firestore database other than the default database,
                 * enter its name here.
                 *
                 * By default, the Firestore client will connect to the `(default)` database.
                 *
                 * https://firebase.google.com/docs/firestore/manage-databases
                 */

                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
