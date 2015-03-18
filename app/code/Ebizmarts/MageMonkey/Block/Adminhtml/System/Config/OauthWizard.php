<?php
/**
 * Author: info@ebizmarts.com
 * Date: 3/18/15
 * Time: 1:59 PM
 * File: OauthWizard.php
 * Module: magento2
 */
namespace Ebizmarts\MageMonkey\Block\Adminhtml\System\Config;

class OauthWizard extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template    = 'system/config/oauth_wizard.phtml';

    protected $_authorizeUri     = "https://login.mailchimp.com/oauth2/authorize";
    protected $_accessTokenUri   = "https://login.mailchimp.com/oauth2/token";
    protected $_redirectUri      = "http://ebizmarts.com/magento/mailchimp/oauth2/complete.php";
    protected $_clientId         = 213915096176;

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
        $originalData = $element->getOriginalData();

        $label = $originalData['button_label'];

        $this->addData(array(
            'button_label' => __($label),
            'button_url'   => $this->authorizeRequestUrl(),
            'html_id' => $element->getHtmlId(),
        ));
        return $this->_toHtml();
    }
    public function authorizeRequestUrl() {

        $url = $this->_authorizeUri;
        $redirectUri = urlencode($this->_redirectUri);

        return "{$url}?redirect_uri={$redirectUri}&response_type=code&client_id={$this->_clientId}";
    }

}