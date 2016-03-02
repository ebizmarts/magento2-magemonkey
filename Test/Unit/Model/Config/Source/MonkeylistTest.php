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
     * @var \Ebizmarts\MageMonkey\Model\Config\Source\Monkeylist
     */
    protected $_collection;
    /**
     * @var \Ebizmarts\MageMonkey\Model\Api|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_apiMock;
    /**
     * @var \Ebizmarts\MageMonkey\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_helper;
    /**
     * @var
     */
    protected $_options;

    protected function setUp()
    {
        $this->_apiMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\Api')
            ->disableOriginalConstructor()
            ->setMethods(['lists'])
            ->getMock();
        $this->_helper = $this->getMockBuilder('Ebizmarts\MageMonkey\Helper\Data')
            ->disableOriginalConstructor()
            ->setMethods(['getApiKey'])
            ->getMock();
        $this->_helper->expects($this->once())->method('getApiKey')->willReturn('8c6593a882492bb972ee77738fc-us8702d1');
        $this->_apiMock->expects($this->atLeastOnce())->method('lists')->willReturn(array('lists'=>array('id'=>1, 'name'=>'List Name')));
        $this->_options = new \stdClass();

        $this->_options->lists = array(array('id'=>1, 'name'=>'List Name'));
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_collection = $objectManager->getObject('Ebizmarts\MageMonkey\Model\Config\Source\Monkeylist',
            [
                'api' => $this->_apiMock,
                'helper' => $this->_helper
            ]
        );
    }

    public function testToOptionArray()
    {
        $this->assertNotEmpty($this->_options->lists);
        $this->assertNotEmpty($this->_collection->toOptionArray());
        foreach ($this->_collection->toOptionArray() as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
        }
    }

    public function testToArray()
    {
        $this->assertNotEmpty($this->_options->lists);
    }

}