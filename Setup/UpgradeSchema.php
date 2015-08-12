<?php
/**
 * Author: info@ebizmarts.com
 * Date: 3/17/15
 * Time: 4:41 PM
 * File: UpgradeSchema.php
 * Module: magento2
 */

namespace Ebizmarts\MageMonkey\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        $installer->endSetup();
    }
}