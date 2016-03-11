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

namespace Ebizmarts\MageMonkey\Test\Unit\Model\Plugin;

class SubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Ebizmarts\MageMonkey\Model\Plugin\Subscriber
     */
    protected $plugin;
    protected $helperMock;
    protected $subscriberMock;


    public function setUp(){


        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->subscriberMock = $this->getMockBuilder('Magento\Newsletter\Model\Subscriber')
            ->disableOriginalConstructor()
            ->setMethods(['getMageMonkeyId','loadByCustomerId'])
            ->getMock();



        $this->helperMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $this->helperMock->expects($this->any())->method('isMonkeyEnabled')->willReturn(true);

        $customerMock = $this->getMockBuilder('Magento\Customer\Model\Customer')
            ->disableOriginalConstructor()
            ->setMethods(['load'])
            ->getMock();
        $customerMock->expects($this->any())->method('load')->willReturn($customerMock);
        $customerSessionMock = $this->getMockBuilder('Magento\Customer\Model\Session')
            ->disableOriginalConstructor()
            ->setMethods(['isLoggedIn', 'getCustomerDataObject'])
            ->getMock();
        $apiMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\Api')
            ->disableOriginalConstructor()
            ->getMock();
        $mcapiMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();
        $options = (object)array('id'=>1);
        $mcapiMock->expects($this->any())
            ->method('listCreateMember')
            ->willReturn($options);

        $apiMock->expects($this->any())
            ->method('loadByStore')
            ->willReturn($mcapiMock);
        $apiMock->expects($this->any())
            ->method('listDeleteMember')
            ->willReturn(true);

        $this->plugin = $objectManager->getObject('Ebizmarts\MageMonkey\Model\Plugin\Subscriber',
                [
                    'helper' => $this->helperMock,
                    'customer' => $customerMock,
                    'customerSession' => $customerSessionMock,
                    'api' => $apiMock
                ]);
    }

    /**
     * @covers Ebizmarts\MageMonkey\Model\Plugin\Subscriber::beforeUnsubscribeCustomerById
     */
    public function testBeforeUnsubscribeCustomerById()
    {
        $this->subscriberMock->expects($this->once())
            ->method('loadByCustomerId')
            ->willReturn($this->subscriberMock);
        $this->subscriberMock->expects($this->exactly(2))
            ->method('getMagemonkeyId')
            ->willReturn(1);
        $this->plugin->beforeUnsubscribeCustomerById($this->subscriberMock, 1);
    }
    /**
     * @covers Ebizmarts\MageMonkey\Model\Plugin\Subscriber::beforeSubscribeCustomerById
     */
    public function testBeforeSubscribeCustomerById()
    {
        $this->plugin->beforeSubscribeCustomerById($this->subscriberMock, 1);
        $this->helperMock->expects($this->exactly(2))->method('isDoubleOptInEnabled')->willReturn(true);
        $this->plugin->beforeSubscribeCustomerById($this->subscriberMock, 1);
        $this->helperMock->expects($this->once())->method('getMergeVars')->willReturn(array('FNAME'=>'fname'));
        $this->plugin->beforeSubscribeCustomerById($this->subscriberMock, 1);
    }
}