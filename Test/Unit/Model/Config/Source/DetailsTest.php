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
    /**
     * @var \Ebizmarts\MageMonkey\Model\Config\Source\Details
     */
    protected $_collection;
    protected $_collectionEmpty;
    /**
     * @var \|\PHPUnit_Framework_MockObject_MockObject|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_apiMock;

    protected function setUp()
    {
//        $apiMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\Api')
//            ->disableOriginalConstructor()
//            ->getMock();
        $helperMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $mcapiMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();

        $helperMock->expects($this->any())
            ->method('getApiKey')
            ->willReturn('apikey');

        $options = array('account_name'=>'ebizmarts','total_subscribers'=>5,'contact'=>(object)array('company'=>'ebizmarts'));
        $mcapiMock->expects($this->any())
            ->method('info')
            ->willReturn((object)$options);

//        $apiMock->expects($this->any())
//            ->method('loadByStore')
//            ->willReturn($mcapiMock);

        $storeManagerMock = $this->getMockBuilder('Magento\Store\Model\StoreManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock->expects($this->any())
            ->method('getId')
            ->willReturn(1);
        $storeManagerMock->expects($this->any())
            ->method('getStore')
            ->willReturn($storeMock);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $apiMock = $objectManager->getObject('Ebizmarts\Magemonkey\Model\Api',
            [
                'helper' => $helperMock,
                'mcapi'  => $mcapiMock,
                'storeManager' => $storeManagerMock
            ]
        );

        $mcapiEmptyMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();
        $optionsEmpty = (object)array('nolists'=>(object)array((object)array()));
        $mcapiEmptyMock->expects($this->any())
            ->method('info')
            ->willReturn($optionsEmpty);

        $apiEmptyMock = $objectManager->getObject('Ebizmarts\Magemonkey\Model\Api',
            [
                'helper' => $helperMock,
                'mcapi'  => $mcapiEmptyMock,
                'storeManager' => $storeManagerMock
            ]
        );
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_collection = $objectManager->getObject('Ebizmarts\MageMonkey\Model\Config\Source\Details',
            [
                'helper' => $helperMock,
                'api' => $apiMock
            ]
        );
        $this->_collectionEmpty = $objectManager->getObject('Ebizmarts\MageMonkey\Model\Config\Source\Details',
            [
                'api' => $apiEmptyMock,
                'helper' => $helperMock
            ]
        );

    }
    public function testToOptionArray()
    {
        $this->_collectionEmpty->toOptionArray();
        $this->assertNotEmpty($this->_collection->toOptionArray());

        foreach ($this->_collection->toOptionArray() as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
        }
    }

}