<?php
/**
 * Author: info@ebizmarts.com
 * Date: 2/26/15
 * Time: 11:43 PM
 * File: List.php
 * Module: magento2
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
    public function __construct(\Ebizmarts\MageMonkey\Helper\Data $helper)
    {

        $this->_helper  = $helper;
        $this->_api     = New \Ebizmarts\MageMonkey\Model\Api(array(),$helper);
        if($helper->getApiKey()) {
            $this->_options = $this->_api->lists();
        }
    }
    public function toOptionArray()
    {
        if($this->_options) {
            $rc = array();
            foreach($this->_options->lists as $list)
            {
                $rc[] = array('value'=>$list->id,'label'=>$list->name);
            }
            return $rc;
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