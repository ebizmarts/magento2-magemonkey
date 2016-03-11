<?php
/**
 * Ebizmarts_MAgeMonkey Magento JS component
 *
 * @category    Ebizmarts
 * @package     Ebizmarts_MageMonkey
 * @author      Ebizmarts Team <info@ebizmarts.com>
 * @copyright   Ebizmarts (http://ebizmarts.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Ebizmarts\MageMonkey\Model;

class MCAPI
{
    protected $_version     = "3.0";
    protected $_timeout     = 300;
    protected $_chunkSize   = 8192;
    protected $_apiKey      = null;
    protected $_secure      = false;
    /**
     * @var \Ebizmarts\MageMonkey\Helper\Data|null
     */
    protected $_helper      = null;
    /**
     * @var \Magento\Framework\HTTP\Adapter\Curl|null
     */
    protected $_curl = null;

    /**
     * MCAPI constructor.
     * @param \Ebizmarts\MageMonkey\Helper\Data $helper
     * @param \Magento\Framework\HTTP\Adapter\Curl $curl
     */
    public function __construct(
        \Ebizmarts\MageMonkey\Helper\Data $helper,
        \Magento\Framework\HTTP\Adapter\Curl $curl
    )
    {
        $this->_helper = $helper;
        $this->_curl = $curl;
        $this->_apiKey = $helper->getApiKey();
        $this->_secure = false;
    }
    public function getApiKey()
    {
        return $this->_apiKey;
    }
    public function load($apiKey, $secure = false){
        $this->_apiKey  = $apiKey;
        $this->_secure  = $secure;
        return $this;
    }
    public function setTimeout($seconds)
    {
        if(is_int($seconds))
        {
            $this->_timeout = $seconds;
        }
    }
    public function getTimeOut()
    {
        return $this->_timeout;
    }
    protected function useSecure($val)
    {
        if($val === true) {
            $this->_secure  = true;
        }
        else
        {
            $this->_secure  = false;
        }
        return $this;
    }
    protected function callServer($use = 'GET',$method = null, $params = null,$fields = null)
    {
        $dc = '';
        $key = '';
        list($host,$key) = $this->getHost($method, $params);
        $curl = $this->_curl;
        $curl->addOption(CURLOPT_POST, false);
        if($fields)
        {
            $curl->addOption(CURLOPT_POSTFIELDS, $fields);
        }
        switch($use)
        {
            case 'POST':
                $curl->addOption(CURLOPT_POST, true);
                break;
            case 'GET':
                break;
            case 'DELETE':
                $curl->addOption(CURLOPT_POST, false);
                $curl->addOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'PATCH':
                $curl->addOption(CURLOPT_POST, true);
                $curl->addOption(CURLOPT_CUSTOMREQUEST, 'PATCH');
                break;
            case 'PUT':
                $curl->addOption(CURLOPT_POST, true);
                $curl->addOption(CURLOPT_PUT, true);

                break;

        }

        $curl->addOption(CURLOPT_URL, $host);
        $curl->addOption(CURLOPT_USERAGENT, 'MageMonkey/');
        $curl->addOption(CURLOPT_HEADER, true);
        $curl->addOption(CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: apikey '.$key,'Cache-Control: no-cache'));
        $curl->addOption(CURLOPT_RETURNTRANSFER, 1);
        $curl->addOption(CURLOPT_CONNECTTIMEOUT, 30);
        $curl->addOption(CURLOPT_TIMEOUT, $this->_timeout);
        $curl->addOption(CURLOPT_FOLLOWLOCATION, 1);
        $curl->connect($host);
        $response = $curl->read();
        $body = preg_split('/^\r?$/m', $response);
        $responseCode = $curl->getInfo(CURLINFO_HTTP_CODE);
        $curl->close();
        $data           = json_decode($body[count($body)-1]);
        switch($use)
        {
            case 'DELETE':
                if($responseCode!=204)
                {
                    throw new \Exception('Type: '.$data->type.' Title: '.$data->title.' Status: '.$data->status.' Detail: '.$data->detail);
                }
                break;
            case 'PUT':
                if($responseCode!=200) {
                    throw new \Exception('Type: '.$data->type.' Title: '.$data->title.' Status: '.$data->status.' Detail: '.$data->detail);
                }
                break;
            case 'POST':
                if($responseCode!=200) {
                    throw new \Exception('Type: '.$data->type.' Title: '.$data->title.' Status: '.$data->status.' Detail: '.$data->detail);
                }
                break;
            case 'PATCH':
                if($responseCode!=200) {
                    throw new \Exception('Type: '.$data->type.' Title: '.$data->title.' Status: '.$data->status.' Detail: '.$data->detail);
                }
                break;
        }
        return $data;
    }
    protected function getHost($method, $params)
    {
        $dc = '';
        if(strstr($this->_apiKey,'-'))
        {
            list($key,$dc)  = explode('-',$this->_apiKey);
            if(!$dc)
            {
                $dc = 'us1';
            }
        }
        $host   = $dc.'.'.\Ebizmarts\MageMonkey\Model\Config::ENDPOINT.'/'.$this->_version;
        if($method)
        {
            $host .= "/$method";
        }
        if(is_array($params))
        {
            foreach($params as $pkey => $value)
            {
                if(is_numeric($pkey))
                {
                    $host .= "/$value";
                }
                else {
                    $host .= "/$pkey/$value";
                }
            }
        }
        return [$host,$key];
    }
    public function info()
    {
        return $this->callServer();
    }
    public function lists()
    {
        return $this->callServer('GET','lists');
    }
    public function listMembers($listId)
    {
        return $this->callServer('GET','lists',array(0=>$listId,1=>'members'));
    }
    public function listCreateMember($listId,$memberData)
    {
        return $this->callServer('POST','lists',array(0=>$listId,1=>'members'),$memberData);
    }
    public function listDeleteMember($listId,$memberId)
    {
        return $this->callServer('DELETE','lists',array(0=>$listId,'members'=>$memberId));
    }
    /*public function listWebhookAdd(){

    }*/
}