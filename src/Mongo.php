<?php
declare(strict_types=1);

namespace Pimenvibritania\Yii3Mongodb;

use MongoClient;
use MongoDB\Collection;
use MongoDB\Database;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Arrays\ArrayHelper;

class Mongo extends MongoClient
{
    public function __construct(
        private string $uri,
        private string $host,
        private string $username,
        private string $password,
        private string $ssl,
        private string $databaseName,
        private bool $retryWrites,
        private bool $tlsAllowInvalidCertificates,
        private array $collectionsName,
        private Aliases $aliases
    )
    {
        $url = $this->uri;
        $opt = [];

        if ($this->host) {
            $url = sprintf('mongodb://%s', $this->host);
            $opt = [
                'username' => $this->username,
                'password' => $this->password,
                'retryWrites' => $this->retryWrites,
                'tls' => true,
            ];
        }
        if ($this->ssl)
            $opt = ArrayHelper::merge($opt,
                [
                    'tlsAllowInvalidCertificates' => $this->tlsAllowInvalidCertificates,
                    'tlsCAFile' => $this->aliases->get($this->ssl)
                ]
            );
        parent::__construct($url, $opt);
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->{$this->databaseName};
    }

    /**
     * @param string $collectionName
     * @return Collection
     */
    public function getCollection(string $collectionName): Collection
    {
        $col = ArrayHelper::getValue($this->collectionsName, $collectionName);
        return $this->getDatabase()->{$col};
    }
}