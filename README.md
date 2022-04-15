
# Glover Maker-Checker [![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://ng.linkedin.com/in/oyetade-tobi)

Using Laravel 8 PHP framework, I have developed an API for an administrative
system that makes use of maker-checker rules for creating, updating and deleting user data.

#### Task Description
A maker-checker system revolves around the idea that for any change to be made to user
information by an administrator, it must be approved by a fellow administrator in order to take
effect; and if the request is declined, the change isn’t persisted. For each request submited, an email is sent to notify other administrators.
######
The user information (editable by the administrator) comprises of three details:
#####
● The user’s first name
#####
● The user’s last name
#####
● The user’s email address.


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
## API Reference

#### Adminstrator signup

```http
  POST /api/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Adminstrator's email address|
| `password` | `string` | **Required**. Adminstrator's password|
| `password_confirmation` | `string` | **Required**. Confirm Adminstrator's password|

#### Admistrator login

```http
  POST /api/login
```
 **NOTE:**  `Once logged in, an Authorization token (called TOKEN) will be generated which will be used for authentication on protected API routes`
| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Adminstrator's email address|
| `password` | `string` | **Required**. Adminstrator's password|


#### Admistrator logout

```http
  POST /api/logout
```
 **NOTE:**  `Once logged out, the Authorization token (called TOKEN) is destroyed.`
| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Adminstrator's email address|
| `password` | `string` | **Required**. Adminstrator's password|


#### Create a customer

```http
  POST /api/create-customer
```

 **Authorization**  `Set value to Bearer TOKEN`

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `first_name` | `string` | **Required**. Customer's first name|
| `last_name` | `string` | **Required**. Customer's last name|
| `email` | `string` | **Required**. Customer's email address|

#### View single customer

```http
  GET /api/find-customer/{id}
```

 **Authorization**  `Set value to Bearer TOKEN`

#### Create a "CREATE" request

```http
  POST /api/create-operation
```

 **Authorization**  `Set value to Bearer TOKEN`

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `first_name` | `string` | **Required**. Customer's first name|
| `last_name` | `string` | **Required**. Customer's last name|
| `email` | `string` | **Required**. Customer's email address|
| `request_type` | `enum` | **Required**. "create"|


#### Create an "UPDATE" request

```http
  POST /api/create-operation
```

 **Authorization**  `Set value to Bearer TOKEN`

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `first_name` | `string` | *Optional*. Customer's first name|
| `last_name` | `string` | *Optional*. Customer's last name|
| `email` | `string` | *Optional*. Customer's email address|
| `user_id` | `int` | **Required**. Customer's ID|
| `request_type` | `enum` | **Required**. "update"|


#### Create a "DELETE" request

```http
  POST /api/create-operation
```

 **Authorization**  `Set value to Bearer TOKEN`

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `first_name` | `string` | *Optional*. Customer's first name|
| `last_name` | `string` | *Optional*. Customer's last name|
| `email` | `string` | *Optional*. Customer's email address|
| `user_id` | `int` | **Required**. Customer's ID|
| `request_type` | `enum` | **Required**. "delete"|

#### View pending requests

```http
  GET /api/pending-operations
```

 **Authorization**  `Set value to Bearer TOKEN`

#### View single request

```http
  GET /api/find-operation/{id}
```

 **Authorization**  `Set value to Bearer TOKEN`

#### Approve request

```http
  PUT /api/approve-operation/{id}
```

 **Authorization**  `Set value to Bearer TOKEN`

#### Decline request

```http
  DELETE /api/reject-operation/{id}
```

 **Authorization**  `Set value to Bearer TOKEN`


