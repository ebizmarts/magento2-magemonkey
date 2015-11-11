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

namespace Ebizmarts\MageMonkey\Test\Unit\Model\Plugin\Subscriber;

class SubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Ebizmarts\MageMonkey\Model\Plugin\Subscriber
     */
    protected $plugin;

    /**
     * @var \Magento\Newsletter\Model\Resource\Subscriber|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resource;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $subscriberFactory;

    /**
     * @var \Magento\Newsletter\Model\Subscriber|\PHPUnit_Framework_MockObject_MockObject
     */
    private $subscriber;

    public function setUp(){

        $this->subscriber = $this->getMockBuilder('\Magento\Newsletter\Model\Subscriber')
            ->setMethods(['loadByEmail', 'getId', 'delete', 'updateSubscription'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->subscriberFactory->expects($this->any())->method('create')->willReturn($this->subscriber);

        $this->plugin = $this->objectManager->getObject(
            'Ebizmarts\MageMonkey\Model\Plugin\Subscriber',
            [
                'subscriberFactory' => $this->subscriberFactory
            ]
        );

        $this->resource = $this->getMock(
            'Magento\Newsletter\Model\Resource\Subscriber',
            [
                'loadByEmail',
                'getIdFieldName',
                'save',
                'loadByCustomerData',
                'received'
            ],
            [],
            '',
            false
        );
    }
    public function testAfterUnsubscribeCustomerById()
    {
        $subscriber= $this->getMock('\Magento\Newsletter\Model\Subscriber|\PHPUnit_Framework_MockObject_MockObject');
        $subscriber->expects($this->once())->method('getMageMonkeyId')->willReturn('magemonkey_id');
        $subscriber->expects($this->atLeastOnce())->method('save')->willReturnSelf();

        $this->plugin->afterUnsubscribeCustomerById($subscriber);
    }

    public function testAfterSubscribeCustomerById()
    {
        $subscriber = $this->getMockBuilder('\Magento\Newsletter\Model\Subscriber')->getMock();
        $subscriber->expects($this->once())->method('getStoreId')->willReturn('store_id');
        $subscriber->expects($this->once())->method('getCustomerId')->willReturn('customer_id');
        $subscriber->expects($this->once())->method('getSubscriberEmail')->willReturn('subscriber_email');
        $subscriber->expects($this->once())->method('getEmail')->willReturn('email');

        $this->plugin->afterSubscribeCustomerById($subscriber);
    }
}