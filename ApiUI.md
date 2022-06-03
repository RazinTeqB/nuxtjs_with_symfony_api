#### [Go BackTo ReadMe](ReadMe.md)

### Swagger Ui Documentation

- Visit [Symfony server](http://localhost:8000/api/)
- Here all available endpoints are listed
- To test any api endpoint
  - Generate jwt token with username and password
    - In [Token Section](http://localhost:8000/api#operations-tag-Token) Under post `/authentication_token` click on `Try It Out` button
    - In request body add `username` and `password`
    ```
        {
            "username": "test@email.com",
            "password": "1234"
        }
    ```
    - Click Execute and you will get a jwt token
    - Copy response token value
    - On top click on `Authorize` button
    - In value section add `Bearer <token>`
    - then click `Authorize` button and close this popup
    - now you can test api endpoints with `Try It Out` button in respective section
