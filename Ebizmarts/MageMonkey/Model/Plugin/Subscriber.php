<?php
/**
 * Author: info@ebizmarts.com
 * Date: 3/2/15
 * Time: 5:59 PM
 * File: Subscriber.php
 * Module: magento2
 */

namespace Ebizmarts\MageMonkey\Model\Plugin;


class Subscriber
{
    /**
     * @var \Ebizmarts\MageMonkey\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customer;

    /**
     * @param \Ebizmarts\MageMonkey\Helper\Data $helper
     * @param \Magento\Customer\Model\Customer $customer
     */
    public function __construct(
        \Ebizmarts\MageMonkey\Helper\Data $helper,
        \Magento\Customer\Model\Customer $customer
    )
    {
        $this->_helper    = $helper;
        $this->_customer  = $customer;
    }

    public function afterUnsubscribeCustomerById(
        $subscriber
    )
    {
        if($subscriber->getMagemonkeyId())
        {
            $api = New \Ebizmarts\MageMonkey\Model\Api(array(),$this->_helper);
            $return =$api->listDeleteMember($this->_helper->getDefaultList(),$subscriber->getMagemonkeyId());
            $subscriber->setMagemonkeyId('')->save();
        }
    }

    public function afterSubscribeCustomerById(
        $subscriber
    )
    {
        $storeId = $subscriber->getStoreId();
        if($this->_helper->isMonkeyEnabled($storeId)) {
            $customer = $this->_customer->load($subscriber->getCustomerId());
            $mergeVars = $this->_helper->getMergeVars($customer);
            $this->_helper->log(print_r($mergeVars, 1));
            $api = New \Ebizmarts\MageMonkey\Model\Api(array(), $this->_helper);
            $data = array('list_id' => $this->_helper->getDefaultList(), 'email_address' => $subscriber->getEmail(), 'email_type' => 'html', 'status' => 'pendig', 'merge_fields' => $mergeVars);
            $return = $api->listCreateMember($this->_helper->getDefaultList(), json_encode($data));
            if (isset($return->id)) {
                $subscriber->setMagemonkeyId($return->id)->save();
            }
        }
    }
}