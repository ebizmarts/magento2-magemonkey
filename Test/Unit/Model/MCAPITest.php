<?php
/**
 * MageMonkey Magento Component
 *
 * @category Ebizmarts
 * @package MageMonkey
 * @author Ebizmarts Team <info@ebizmarts.com>
 * @copyright Ebizmarts (http://ebizmarts.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @date: 3/11/16 3:30 PM
 * @file: MCADITest.php
 */
namespace Ebizmarts\MageMonkey\Test\Unit\Model;

class MCAPITest extends \PHPUnit_Framework_TestCase
{
    protected $_mcapi;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $curlMock = $this->getMockBuilder('Magento\Framework\HTTP\Adapter\Curl')
            ->disableOriginalConstructor()
            ->getMock();
        $curlMock->expects($this->any())
            ->method('addOption')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('connect')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('read')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('getInfo')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('close')
            ->willReturn(true);

        $helperMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $helperMock->expects($this->any())
            ->method('getApiKey')
            ->willReturn('api-key');


        $this->_mcapi = $objectManager->getObject('Ebizmarts\Magemonkey\Model\MCAPI',
            [
                'helper' => $helperMock,
                'curl'  => $curlMock
            ]);
    }

    /**
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::load
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::getApiKey
     */
    public function testLoad()
    {
        $mcapi = $this->_mcapi->load('apikey');
        $this->assertEquals($mcapi->getApiKey(),'apikey');
    }
    /**
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::setTimeout
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::getTimeout
     */
    public function testTimeout()
    {
        $this->_mcapi->setTimeout(10);
        $this->assertEquals($this->_mcapi->getTimeout(),10);
    }
    /**
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::info
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::callServer
     */
    public function testInfo()
    {
        $this->_mcapi->info();
    }
    /**
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::lists
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::callServer
     */
    public function testLists()
    {
        $this->_mcapi->lists();
    }
    /**
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::listMembers
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::callServer
     */
    public function testListMembers()
    {
        $this->_mcapi->listMembers(1);
    }
    /**
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::listCreateMember
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::callServer
     */
    public function testListCreateMember()
    {
        $this->_mcapi->listCreateMember(1,['name'=>'name']);
    }
    /**
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::listDeleteMember
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::callServer
     * @covers Ebizmarts\MageMonkey\Model\MCAPI::getHost
     */
    public function testListDeleteMember()
    {
        $this->_mcapi->listDeleteMember(1,1);
    }
}
