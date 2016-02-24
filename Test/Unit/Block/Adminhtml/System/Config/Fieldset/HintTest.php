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
    protected function setUp(){
        $contextMock = $this->getMockBuilder('Magento\Backend\Block\Template\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $productMetaDataMock = $this->getMockBuilder('Magento\Framework\App\ProductMetadataInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $loaderMock = $this->getMockBuilder('Magento\Framework\Module\ModuleList\Loader')
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_collection = $objectManager->getObject('Ebizmarts\MageMonkey\Block\Adminhtml\System\Config\Fieldset\Hint',
            [
                'context' => $contextMock,
                'productMetaData' => $productMetaDataMock,
                'loader' => $loaderMock
            ]
        );
    }

    public function test(){

    }
}