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
     * @var \Magento\Newsletter\Model\Resource\Subscriber|\PHPUnit_Framework_MockObject_MockObject
     */
//    protected $resource;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $subscriberFactory;

    /**
     * @var \Magento\Newsletter\Model\Subscriber|\PHPUnit_Framework_MockObject_MockObject
     */
    private $subscriber;


    private $context;
    private $storeManager;
    private $logger;
    private $groupRepositoryInterface;
    private $scopeConfig;
    private $helper;
    private $customerMock;
    private $customerSessionMock;
    private $apiMock;
    private $customerRepository;
    private $newsletterData;
    private $transportBuilder;
    private $customerSession;
    private $customerAccountManagement;
    private $inlineTranslation;
    private $resource;
    private $objectManager;


    public function setUp(){
        $this->newsletterData = $this->getMock('Magento\Newsletter\Helper\Data', [], [], '', false);
        $this->scopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->transportBuilder = $this->getMock(
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
        $this->storeManager = $this->getMock('Magento\Store\Model\StoreManagerInterface');
        $this->customerSession = $this->getMock(
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
        $this->resource = $this->getMock(
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
        $this->resource->expects($this->atLeastOnce())
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
                'newsletterData' => $this->newsletterData,
                'scopeConfig' => $this->scopeConfig,
                'transportBuilder' => $this->transportBuilder,
                'storeManager' => $this->storeManager,
                'customerSession' => $this->customerSession,
                'customerRepository' => $this->customerRepository,
                'customerAccountManagement' => $this->customerAccountManagement,
                'inlineTranslation' => $this->inlineTranslation,
                'resource' => $this->resource
            ]
        );

        $this->context = $this->getMockBuilder('Magento\Framework\App\Helper\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManager = $this->getMockBuilder('Magento\Store\Model\StoreManagerInterface')->getMock();
        $this->logger = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\Logger\Magemonkey')
            ->disableOriginalConstructor()
            ->getMock();
        $this->groupRepositoryInterface = $this->getMockBuilder('Magento\Customer\Api\GroupRepositoryInterface')->getMock();
        $this->scopeConfig = $this->getMockBuilder('Magento\Framework\App\Config\ScopeConfigInterface')->getMock();
//        $this->helper = new \Ebizmarts\MageMonkey\Helper\Data(
//            $this->context,
//            $this->storeManager,
//            $this->logger,
//            $this->groupRepositoryInterface,
//            $this->scopeConfig
//            );
        $this->helper = $this->getMockBuilder('Ebizmarts\MageMonkey\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerMock = $this->getMockBuilder('Magento\Customer\Model\Customer')
            ->disableOriginalConstructor()
            ->setMethods(['load'])
            ->getMock();
        $this->customerSessionMock = $this->getMockBuilder('Magento\Customer\Model\Session')
            ->disableOriginalConstructor()
            ->setMethods(['isLoggedIn', 'getCustomerDataObject'])
            ->getMock();
        $this->apiMock = $this->getMockBuilder('Ebizmarts\MageMonkey\Model\Api')
            ->disableOriginalConstructor()
            ->setMethods(['loadByStore', 'listDeleteMember', 'listCreateMember'])
            ->getMock();
        $this->plugin = new \Ebizmarts\MageMonkey\Model\Plugin\Subscriber(
            $this->helper,
            $this->customerMock,
            $this->customerSessionMock,
            $this->apiMock
        );
        $this->newsletterData = $this->getMock('Magento\Newsletter\Helper\Data', [], [], '', false);
        $this->scopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->transportBuilder = $this->getMock(
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
        $this->storeManager = $this->getMock('Magento\Store\Model\StoreManagerInterface');
        $this->customerSession = $this->getMock(
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
        $this->customerAccountManagement = $this->getMock('Magento\Customer\Api\AccountManagementInterface');
        $this->inlineTranslation = $this->getMock('Magento\Framework\Translate\Inline\StateInterface');

        $this->resource = $this->getMock(
            'Magento\Newsletter\Model\Resource\Subscriber',
            [
                'loadByEmail',
                'getIdFieldName',
                'save',
                'loadByCustomerData',
                'received',
                'sendEmailCheck',
                'loadByStore'
            ],
            [],
            '',
            false
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

        $this->plugin->beforeSubscribeCustomerById($this->subscriber, 1);
    }
}