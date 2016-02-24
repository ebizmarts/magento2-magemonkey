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

namespace Ebizmarts\MageMonkey\Model\Config\Source;

class Monkeylist implements \Magento\Framework\Option\ArrayInterface
{
    protected $_api     = null;
    protected $_options = null;
    protected $_helper  = null;
    /**
     * @param \Ebizmarts\MageMonkey\Helper\Data $helper
     */
    public function __construct(
        \Ebizmarts\MageMonkey\Helper\Data $helper,
        \Ebizmarts\MageMonkey\Model\Api $api
    )
    {

        $this->_helper = $helper;
        $this->_api = $api;
        if($this->_helper->getApiKey()) {
            $this->_options = $this->_api->lists();
        }
    }
    public function toOptionArray()
    {
        if(isset($this->_options->lists)) {
            $rc = array();
            foreach($this->_options->lists as $list)
            {
                if(isset($list->id) && isset($list->name)) {
                    $rc[] = array('value' => $list->id, 'label' => $list->name);
                }
            }
            return $rc;
        }else{
            return array(array('value' => 0, 'label' => __('---No Data---')));
        }
    }

    public function toArray()
    {
        $rc = array();
        foreach($this->_options->lists as $list)
        {
            $rc[$list->id] = $list->name;
        }
        return $rc;
    }
}