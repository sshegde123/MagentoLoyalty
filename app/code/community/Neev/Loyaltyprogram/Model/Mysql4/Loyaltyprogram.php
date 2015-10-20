<?php
class Neev_Loyaltyprogram_Model_Mysql4_Loyaltyprogram extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init("loyaltyprogram/loyaltyprogram", "loyaltyprogram_referal_id");
    }

}