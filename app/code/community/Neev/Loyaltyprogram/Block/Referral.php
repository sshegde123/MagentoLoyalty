<?php

class Neev_Loyaltyprogram_Block_Referral extends Mage_Core_Block_Template {

    public function _getSession(){
        return Mage::getSingleton("core/session",  array("name"=>"frontend"));
    }

    public function setReferralCode(){
        $session = $this->_getSession();
        if($this->getRequest()->getParam('rfId')!=""){
            $session->setData("referralId", $this->getRequest()->getParam('rfId'));
            return $this->getRequest()->getParam('rfId');
        } else {
            if($session->getData('referralId')){
                return $session->getData('referralId');
            }
            return false;
        }

    }

    /**
     * function to get Referral details
     */
    public function getRefereeDetails($refereeCode){
        try{
            $session = $this->_getSession();
            $referralDetails = Mage::getModel('loyaltyprogram/referral')
                                ->getCollection()
                                ->addReferralCodeFilter($refereeCode)
                                ->isActive()
                                ->getData();
            $session->setData('referralDetails',$referralDetails[0]);
            return $referralDetails[0];
        } catch(Exception $e){
            Mage::logException($e);
            return false;
        }
    }

    /**
     * Function to get Referral details
     */
    public function getReferralPersonalDetails($referralId){
        return Mage::getSingleton('customer/customer')->load($referralId)->getData();
    }

    /*
     * Function list all referral for logged in customer
     */
    public function getMyReferralList(){

        return Mage::getModel('loyaltyprogram/referral')
            ->getCollection()
            ->addReferrerFilter($this->getCustomerSession()->getCustomer()->getId())
            ->setOrder('loyaltyprogram_referral_id','DESC')
            ->getData();
    }

    public function getCustomerSession(){
        return Mage::getSingleton('customer/session');
    }
}