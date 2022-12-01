<?php

namespace mqServer\Service\elasticSearch;


use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Namespaces\IndicesNamespace;

trait BaseElasticSearchService
{

    protected $client = null;

    public function getClient(): Client
    {
        if ($this->client === null){
            $builder = ClientBuilder::create();
            $builder->setHosts([MAC_IP]);
            $this->client = $builder->build();
        }
        return $this->client;
    }

    public function getIndicesClient():IndicesNamespace
    {
        return $this->getClient()->indices();
    }

}


