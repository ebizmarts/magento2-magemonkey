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
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @param \Ebizmarts\MageMonkey\Helper\Data $helper
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Magento\Customer\Model\Session $customerSession
     */
    protected $_api = null;

    public function __construct(
        \Ebizmarts\MageMonkey\Helper\Data $helper,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\Session $customerSession,
        \Ebizmarts\MageMonkey\Model\Api $api

    )
    {
        $this->_helper          = $helper;
        $this->_customer        = $customer;
        $this->_customerSession = $customerSession;
        $this->_api             = $api;
    }

    public function beforeUnsubscribeCustomerById(
        $subscriber, $customerId
    )
    {
        $subscriber->loadByCustomerId($customerId);
        if($subscriber->getMagemonkeyId())
        {
            $api = $this->_api->loadByStore($subscriber->getStoreId());
            $api->listDeleteMember($this->_helper->getDefaultList(),$subscriber->getMagemonkeyId());
            $subscriber->setMagemonkeyId('');
        }
    }

    public function beforeSubscribeCustomerById(
        $subscriber, $customerId
    )
    {
        $subscriber->loadByCustomerId($customerId);
        $storeId = $subscriber->getStoreId();
        if($this->_helper->isMonkeyEnabled($storeId)) {
            $this->_helper->log($subscriber->getEmail());
            $customer = $this->_customer->load($customerId);
            $this->_helper->log($customer->getId());
            $this->_helper->log($customer->getFirstname());
            $mergeVars = $this->_helper->getMergeVars($customer);
            $api = $this->_api->loadByStore($subscriber->getStoreId());
            $isSubscribeOwnEmail = $this->_customerSession->isLoggedIn()
                && $this->_customerSession->getCustomerDataObject()->getEmail() == $subscriber->getSubscriberEmail();
            if($this->_helper->isDoubleOptInEnabled($storeId) && !$isSubscribeOwnEmail) {
                $status = 'pending';
            }else{
                $status = 'subscribed';
            }
            $this->_helper->log('$this->_helper->getDefaultList()');
            $this->_helper->log($this->_helper->getDefaultList());
            if($mergeVars){
                $data = array('list_id' => $this->_helper->getDefaultList(), 'email_address' => $customer->getEmail(), 'email_type' => 'html', 'status' => $status, 'merge_fields' => $mergeVars);
            }else {
                $data = array('list_id' => $this->_helper->getDefaultList(), 'email_address' => $customer->getEmail(), 'email_type' => 'html', 'status' => $status);
            }
            $this->_helper->log(print_r(json_encode($data), 1));
            $return = $api->listCreateMember($this->_helper->getDefaultList(), json_encode($data));
            if (isset($return->id)) {
                $subscriber->setMagemonkeyId($return->id);
            }
        }
    }
}