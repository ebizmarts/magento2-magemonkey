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


    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $subscriberFactory;

    /**
     * @var \Magento\Newsletter\Model\Subscriber|\PHPUnit_Framework_MockObject_MockObject
     */
    private $subscriber;


    private $groupRepositoryInterface;
    private $scopeConfig;
    private $customerRepository;
    private $customerAccountManagement;
    private $inlineTranslation;
    private $objectManager;
    protected $helperMock;


    public function setUp(){
        $newsletterDataMock = $this->getMock('Magento\Newsletter\Helper\Data', [], [], '', false);
        $scopeConfigMock = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $transportBuilderMock = $this->getMock(
            'Magento\Framework\Mail\Template\TransportBuilder',
            [
                'setTemplateIdentifier',
                'setTemplateOptions',
                'setTemplateVars',
                'setFrom',
                'addTo',
                'getTransport'
            ],
            [],
            '',
            false
        );
        $storeManagerMock = $this->getMock('Magento\Store\Model\StoreManagerInterface');
        $customerSessionMock = $this->getMock(
            'Magento\Customer\Model\Session',
            [
                'isLoggedIn',
                'getCustomerDataObject',
                'getCustomerId'
            ],
            [],
            '',
            false
        );
        $this->customerRepository = $this->getMock('Magento\Customer\Api\CustomerRepositoryInterface');
        $this->customerAccountManagement = $this->getMock('Magento\Customer\Api\AccountManagementInterface');
        $this->inlineTranslation = $this->getMock('Magento\Framework\Translate\Inline\StateInterface');
        $resourceMock = $this->getMock(
            'Magento\Newsletter\Model\ResourceModel\Subscriber',
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
        $customerDataMock = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMock();
        $this->customerRepository->expects($this->atLeastOnce())
            ->method('getById')
            ->willReturn($customerDataMock);
        $resourceMock->expects($this->atLeastOnce())
            ->method('loadByCustomerData')
            ->with($customerDataMock)
            ->willReturn(
                [
                    'subscriber_id' => 1,
                    'subscriber_status' => 1
                ]
            );

        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->subscriber = $this->objectManager->getObject(
            'Magento\Newsletter\Model\Subscriber',
            [
                'newsletterData' => $newsletterDataMock,
                'scopeConfig' => $scopeConfigMock,
                'transportBuilder' => $transportBuilderMock,
                'storeManager' => $storeManagerMock,
                'customerSession' => $customerSessionMock,
                'customerRepository' => $this->customerRepository,
                'customerAccountManagement' => $this->customerAccountManagement,
                'inlineTranslation' => $this->inlineTranslation,
                'resource' => $resourceMock
            ]
        );


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

        $this->plugin = new \Ebizmarts\MageMonkey\Model\Plugin\Subscriber(
            $this->helperMock,
            $customerMock,
            $customerSessionMock,
            $apiMock
        );
    }
    public function testBeforeUnsubscribeCustomerById()
    {

        $customerId = 1;
        $customerDataMock = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMock();
        $this->customerRepository->expects($this->atLeastOnce())
            ->method('getById')
            ->with($customerId)->willReturn($customerDataMock);

        $this->plugin->beforeUnsubscribeCustomerById($this->subscriber, 1);
    }

    public function testBeforeSubscribeCustomerById()
    {
        $this->subscriber->setMagemonkeyId(1);
        $this->plugin->beforeSubscribeCustomerById($this->subscriber, 1);
        $this->helperMock->expects($this->any())->method('isDoubleOptInEnabled')->willReturn(true);
        $this->plugin->beforeSubscribeCustomerById($this->subscriber, 1);
        $this->helperMock->expects($this->once())->method('getMergeVars')->willReturn(array('FNAME'=>'fname'));
        $this->plugin->beforeSubscribeCustomerById($this->subscriber, 1);
    }
}