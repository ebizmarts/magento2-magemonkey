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

namespace Ebizmarts\MageMonkey\Test\Unit\Helper;

class DataTest extends \PHPUnit_Framework_TestCase
{
    protected $counter = 0;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_scopeMock;
    /**
     * @var \Ebizmarts\MageMonkey\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Ebizmarts\MageMonkey\Model\Logger\Magemonkey|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_logger;

    protected function setUp(){
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_scopeMock = $this->getMockBuilder('Magento\Framework\App\Config\ScopeConfigInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock = $this->getMockBuilder('Magento\Framework\App\Helper\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->_scopeMock);
        $storeManagerMock = $this->getMockBuilder('Magento\Store\Model\StoreManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_logger = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\Logger\Magemonkey')
            ->disableOriginalConstructor()
            ->getMock();
        $groupRegistryMock = $this->getMockBuilder('Magento\Customer\Model\GroupRegistry')
            ->disableOriginalConstructor()
            ->getMock();
        $scopeConfigMock = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');

        $this->_helper = $objectManager->getObject('Ebizmarts\MageMonkey\Helper\Data',
            [
                'context'=>$contextMock,
                'storeManager' => $storeManagerMock,
                'logger' => $this->_logger,
                'groupRegistry' => $groupRegistryMock,
                'scopeConfig' => $scopeConfigMock
            ]);
    }

    public function testIsMonkeyEnabled(){
        $this->_scopeMock->expects($this->once())
            ->method('getValue')
            ->willReturn(1);
        $this->assertEquals($this->_helper->isMonkeyEnabled(), 1);
    }

    public function testIsDoubleOptInEnabled(){
        $this->_scopeMock->expects($this->once())
            ->method('getValue')
            ->willReturn(1);
        $this->assertEquals($this->_helper->isDoubleOptInEnabled(), 1);
    }

    public function testGetApiKey(){
        $this->_scopeMock->expects($this->once())
            ->method('getValue')
            ->willReturn('702d18c6593a882492bb972ee77738fc-us8');
        $this->assertEquals($this->_helper->getApiKey(), '702d18c6593a882492bb972ee77738fc-us8');
    }

//    public function testGetDefaultList(){
//        $this->_scopeMock->expects($this->any())
//            ->method('getValue')
//            ->willReturn('Main List');
//
//        echo $this->_helper->getDefaultList();
//
//        $this->assertEquals($this->_helper->getDefaultList(), 'Main List');
//    }

//    public function testGetMergeVars(){
//        $customerMock = $this->getMockBuilder('Magento\Customer\Model\Customer')
//            ->disableOriginalConstructor()
//            ->getMock();
//        $this->_scopeMock->expects($this->atLeastOnce())
//            ->method('getValue')
//            ->willReturn('a:10:{s:18:"_1455130677743_743";a:2:{s:7:"magento";s:5:"fname";s:9:"mailchimp";s:5:"FNAME";}s:18:"_1455132553917_917";a:2:{s:7:"magento";s:5:"lname";s:9:"mailchimp";s:5:"LNAME";}s:18:"_1455132560288_288";a:2:{s:7:"magento";s:6:"gender";s:9:"mailchimp";s:6:"GENDER";}s:18:"_1455132567137_137";a:2:{s:7:"magento";s:3:"dob";s:9:"mailchimp";s:3:"DOB";}s:18:"_1455132573944_944";a:2:{s:7:"magento";s:15:"billing_address";s:9:"mailchimp";s:7:"BILLING";}s:18:"_1455132594111_111";a:2:{s:7:"magento";s:16:"shipping_address";s:9:"mailchimp";s:8:"SHIPPING";}s:17:"_1455132602049_49";a:2:{s:7:"magento";s:9:"telephone";s:9:"mailchimp";s:9:"TELEPHONE";}s:18:"_1455132614663_663";a:2:{s:7:"magento";s:7:"company";s:9:"mailchimp";s:7:"COMPANY";}s:18:"_1455132622855_855";a:2:{s:7:"magento";s:8:"group_id";s:9:"mailchimp";s:6:"CGROUP";}s:18:"_1455132622855_877";a:2:{s:7:"magento";s:10:"middlename";s:9:"mailchimp";s:5:"MNAME";}}');
//
//        $customerMock->expects($this->any())
//            ->method('getData')
//            ->will($this->returnCallback([$this,'_getData']));
//
//        $billingMock = $this->getMockBuilder('Magento\Customer\Model\Customer\Address')
//            ->disableOriginalConstructor()
//            ->setMethods(array('getTelephone','getStreetLine','getCompany','getCity','getRegion','getPostCode','getCountryId'))
//            ->getMock();
//        $billingMock->expects($this->any())->method('getTelephone')->willReturn('555 1234');
//        $billingMock->expects($this->any())->method('getCompany')->willReturn('ebizmarts');
//        $billingMock->expects($this->any())->method('getStreetLine')->willReturn('here');
//        $billingMock->expects($this->any())->method('getCity')->willReturn('los angeles');
//        $billingMock->expects($this->any())->method('getRegion')->willReturn('ca');
//        $billingMock->expects($this->any())->method('getPostCode')->willReturn('90210');
//        $billingMock->expects($this->any())->method('getCountryId')->willReturn('1');
//
//        $customerMock->expects($this->any())->method('getDefaultBillingAddress')->willReturn($billingMock);
//        $customerMock->expects($this->any())->method('getDefaultShippingAddress')->willReturn($billingMock);
//
//        $ret = $this->_helper->getMergeVars($customerMock);
//    }
    public function _getData($data)
    {
        switch($data)
        {
            case 'gender':
                if($this->counter==0)
                {
                    $rc = 2;
                }
                else {
                    $rc = 1;
                }
                $this->counter+=1;
                break;
            case 'dob':
                $rc = '1989-06-04';
                break;
            case 'firstname' :
                $rc = 'fname';
                break;
            case 'lastname':
                $rc = 'lname';
                break;
            default:
                $rc = $data;
                break;
        }
        return $rc;
    }
}