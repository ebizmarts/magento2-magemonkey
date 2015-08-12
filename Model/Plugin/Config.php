<?php
/**
 * Author: info@ebizmarts.com
 * Date: 12/08/15
 * Time: 03:20 PM
 * File: Config.php
 * Module: magento2-magemonkey
 */
namespace Ebizmarts\MageMonkey\Model\Plugin;
class Config
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;
    /**
     * @var \Magento\Framework\Module\ModuleList\Loader
     */
    protected $_loader;
    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $_writer;
    /**
     * @var \Ebizmarts\MageMonkey\Helper\Data
     */
    protected $_helper;
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Module\ModuleList\Loader $loader
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Ebizmarts\MageMonkey\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Module\ModuleList\Loader $loader,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Ebizmarts\MageMonkey\Helper\Data $helper
    )
    {
        $this->_objectManager = $objectManager;
        $this->_logger = $logger;
        $this->_loader = $loader;
        $this->_writer = $configWriter;
        $this->_helper = $helper;
    }
    public function aroundSave(\Magento\Config\Model\config $config,\Closure $proceed)
    {
        $ret = $proceed();
        $sectionId = $config->getSection();
        if($sectionId=='magemonkey')
        {
            $this->_helper->createWebhook();
        }
        return $ret;
    }
}