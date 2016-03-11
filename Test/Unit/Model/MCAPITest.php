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

    protected function setUp()
    {
        $curlMock = $this->getMockBuilder('Magento\Framework\HTTP\Adapter\Curl')
            ->disableOriginalConstructor()
            ->getMock();
        $helperMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_mcapi = $objectManager->getObject('Ebizmarts\Magemonkey\Model\MCAPI',
            [
                'helper' => $helperMock,
                'curl'  => $curlMock
            ]
        );
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

}
