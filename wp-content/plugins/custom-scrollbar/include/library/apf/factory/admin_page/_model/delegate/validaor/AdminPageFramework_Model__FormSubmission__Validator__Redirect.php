<?php 
/**
	Admin Page Framework v3.8.13b07 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/custom-scrollbar>
	Copyright (c) 2013-2016, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class CustomScrollbar_AdminPageFramework_Model__FormSubmission__Validator__Redirect extends CustomScrollbar_AdminPageFramework_Model__FormSubmission__Validator_Base {
    public $sActionHookPrefix = 'try_validation_before_';
    public $iHookPriority = 40;
    public $iCallbackParameters = 5;
    public function _replyToCallback($aInputs, $aRawInputs, array $aSubmits, $aSubmitInformation, $oFactory) {
        $_sRedirectURL = $this->_getPressedSubmitButtonData($aSubmits, 'redirect_url');
        if (!$_sRedirectURL) {
            return;
        }
        add_filter("options_update_status_{$this->oFactory->oProp->sClassName}", array($this, '_replyToSetStatus'));
        $this->_setRedirectTransients($_sRedirectURL, $this->getElement($aSubmitInformation, 'page_slug'));
    }
    public function _replyToSetStatus($aStatus) {
        return array('confirmation' => 'redirect') + $aStatus;
    }
    private function _setRedirectTransients($sURL, $sPageSlug) {
        if (empty($sURL)) {
            return;
        }
        $_sTransient = 'apf_rurl' . md5(trim("redirect_{$this->oFactory->oProp->sClassName}_{$sPageSlug}"));
        return $this->setTransient($_sTransient, $sURL, 60 * 2);
    }
}
