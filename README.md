# Smindle

Smindle assignment

## Get started

1) Clone the git repository

2) In folder /smindle/ create a file ``.env`` and copy the contents of the ``.env.example``

3) Run command ``docker-compose up -d`` to run docker containers

4) Run command ``docker exec <container_id> php artisan migrate`` for create tables in DB

5) Run command ``docker exec <container_id> php artisan queue:work`` for start Laravel queue

\* Laravel ``container_id`` you can get by running command ``docker ps``

## Checking of assignment results

1) Make request POST http://localhost:8000/api/order/ with JSON payload

```json
{
    "first_name": "John",
    "last_name": "Doe",
    "address": "Someaddress 12 B 3",
    "basket": [
        {
            "name": "Cool merch",
            "type": "unit",
            "price": 295
        },
        {
            "name": "Sample Subscription",
            "type": "subscription",
            "price": 175
        }
    ]
}
```

2) Check in the DB that new records were created in tables ``orders`` and ``basket_items``

3) Wait 5-7 seconds and check in file ``/storage/logs/laravel.log`` that the request to third party slow API made and results received

4) Make request POST http://localhost:8000/api/order/ without any payload

5) Check that you get validation errors in the response