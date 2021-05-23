# Project RnR

PHP Project for Fun and Profit

## Running the Project

1. Clone the repository and create a new configuration based on `.env.sample`. 
   Edit the configuration according to documenation

```shell
git clone https://github.com/mistericy/rnr
cd rnr && cp .env.sample .env
```

2. Install project's dependencies

```shell
composer install --optimize-autoloader --no-interaction --quiet
```

3. Install and build frontend
```shell
cd frontend && npm install && npm run build && cd ..
```

4. Run docker compose
```shell
docker compose up --build -d
```

5. Grab a cup of coffee.

6. Navigate to [http://localhost:3333/build](http://localhost:3333/build) and login as administrator
using the following credentials:
```
email: t.mpampouras@gavgav.gr
password: test 
```

Obviously you can change the details of the first user, which is the system's administrator,
**before building the containers**. You can also generate a new password by using PHP's
```password_hash``` function.

7. Have fun
