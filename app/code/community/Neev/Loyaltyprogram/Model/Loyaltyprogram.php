<?php
class Neev_Loyaltyprogram_Model_Loyaltyprogram extends Mage_Core_Model_Abstract {

	public function _construct()
    {
        parent::_construct();
        $this->_init('loyaltyprogram/loyaltyprogram');
    }
}