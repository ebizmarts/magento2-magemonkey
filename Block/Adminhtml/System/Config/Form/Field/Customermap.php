<?php
/**
 * Author: info@ebizmarts.com
 * Date: 3/5/15
 * Time: 1:25 PM
 * File: Customermap.php
 * Module: magento2
 */
namespace Ebizmarts\MageMonkey\Block\Adminhtml\System\Config\Form\Field;

class Customermap extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        array $data = []
    )
    {
        $this->_elementFactory  = $elementFactory;
        parent::__construct($context,$data);
    }
    protected function _construct()
    {
        $this->addColumn('magento', ['label' => __('Magento')]);
        $this->addColumn('mailchimp', ['label' => __('MailChimp')]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
        parent::_construct();
    }

}