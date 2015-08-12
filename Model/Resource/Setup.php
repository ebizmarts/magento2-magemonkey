<?php
/**
 * Author: info@ebizmarts.com
 * Date: 3/4/15
 * Time: 12:59 AM
 * File: Setup.php
 * Module: magento2
 */

namespace Ebizmarts\MageMonkey\Model\Resource;

class Setup  extends \Magento\Framework\Module\DataSetup
{
    /**
     * @param \Magento\Framework\Module\Setup\Context $context
     * @param string $resourceName
     * @param string $moduleName
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Module\Setup\Context $context,
        $resourceName,
        $moduleName = 'Ebizmarts_MageMonkey',
        $connectionName = \Magento\Framework\Module\Updater\SetupInterface::DEFAULT_SETUP_CONNECTION
    ) {
        parent::__construct($context, $resourceName, $moduleName, $connectionName);
    }
}