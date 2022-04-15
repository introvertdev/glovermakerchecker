
## Setup Guide
To setup the application in your environment, follow the steps below:
```javascript
1. clone repo run git clone https://github.com/introvertdev/glovermakerchecker.git
2. run composer install
3. run copy .env.example .env
4. configure your database settings in your .env file
5. run php artisan key:generate
6. run php artisan migrate
7. run php artisan serve 

```

## Email Settings
if you are using GMAIL, make sure you turn on "Less Secure App Access". You can do that from [HERE](https://myaccount.google.com/lesssecureapps?pli=1).
#####
Otherwise, you can just fill in your mail provider SMTP credentials.
##### use the .env MAIL settings to configure the email as below: 

```javascript
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=<your_gmail_username>
MAIL_PASSWORD=<your_gmail_password>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=<your_gmail_username>
MAIL_FROM_NAME="${APP_NAME}"
```
If you run into a TLS/SSL error, kindly change the following on your config/mail.php
```javascript
'smtp' => [
    'transport' => 'smtp',
    'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
    'port' => env('MAIL_PORT', 587),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'timeout' => null,
    'auth_mode' => null,
],

```

To this (I have added the "stream" array)
```javascript
'smtp' => [
    'transport' => 'smtp',
    'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
    'port' => env('MAIL_PORT', 587),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'timeout' => null,
    'auth_mode' => null,
    'stream' => [
        'ssl' => [
            'allow_self_signed' => true,
            'verify_peer' => false,
            'verify_peer_name' => false
        ],
    ],
],

```