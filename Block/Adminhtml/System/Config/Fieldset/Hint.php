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

namespace Ebizmarts\MageMonkey\Block\Adminhtml\System\Config\Fieldset;
class Hint extends \Magento\Backend\Block\Template implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * @var string
     */
    protected $_template = 'Ebizmarts_MageMonkey::system/config/fieldset/hint.phtml';
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $_metaData;
    /**
     * @var \Magento\Framework\Module\ModuleList\Loader
     */
    protected $_loader;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetaData
     * @param \Magento\Framework\Module\ModuleList\Loader $loader
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\ProductMetadataInterface $productMetaData,
        \Magento\Framework\Module\ModuleList\Loader $loader,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_metaData = $productMetaData;
        $this->_loader = $loader;
    }
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return mixed
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->toHtml();
    }
    public function getPxParams()
    {
        $modules = $this->_loader->load();
        $v = "";
        if(isset($modules['Ebizmarts_MageMonkey']))
        {
            $v =$modules['Ebizmarts_MageMonkey']['setup_version'];
        }
        $extension = "MageMonkey;{$v}";
        $mageEdition = $this->_metaData->getEdition();
        switch($mageEdition)
        {
            case 'Community':
                $mageEdition = 'CE';
                break;
            case 'Enterprise':
                $mageEdition = 'EE';
                break;
        }
        $mageVersion = $this->_metaData->getVersion();
        $mage = "Magento {$mageEdition};{$mageVersion}";
        $hash = md5($extension . '_' . $mage . '_' . $extension);
        return "ext=$extension&mage={$mage}&ctrl={$hash}";
    }
    public function getVersion()
    {
        $modules = $this->_loader->load();
        $v = "";
        if(isset($modules['Ebizmarts_MageMonkey']))
        {
            $v =$modules['Ebizmarts_MageMonkey']['setup_version'];
        }
        return $v;
    }
}