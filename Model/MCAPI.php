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
    protected $_apiUrl      = null;
    protected $_timeout     = 300;
    protected $_chunkSize   = 8192;
    protected $_apiKey      = null;
    protected $_secure      = false;
    protected $_helper      = null;

    /**
     * @param $apiKey
     * @param \Ebizmarts\MageMonkey\Helper\Data $helper
     * @param bool $secure
     */
    public function __construct($apiKey,
                                \Ebizmarts\MageMonkey\Helper\Data $helper,
                                $secure = false
    )
    {
        $this->_secure  = $secure;
        $this->_apiUrl  = parse_url("http://api.mailchimp.com/" . $this->_version);
        $this->_apiKey  = $apiKey;
        $this->_helper  = $helper;
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
    public function useSecure($val)
    {
        if($val === true) {
            $this->_secure  = true;
        }
        else
        {
            $this->_secure  = false;
        }
    }
    public function callServer($use = 'GET',$method = null, $params = null,$fields = null)
    {
        $dc = '';
        $key = '';
        if(strstr($this->_apiKey,'-'))
        {
            list($key,$dc)  = explode('-',$this->_apiKey);
            if(!$dc)
            {
                $dc = 'us1';
            }
        }
        $host   = $dc.'.'.$this->_apiUrl['host'].'/'.$this->_version;
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
        $ch     = curl_init();
        $this->_helper->log($host);
        curl_setopt($ch, CURLOPT_POST, false);
        if($fields)
        {
            curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
            $this->_helper->log($fields);
        }
        switch($use)
        {
            case 'POST':
                curl_setopt($ch,CURLOPT_POST,true);
                break;
            case 'GET':
                break;
            case 'DELETE':
                curl_setopt($ch,CURLOPT_POST,false);
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'DELETE');
                break;
            case 'PATCH':
                curl_setopt($ch,CURLOPT_POST,true);
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'PATCH');
                break;
            case 'PUT':
                curl_setopt($ch,CURLOPT_POST,true);
                curl_setopt($ch,CURLOPT_PUT, true);
                break;

        }

        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_USERAGENT, 'MageMonkey/'); // @todo put the version of MageMonkey
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: apikey '.$key,'Cache-Control: no-cache'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        $response       = curl_exec($ch);
//        $this->_logger->info($response);
        $header_size    = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header         = substr($response, 0, $header_size);
        $body           = substr($response, $header_size);
        $responseCode   = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        $data           = json_decode($body);
        $this->_helper->log(print_r(json_encode($data),1));
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