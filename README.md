# Yii3 Mongodb
MongoDB Service for Yii3 API.
## Requirement
- PHP Mongo extension already installed 
## Installation
```shell
composer require composer require pimenvibritania/yii3-mongodb
```
## Configuration
Add this to your `DI` configuration:
```php
<?php

use Pimenvibritania\Yii3Mongodb\Mongo; 
    
return [
   
    // Your code ...
   
    Mongo::class => [
        'class' => Mongo::class,
        '__construct()' => [
            $params['mongodb/mongodb']['uri'],
            $params['mongodb/mongodb']['host'],
            $params['mongodb/mongodb']['username'],
            $params['mongodb/mongodb']['password'],
            $params['mongodb/mongodb']['ssl'],
            $params['mongodb/mongodb']['database'],
            $params['mongodb/mongodb']['retryWrites'],
            $params['mongodb/mongodb']['tlsAllowInvalidCertificates'],
            $params['mongodb/mongodb']['collections'],
        ]
    ],
];
```
Add this to your `params-local.php`
```php
<?php

return [
    // Your code ...
    
    "mongodb/mongodb" => [
        "uri" => $_ENV["MONGODB_BASE_URL"],
        "host" => $_ENV["MONGODB_HOST"],
        "username" => $_ENV["MONGODB_USERNAME"],
        "password" => $_ENV["MONGODB_PASSWORD"],
        "database" => $_ENV["MONGODB_DATABASE"],
        "ssl" => "@root/rds-combined-ca-bundle.pem", //SSL configuration path if needed
        "tlsAllowInvalidCertificates" => true,
        "retryWrites" => false,
        "collections" => [
            "yourCollectionAlias" => "yourCollection",
        ],
    ],
];

```
Then add this key to your `.env`

```dotenv
MONGODB_BASE_URL=
MONGODB_HOST=
MONGODB_USERNAME=
MONGODB_PASSWORD=
MONGODB_DATABASE=
```

## Usages
example:
```php
<?php
use Pimenvibritania\Yii3Mongodb\Mongo as MongoClient; 

Class example {
    public function __construct(private MongoClient $mongoClient){}
    
    public function getAll(): array
    {
        return $this->collection()->find(
            [],
            [
                'sort' => [
                    'createdAt' => -1,
                ]
            ]
        )->toArray();
    }
    
    private function collection(): \MongoDB\Collection
    {
        return $this->mongoClient->getCollection("your_collection");
    }
}
```