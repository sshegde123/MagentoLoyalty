<?php
class Neev_Loyaltyprogram_Model_Mysql4_Referral_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

	public function _construct()
    {
        parent::_construct();
        $this->_init('loyaltyprogram/referral');
    }

    protected function _initSelect()
    {
        parent::_initSelect();
        $select = $this->getSelect();
        $select->join(
            array('cust' => $this->getTable('loyaltyprogram/customer_entity')),
            'referer_id = cust.entity_id'
        );
        return $this;
    }

    public function addEmailFilter($email)
    {
        $this->getSelect()->where('referral_email = ?', $email);
        return $this;
    }

    public function addReferrerFilter($referrerId)
    {
        $this->getSelect()->where('referer_id = ?', $referrerId);
        return $this;
    }

    public function addFlagFilter($status)
    {
        $this->getSelect()->where('status = ?', $status);
        return $this;
    }

//    public function addClientFilter($id)
//    {
//        $this->getSelect()->where('referer_id = ?', $id);
//        return $this;
//    }

    public function addReferralCodeFilter($refereeCode){
        $this->getSelect()->where('referral_code = ?', $refereeCode);
        return $this;
    }

    public function isActive(){
        $this->getSelect()->where('status = ?', 0);
        return $this;
    }

}