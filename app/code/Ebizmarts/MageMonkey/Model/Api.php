<?php
/**
 * Author: info@ebizmarts.com
 * Date: 2/27/15
 * Time: 3:04 AM
 * File: Api.php
 * Module: magento2
 */
namespace Ebizmarts\MageMonkey\Model;

class Api
{
    protected $_mcapi   = null;
    protected $apiHost  = null;

    /**
     * @param array $args
     * @param \Ebizmarts\MageMonkey\Helper\Data $helper
     */
    public function __construct(array $args,
                                \Ebizmarts\MageMonkey\Helper\Data $helper
    )
    {
        $apiKey         = (!isset($args['apiKey'])) ? $helper->getApiKey() : $args['apiKey'];
        $this->_mcapi = new \Ebizmarts\MageMonkey\Model\MCAPI($apiKey,$helper);

    }
    public function __call($method,$args=null)
    {
        return $this->call($method,$args);
    }
    public function call($command,$args)
    {
        if($args)
        {
            $result = call_user_func_array(array($this->_mcapi, $command), $args);
        }
        else
        {
            $result = $this->_mcapi->{$command}();
        }
        return $result;
    }
}