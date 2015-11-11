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