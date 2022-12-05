<?php

namespace mqServer\service\elasticSearch;


use Elasticsearch\ClientBuilder;
use GuzzleHttp\Ring\Client\MockHandler;
use PhpOffice\PhpSpreadsheet\Reader\Xls\ErrorCode;
use src\Error\ElasticSearchError;
use mqServer\rbmq\Service\BaseService;
use src\Service\elasticSearch\BaseElasticSearchService;
use think\Model;

class ElasticSearchService extends BaseService
{
    use BaseElasticSearchService;

    private $query = [];

    private $query_must = [];
    private $query_should = [];
    private $size = 10;

    /**
     * 增加数据
     * @param string $index 表/文档
     * @param array $data 数组
     * @return array|callable
     */
    public function insertData(string $index, array $data)
    {
        if (empty($data)) {
            throw (new ElasticSearchError('内容不能为空'));
        }
        $client = $this->getClient();
        foreach ($data as $datum) {
            $params['body'][] = ['index' => ['_index' => $index]];
            $params['body'][] = $datum;
        }
        return $client->bulk($params);
    }

    /**
     * 删除表
     * @param $index
     */
    public function deleteIndex($index)
    {
        $deleteParams = [
            'index' => $index,
        ];
        $this->getIndicesClient()->delete($deleteParams);
    }

    public function initQuery()
    {
        return $this->query;
    }

    /**
     * 类似sql的 ( and )
     * @param $columns
     * @return $this
     */
    public function mustParams($columns)
    {
        if (count($columns) > 1) {
            foreach ($columns as $item_column => $item_val) {
                $this->query_must[] = ['match' => [$item_column => $item_val]];
            }
        } else {
            foreach ($columns as $item_column => $item_val) {
                $this->query_must[] = [$item_column => $item_val];
            }
        }
        return $this;
    }

    /**
     * 类似sql的 ( or )
     * @param $columns
     * @return $this
     */
    public function shouldParams($columns)
    {
        var_dump($columns);
        if (count($columns) > 1) {
            foreach ($columns as $item_column => $item_val) {
//                $this->query_should[] = ['match' => [$item_column => $item_val]];
                $this->query_should[] = ['match_all' => [$item_column => $item_val]];
            }
        } else {
            foreach ($columns as $item_column => $item_val) {
                $this->query_should[] = [$item_column => $item_val];
            }
        }
        return $this;
    }

    public function fromIndex($index)
    {
        $this->query['index'] = $index;
        return $this;
    }

    public function limit($size)
    {
        $this->size = $size;
        return $this;
    }

    public function searchAll()
    {
        if (count($this->query_must) > 1) {
            $this->query['body']['query']['bool']['must'] = $this->query_must;
        } else {
            foreach ($this->query_must as $key => $item) {
                $key_item = array_keys($item)[0];
                $this->query['body']['query']['match'][$key_item] = $item[$key_item];
            }
        }
        $this->query['size'] = $this->size;

        return $this->getClient()->search($this->query,100);
    }
}


