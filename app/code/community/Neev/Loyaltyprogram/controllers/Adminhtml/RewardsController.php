<?php
/**
 * Created by PhpStorm.
 * User: Subrahmanya
 * Date: 25/5/15
 * Time: 3:55 PM
 */
class Neev_Loyaltyprogram_Adminhtml_RewardsController extends Mage_Adminhtml_Controller_Action {

    public function init(){
        protected function _initAction() {
            $this->loadLayout()->_setActiveMenu("loyaltyprogram/loyaltyprogram")->
                _addBreadcrumb(Mage::helper("adminhtml")->__("Loyalty Program"), Mage::helper("adminhtml")->__("Rewards Details"));
            return $this;
        }
    }
}