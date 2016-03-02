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

class Api
{
    protected $_mcapi   = null;
    protected $apiHost  = null;
    protected $_helper = null;

    /**
     * @param array $args
     * @param \Ebizmarts\MageMonkey\Helper\Data $helper
     */
    public function __construct(
        \Ebizmarts\MageMonkey\Helper\Data $helper,
        \Ebizmarts\MageMonkey\Model\MCAPI $mcapi
    )
    {
        $this->_helper = $helper;
        $this->_mcapi = $mcapi;

    }
    public function __call($method,$args=null)
    {
        return $this->call($method,$args);
    }
    public function call($command,$args)
    {
        if($args)
        {
            if(is_callable(array($this->_mcapi, $command))) {
                $result = call_user_func_array(array($this->_mcapi, $command), $args);
            }
        }
        else
        {
            $result = $this->_mcapi->{$command}();
        }
        return $result;
    }

    public function loadByStore($store = null){
        $apiKey = $this->_helper->getApiKey($store);
        return $this->_mcapi->load($apiKey);
    }
}