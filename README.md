# Project RnR

PHP Project for Fun and Profit

## Running the Project

Create an `.env` file in the root of the project. The `.sample.env` file
will guide you through.

After that, run docker compose in order to build the necessary containers.

```shell
docker-compose up
```

The database will be created and prepopulated with test values. All users'
passwords are set to `test`.

After the initialization of the containers navigate to 
http://localhost:3333/build and enjoy.


