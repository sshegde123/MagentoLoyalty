<?php
/**
 * Created by PhpStorm.
 * User: Subrahmanya
 * Date: 29/6/15
 * Time: 11:51 AM
 * Observer class to execute Loyalty program related events
 */

class Neev_Loyaltyprogram_Model_Observer {
    /**
     * Function to update Referral details after customer registartion
     */
    public function updateReferralDetails($eventObj){
        try{
            $customerData = $eventObj->getCustomer()->getData();
            if($customerData['referral_code'] && Mage::getSingleton('loyaltyprogram/referral')->isReferralModuleActive()){
                $data['referral_email'] = $customerData['email'];
                $data['referral_id'] = $customerData['entity_id'];
                $data['status'] = 1;
                $data['registered_on'] = Mage::getModel('core/date')->date('Y-m-d H:i:s', time());
                $data['referral_code'] = $customerData['referral_code'];
                $referralDetails = Mage::getModel('loyaltyprogram/referral')
                    ->load($customerData['referral_code'],'referral_code');
                $data['loyaltyprogram_referral_id'] = $referralDetails->getLoyaltyprogramReferralId();
                $referralDetails->setData($data)->save();
                $session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
                $session->setData('referralId',null);
                $session->setData('referralDetails',null);
            }
        } catch(Exception $e){
            Mage::logException($e);
        }
    }
} 