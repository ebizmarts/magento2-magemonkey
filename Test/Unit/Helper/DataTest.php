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
        $groupRepositoryInterfaceMock = $this->getMockBuilder('Magento\Customer\Api\GroupRepositoryInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_helper = $objectManager->getObject('Ebizmarts\MageMonkey\Helper\Data',
            [
                'context'=>$contextMock,
                'storeManager' => $storeManagerMock,
                'logger' => $this->_logger,
                'groupRepositoryInterface' => $groupRepositoryInterfaceMock
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

    public function testGetDefaultList(){
        $this->_scopeMock->expects($this->once())
            ->method('getValue')
            ->willReturn('Main List');
        $this->assertEquals($this->_helper->getDefaultList(), 'Main List');
    }
}