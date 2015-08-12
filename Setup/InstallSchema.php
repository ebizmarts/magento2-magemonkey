<?php
/**
 * Author: info@ebizmarts.com
 * Date: 3/17/15
 * Time: 4:25 PM
 * File: InstallSchema.php
 * Module: magento2
 */
namespace Ebizmarts\MageMonkey\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();


        $connection->addColumn(
            $installer->getTable('newsletter_subscriber'),
            'magemonkey_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'default' => '',
                'comment' => 'Mailchimp reference'
            ]
        );
        $installer->endSetup();
    }
}