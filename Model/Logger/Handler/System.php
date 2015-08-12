<?php
/**
 * Author: info@ebizmarts.com
 * Date: 3/3/15
 * Time: 12:51 PM
 * File: System.php
 * Module: magento2
 */
namespace Ebizmarts\MageMonkey\Model\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class System extends \Magento\Framework\Logger\Handler\System
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/MageMonkey.log';
}
