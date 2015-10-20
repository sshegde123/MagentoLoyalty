<?php
class Neev_Loyaltyprogram_Model_Mysql4_Referral extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init("loyaltyprogram/referral", "loyaltyprogram_referral_id");
    }

}