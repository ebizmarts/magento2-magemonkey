<?php
/**
 * Author: info@ebizmarts.com
 * Date: 2/27/15
 * Time: 5:27 PM
 * File: Details.php
 * Module: magento2
 */

namespace Ebizmarts\MageMonkey\Model\Config\Source;

class Details  implements \Magento\Framework\Option\ArrayInterface
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
            $this->_options = $this->_api->info();
        }
    }
    public function toOptionArray()
    {
        if(isset($this->_options->account_name)) {
            return [
                ['value'=>'Account Name',      'label'=> $this->_options->account_name],
                ['value'=>'Company',           'label'=> $this->_options->contact->company],
                ['value'=>'Total Subscribers', 'label'=> $this->_options->total_subscribers],
            ];
        }else{
            return [
                ['value'=>'Error','label' => __('Invalid API Key')]
            ];
        }
    }
    public function toArray()
    {
        return array(
            'Account Name' => $this->_options->account_name
        );

    }
}
