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
