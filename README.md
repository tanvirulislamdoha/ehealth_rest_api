# eHealth API

eHealth API is a CRUD (Create, Read, Update, Delete) API built with PHP that allows users to store and fetch medical records. It also includes a token-based authentication system for added security.

## Features

- CRUD functionality for medical records
- Token-based authentication system
- RESTful API architecture

## Installation

1. Clone the repository: `git clone https://github.com/yourusername/ehealth-api.git`
2. Create a database for the system
3. Import the SQL file located at `database/ehealth_api.sql`
4. Edit the database configuration file located at `app/config/database.php` with your database credentials
5. Start the system by navigating to the project directory and running `php -S localhost:8000`

## Usage

1. Generate a new token by making a POST request to the `/api/token` endpoint with your username and password in the request body. The response will contain a new token that you can use to access protected endpoints.
2. Use the token to access protected endpoints by including it in the `Authorization` header of your requests. The header should look like this:

