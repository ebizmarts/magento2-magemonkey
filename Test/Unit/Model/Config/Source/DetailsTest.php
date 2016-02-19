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

namespace Ebizmarts\MageMonkey\Test\Unit\Model\Config\Soruce;

class DetailsTest extends \PHPUnit_Framework_TestCase
{
    protected $_collection;
    protected $_apiMock;
    protected $_helper;

    protected function setUp()
    {
        $this->apiMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\Api')
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper = $this->getMockBuilder('Ebizmarts\MageMonkey\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_collection = $objectManager->getObject('Ebizmarts\MageMonkey\Model\Config\Source\Details',
            [
                'helper' => $this->helper,
                'api' => $this->apiMock
            ]
        );
    }
    public function testToOptionArray()
    {
        $this->assertNotEmpty($this->_collection->toOptionArray());

        foreach ($this->_collection->toOptionArray() as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
        }
    }

}