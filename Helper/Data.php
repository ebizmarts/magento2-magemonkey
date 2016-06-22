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

namespace Ebizmarts\MageMonkey\Helper;

use Magento\Store\Model\Store;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ACTIVE           = 'magemonkey/general/active';
    const XML_PATH_APIKEY           = 'magemonkey/general/apikey';
    const XML_PATH_MAXLISTAMOUNT    = 'magemonkey/general/maxlistamount';
    const XML_PATH_LIST             = 'magemonkey/general/list';
    const XML_PATH_LOG              = 'magemonkey/general/log';
    const XML_PATH_MAPPING          = 'magemonkey/general/mapping';
    const XML_PATH_CONFIRMATION_FLAG = 'newsletter/subscription/confirm';


    protected $_storeManager;
    protected $_mlogger;
    protected $_groupRegistry;
    protected $_scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Ebizmarts\MageMonkey\Model\Logger\Logger $logger
     * @param \Magento\Customer\Model\GroupRegistry $groupRegistry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Ebizmarts\MageMonkey\Model\Logger\Logger $logger,
        \Magento\Customer\Model\GroupRegistry $groupRegistry
    )
    {
        $this->_storeManager                = $storeManager;
        $this->_mlogger                     = $logger;
        $this->_groupRegistry               = $groupRegistry;
        $this->_scopeConfig                 = $context->getScopeConfig();
        parent::__construct($context);
    }

    public function isMonkeyEnabled($store = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
    }
    public function isDoubleOptInEnabled($store = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CONFIRMATION_FLAG, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
    }
    public function getApiKey($store = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_APIKEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
    }
    public function getDefaultList($store = null)
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_LIST, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
    }
    public function getLogger()
    {
        return $this->_logger;
    }
    public function log($message,$store=null)
    {
        if($this->_scopeConfig->getValue(self::XML_PATH_LOG, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store))
        {
            $this->_mlogger->MonkeyLog($message);
        }
    }
    public function getMergeVars($customer,$store = null)
    {
        $merge_vars = array();
        $mergeVars  = unserialize($this->_scopeConfig->getValue(self::XML_PATH_MAPPING, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store));
        foreach($mergeVars as $map)
        {
            $merge_vars = array_merge($merge_vars,$this->_getMergeVarsValue($map, $customer));
        }
        return $merge_vars;
    }

    protected function _getMergeVarsValue($map, $customer)
    {
        $merge_vars = array();
        $customAtt = $map['magento'];
        $chimpTag  = $map['mailchimp'];
        if($chimpTag && $customAtt) {
            $key = strtoupper($chimpTag);
            switch ($customAtt) {
                case 'fname':
                    $val = $customer->getFirstname();
                    $merge_vars[$key] = $val;
                    break;
                case 'lname':
                    $val = $customer->getLastname();
                    $merge_vars[$key] = $val;
                    break;
                case 'gender':
                    $val = (int)$customer->getData(strtolower($customAtt));
                    if ($val == 1) {
                        $merge_vars[$key] = 'Male';
                    } elseif ($val == 2) {
                        $merge_vars[$key] = 'Female';
                    }
                    break;
                case 'dob':
                    $dob = $customer->getData(strtolower($customAtt));
                    if ($dob) {
                        $merge_vars[$key] = (substr($dob, 5, 2) . '/' . substr($dob, 8, 2));
                    }
                    break;
                case 'billing_address':
                case 'shipping_address':
                    $addr = explode('_', $customAtt);
                    $merge_vars = array_merge($merge_vars,$this->_updateMergeVars($key,ucfirst($addr[0]),$customer));
                    break;
                case 'telephone':
                    if ($address = $customer->{'getDefaultBillingAddress'}()) {
                        $telephone = $address->getTelephone();
                        if ($telephone) {
                            $merge_vars[$key] = $telephone;
                        }
                    }
                    break;
                case 'company':
                    if ($address = $customer->{'getDefaultBillingAddress'}()) {
                        $company = $address->getCompany();
                        if ($company) {
                            $merge_vars[$key] = $company;
                        }
                    }
                    break;
                case 'group_id':
                    $merge_vars = array_merge($merge_vars,$this->_getCustomerGroup($customer,$key));
                    break;
                default:
                    if (($value = (string)$customer->getData(strtolower($customAtt)))) {
                        $merge_vars[$key] = (string)$customer->getData(strtolower($customAtt));
                    }
                    break;

            }
            return $merge_vars;
        }
    }
    protected function _getCustomerGroup($customer,$key)
    {
        $merge_vars = array();
        $group_id = (int) $customer->getGroupId();
        if($group_id == 0) {
            $merge_vars[$key] = 'NOT LOGGED IN';
        } else {
            try {
                $customerGroup = $this->_groupRegistry->retrieve($group_id);
                $merge_vars[$key] = $customerGroup->getCode();
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
        return $merge_vars;
    }
    protected function _updateMergeVars($key,$type,$customer)
    {
        $merge_vars = array();
        if ($address = $customer->{'getDefault' . $type . 'Address'}()) {
            $merge_vars[$key] = array(
                'addr1' => $address->getStreetLine(1),
                'addr2' => $address->getStreetLine(2),
                'city' => $address->getCity(),
                'state' => (!$address->getRegion() ? $address->getCity() : $address->getRegion()),
                'zip' => $address->getPostcode(),
                'country' => $address->getCountryId()
            );
        }
        return $merge_vars;
    }
}