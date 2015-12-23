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
    public function __construct(
        \Ebizmarts\MageMonkey\Helper\Data $helper,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->_helper          = $helper;
        $this->_customer        = $customer;
        $this->_customerSession = $customerSession;
    }

    public function beforeUnsubscribeCustomerById(
        $subscriber, $result
    )
    {
        $this->_helper->log('unsubscribe');
        $this->_helper->log($subscriber->getData());
        if($subscriber->getMagemonkeyId())
        {
            $this->_helper->log('if');
            $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

            $api = $objectManager->create('Ebizmarts\MageMonkey\Model\Api', array(array(),$this->_helper));
            $api = New \Ebizmarts\MageMonkey\Model\Api(array(),$this->_helper);
            //$return = $api->listDeleteMember($this->_helper->getDefaultList(),$subscriber->getMagemonkeyId());
            $this->_helper->log($subscriber->getMagemonkeyId());
            //$subscriber->setMagemonkeyId('');
        }
        $this->_helper->log('out');
        return $result;
    }

    public function afterSubscribeCustomerById(
        $subscriber, $result
    )
    {
        $storeId = $subscriber->getStoreId();
        if($this->_helper->isMonkeyEnabled($storeId)) {
            $customer = $this->_customer;
            $mergeVars = $this->_helper->getMergeVars($customer);
            $api = New \Ebizmarts\MageMonkey\Model\Api(array(), $this->_helper);
            $isSubscribeOwnEmail = $this->_customerSession->isLoggedIn()
                && $this->_customerSession->getCustomerDataObject()->getEmail() == $subscriber->getSubscriberEmail();
            if($this->_helper->isDoubleOptInEnabled($storeId) && !$isSubscribeOwnEmail) {
                $status = 'pending';
            }else{
                $status = 'subscribed';
            }
            $data = array('list_id' => $this->_helper->getDefaultList(), 'email_address' => $subscriber->getEmail(), 'email_type' => 'html', 'status' => $status, /*'merge_fields' => $mergeVars*/);
            $return = $api->listCreateMember($this->_helper->getDefaultList(), json_encode($data));
            if (isset($return->id)) {
                $subscriber->setMagemonkeyId($return->id);
            }
        }
        return $result;
    }
}