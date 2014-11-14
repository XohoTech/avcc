<?php
namespace Application\Bundle\FrontBundle\Helper;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

class Sphinx
{
    private $conn;
    
    public function __construct($params)
    {
        $this->conn = new Connection();
        $this->conn->setParams(array('host' => $params['host'], 'port' => $params['port']));
    }
    
    public function insert($indexName, $data){
        $sq = SphinxQL::create($this->conn)->insert()->into($indexName);
        return $sq->set($data);
    }
}