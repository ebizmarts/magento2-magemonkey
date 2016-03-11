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
    /**
     * @var MCAPI|null
     */
    protected $_mcapi   = null;
    /**
     * @var \Ebizmarts\MageMonkey\Helper\Data|null
     */
    protected $_helper = null;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface|null
     */
    protected $_storeManager = null;

    /**
     * Api constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Ebizmarts\MageMonkey\Helper\Data $helper
     * @param MCAPI $mcapi
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Ebizmarts\MageMonkey\Helper\Data $helper,
        \Ebizmarts\MageMonkey\Model\MCAPI $mcapi
    )
    {
        $this->_helper = $helper;
        $this->_mcapi = $mcapi;
        $this->_storeManager = $storeManager;

    }
    public function __call($method,$args=null)
    {
        return $this->call($method,$args);
    }
    public function call($command,$args)
    {
        $result = null;
        if($args)
        {
            if(is_callable(array($this->_mcapi, $command))) {
                $reflectionMethod = new \ReflectionMethod($this->_mcapi,$command);
                $result = $reflectionMethod->invokeArgs($this->_mcapi,$args);
            }
        }
        else
        {
            if(is_callable(array($this->_mcapi, $command))) {
                $result = $this->_mcapi->{$command}();
            }
        }
        return $result;
    }
}