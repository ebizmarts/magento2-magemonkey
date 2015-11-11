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