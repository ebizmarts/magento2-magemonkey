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

namespace Ebizmarts\MageMonkey\Test\Unit\Model\Config\Source;

class MonkeylistTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    protected $_options;

    protected function setUp()
    {
        $apiMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\Api')
            ->disableOriginalConstructor()
            ->getMock();
        $helperMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $mcapiMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();
        $apiEmptyMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\Api')
            ->disableOriginalConstructor()
            ->getMock();
        $mcapiEmptyMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();

        $helperMock->expects($this->any())
            ->method('getApiKey')
            ->willReturn('apikey');

        $options = (object)array('lists'=>(object)array((object)array('id'=>1,'name'=>'list1'),(object)array('id'=>2,'name'=>'list2')));
        $optionsEmpty = (object)array('nolists'=>(object)array((object)array()));

        $mcapiMock->expects($this->any())
            ->method('lists')
            ->willReturn($options);

        $apiMock->expects($this->any())
            ->method('loadByStore')
            ->willReturn($mcapiMock);

        $mcapiEmptyMock->expects($this->any())
            ->method('lists')
            ->willReturn($optionsEmpty);

        $apiEmptyMock->expects($this->any())
            ->method('loadByStore')
            ->willReturn($mcapiEmptyMock);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_options = $objectManager->getObject('Ebizmarts\MageMonkey\Model\Config\Source\Monkeylist',
            [
                'api' => $apiMock,
                'helper' => $helperMock
            ]
        );
        $this->_optionsEmpty = $objectManager->getObject('Ebizmarts\MageMonkey\Model\Config\Source\Monkeylist',
            [
                'api' => $apiEmptyMock,
                'helper' => $helperMock
            ]
        );
    }

    public function testToOptionArray()
    {
        //$this->assertNotEmpty($this->_options->lists);
        $this->assertNotEmpty($this->_options->toOptionArray());
        foreach ($this->_options->toOptionArray() as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
        }
        $this->assertNotEmpty($this->_optionsEmpty->toOptionArray());
    }

    public function testToArray()
    {
        $this->assertNotEmpty($this->_options->toArray());
        foreach ($this->_options->toOptionArray() as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
        }
    }

}