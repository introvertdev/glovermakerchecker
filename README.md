
# Glover Maker-Checker

Using Laravel 8 PHP framework, I have developed an API for an administrative
system that makes use of maker-checker rules for creating, updating and deleting user data.

#### This API is also accessible at https://techdomot.com/glovermakerchecker/api

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


## API Reference

#### Adminstrator signup

```http
  POST /api/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Adminstrator's email address|
| `password` | `string` | **Required**. Adminstrator's password|

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
