<?php
class Neev_Loyaltyprogram_Model_Referral extends Neev_Loyaltyprogram_Model_Loyaltyprogram{
	
	public function _construct()
    {
        parent::_construct();
        $this->_init('loyaltyprogram/referral');
    }

    public function isReferralModuleActive(){
        if(Mage::getStoreConfig("loyaltyprogram/loyaltyprogram_referrals/loyaltyprogram_referrals_enabled",Mage::app()->getStore())){
            return true;
        } else {
            return false;
        }
    }
	/**
	*Function to validate referal details
	*
	**/
	public function isReferalDetailsExists($referalEmail, $refererId, $referalName){
		try{
			//$referalDetails = $this->getReferalDetails($referalEmail, $refererId, $referalName);
			if(Mage::getModel('loyaltyprogram/referral')->getCollection()->addEmailFilter($referalEmail)->addReferrerFilter($refererId)->getData()){
                return true;
            } else {
                return false;
            }

		} catch (Exception $e){
			Mage::logException($e);
			return false;
		}
	}

	/***
	*	Function to get Referal details
	*   Return array
	****/
	public function getReferalDetails($referalEmail, $refererId, $referalName){
		try{
			$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
			$select = $connection->select()
						->from('loyaltyprogram_referal', array('*'))
						->where('refererer_id =?  and referal_email = ?',
								array('refererer_id' => $refererId, 'referal_email' => $referalEmail));
			$referalDetails = $connection->fetchAll($select);
			return $referalDetails;
		} catch (Exception $e){
			Mage::logException($e);
			return null;
		}
		
	}

    public function updateReferralByCode($referral_code){
        try{

        } catch (Exception $e){
            Mage::logException($e);
            return null;
        }
    }
}