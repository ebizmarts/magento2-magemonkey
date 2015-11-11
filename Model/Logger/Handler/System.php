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
