<?php

namespace common\Lib;

use yii\base\Object;
use Hprose\Http\Client;
class RpcClient extends Object
{
	private $_url;
	private $client;
	
	public function setUrl($url)
	{
		$this->_url = $url;
		$this->client = new Client($url);
	}

	public function getUrl()
	{
		return $this->_url;
	}

	public function __construct($url, $config = '')
	{
		parent::__construct($config);
		$this->url = $url;
	}

	public function __call($name, $params)
    {
    	dump($this->client);dump($name);dump($params);exit;
        return call_user_func_array([$this->client, $name], $params);
    }

}