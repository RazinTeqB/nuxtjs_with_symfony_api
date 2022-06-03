# Api demo project using Symfony with Api Platform package and nuxtJs as frontend

## Installtion

### Step 1: Clone Repository

`git clone https://github.com/RazinTeqB/nuxtjs_with_symfony_api.git`

`cd nuxtjs_with_symfony_api`

### Step 2: Back End Setup

- ### Step 2.1: Change directory to backend

  `cd backend`

- ### Step 2.3: Make a copy of .env.example file

  `cp .env.example .env`

- ### Step 2.2: Install Dependencies

  `composer install`

- ### Step 2.4: Set Database Credentials in .env file

- ### Step 2.5: Run database migration

  `php bin/console doctrine:schema:update --force`

- ### Step 2.6: Run the database fixture to load default user

  `php bin/console doctrine:fixtures:load`

- ### Step 2.7: Generate JWT Public/Secret Key [For more info visit [LexikJWTAuthenticationBundle Installation](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/index.rst#installation)]

  `php bin/console lexik:jwt:generate-keypair`

- ### Step 2.8: Install Node Packages

  `npm install`

- ### Step 2.9: Generate Build

  `npm run dev`

- ### Step 2.10: Start the server (with symfony cli)

  `symfony server:start`

### Step 3: Front End Setup

- ### Step 3.1: Change directory to front end

  `cd ../frontend`

- ### Step 3.2: Install Dependencies

  `npm install`

- ### Step 2.3: Make a copy of .env.example file

  `cp .env.example .env`

- ### Step 3.3: Set backend api url in frontend/.env file

  - `API_URL="http://<HOST>:<PORT>"`
  - `Note: If`API_URL`not set then http://localhost:8000 will be used` ([Configured In Nuxt Config File](frontend/nuxt.config.js?plain=1#L51))

- ### Step 3.4: Run build frontend

  `npm run build`

- ### Step 3.5: start nuxt server

  `npm run start`

- ### Visit generated site url in your browser

- ### Login with
  - username: test@email.com
  - password: 1234
