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

namespace Ebizmarts\MageMonkey\Test\Unit\Block\Adminhtml\System\Config\Fieldset;

class HintTest extends \PHPUnit_Framework_TestCase
{
    protected $_hint;
    protected $_counter = 0;
    protected function setUp(){
        $contextMock = $this->getMockBuilder('Magento\Backend\Block\Template\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $productMetaDataMock = $this->getMockBuilder('Magento\Framework\App\ProductMetadataInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $productMetaDataMock->expects($this->any())->method('getVersion')->willReturn('1');
        $productMetaDataMock->expects($this->any())->method('getEdition')->will($this->onConsecutiveCalls('Community','Enterprise'));
        $loaderMock = $this->getMockBuilder('Magento\Framework\Module\ModuleList\Loader')
            ->disableOriginalConstructor()
            ->getMock();
        $loaderMock->expects($this->any())->method('load')->willReturn(['Ebizmarts_MageMonkey'=> ['setup_version'=>3]]);
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_hint = $objectManager->getObject('Ebizmarts\MageMonkey\Block\Adminhtml\System\Config\Fieldset\Hint',
            [
                'context' => $contextMock,
                'productMetaData' => $productMetaDataMock,
                'loader' => $loaderMock
            ]
        );
    }

    public function testGetPxParams()
    {
        $this->assertEquals($this->_hint->getPxParams(),'ext=MageMonkey;3&mage=Magento CE;1&ctrl=818b95f17fc2d5e4bc1560681f1eb287');
        $this->assertEquals($this->_hint->getPxParams(),'ext=MageMonkey;3&mage=Magento EE;1&ctrl=21280f46ed6d9789a031f06e0ce5767c');
    }
    public function testGetVersion(){
        $this->assertEquals($this->_hint->getVersion(),3);
    }
}