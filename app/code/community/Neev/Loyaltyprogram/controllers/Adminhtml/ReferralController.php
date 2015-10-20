<?php
/**
 * Created by PhpStorm.
 * User: Subrahmanya
 * Date: 25/5/15
 * Time: 3:02 PM
 */
class Neev_Loyaltyprogram_Adminhtml_ReferralController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu("loyaltyprogram/loyaltyprogram")->
                            _addBreadcrumb(Mage::helper("adminhtml")->__("Loyalty Program"), Mage::helper("adminhtml")->__("Referral Details"));
        return $this;
    }

    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }
}