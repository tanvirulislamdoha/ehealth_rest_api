# eHealth API

eHealth API is a CRUD API built with PHP that allows users to store and fetch medical records. It also includes a token-based authentication system for added security.

## Features

- CRUD functionality for medical records
- Token-based authentication system
- RESTful API architecture

## Installation

1. Clone the repository: `[git clone https://github.com/yourusername/ehealth-api.git]`
2. Create a database for the system nammed `national_server`
3. Import the SQL file to created `national_server` from `SQL_FILE/ehealth_api.sql`
4. Add some data to `medical_record` table

## Usage

1. Generate a new token by making a GET request to the `http://localhost/ehealth_rest_api-main/medical_record/token_builder.php?` endpoint with your `nid`. The response will contain a new `token` and a `token_id` that you can use to access protected data.
2. Receive your data using GET method and request to the `http://localhost/ehealth_rest_api-main/medical_record/create_token.php?` endpoint with your `nid` and generated `token` and `token_id`. The response will contain data from medical records associated with this nid and token.


