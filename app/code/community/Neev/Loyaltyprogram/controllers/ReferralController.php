<?php
class Neev_Loyaltyprogram_ReferralController extends Mage_Core_Controller_Front_Action {

	public function preDispatch() {
         //a brute-force protection here would be nice

         Mage_Core_Controller_Front_Action::preDispatch();

         if (!$this->getRequest()->isDispatched()) {
             return;
         }
        if(!Mage::getStoreConfig("loyaltyprogram/loyaltyprogram_referrals/loyaltyprogram_referrals_enabled",Mage::app()->getStore())){
            $this->_redirectUrl(Mage::helper('customer')->getDashboardUrl());
            return;
        }
         /** Redirect to login if the user is not logged in */
         $action = $this->getRequest()->getActionName();
         $openActions = array('setReferer');
         $pattern = '/^(' . implode('|', $openActions) . ')/i';

        if (!preg_match($pattern, $action)) {
            if(!$this->_getCustomerSession()->authenticate($this)){
                $currentUrl = Mage::helper('core/url')->getCurrentUrl();
                Mage::getSingleton('core/session')->setBeforeAuthUrl($currentUrl);
                $this->setFlag('', 'no-dispatch', true);
                return;
            }
        }
    }

    public function _getSession(){
        return Mage::getSingleton('core/session');
    }

    public function indexAction(){
    	$this->loadLayout();
        $this->renderLayout();
    }

    /**
    *Function to send email to the  customer whose email is entered in the form
    * @array emails
    *
    ***/
    public function referralPostAction(){
        try {
            $email = $this->getRequest()->getPost('email');
            $emailValidator = new Zend_Validate_EmailAddress();

            if($emailValidator->isValid($email)){
                $customer = Mage::getModel("customer/customer");
                $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
                $customer->loadByEmail($email);
                if($customer->getId()){
                    throw new Exception("Customer Already registered in our web site.");
                }
                $session = $this->_getCustomerSession();
                $name = $this->getRequest()->getPost('name');;
                $refererCode = md5(time());
                $refererLink = Mage::getUrl('customer/account/create', array('_secure'=>true))."?rfId=".$refererCode;
                $refererName = $session->getCustomer()->getName();


                if(!Mage::getSingleton('loyaltyprogram/referral')->isReferalDetailsExists($email ,$session->getCustomer()->getId(), $name)){
                    $vars = array(
                        'name'=> $name,
                        'referLink' => $refererLink,
                        'refererName' => $refererName
                    );
                    $senderDetails = array('name' => $name,
                                           'email' => $email,
                                           'ccEmail' => $session->getCustomer()->getEmail()
                                    );
                    $this->sendEmail($vars,$senderDetails);
                    $data['referer_id'] = $this->_getCustomerSession()->getCustomer()->getId();
                    $data['referral_email'] = $email;
                    $data['referral_name'] = $name;
                    $data['referral_code'] = $refererCode;
                    Mage::getModel('loyaltyprogram/referral')->setData($data)->save();
                    Mage::getSingleton("core/session")->addSuccess("Referral request sent. Thanks for referring your friend.");
                } else {
                    throw new Exception("You have already referred ".$email.".");
                }
            } else{
                throw new Exception("Invalid Email. Please enter correct email.");
                
            }
        } catch(Exception $e){
             echo "Exception".$e->getMessage();
            Mage::getSingleton("core/session")->addError($e->getMessage());
            Mage::logException($e);
        }
        $this->_redirect('*/*/');
    }

    /**
     * Send email to referral
     */
    public function sendEmail($vars,$senderDetails){
        $emailTemplateCode = Mage::getStoreConfig("loyaltyprogram/loyaltyprogram_referrals/loyaltyprogram_referrals_email_template",Mage::app()->getStore());
        $sTemplate = Mage::getModel('core/email_template')
            ->loadByCode($emailTemplateCode);
            //->getProcessedTemplate($vars);
        Mage::getModel('core/email_template')->sendTransactional($sTemplate->getId(), $senderDetails['name'], $senderDetails['email'] , '', $vars, Mage::app()->getStore());
    }

    /**
     * Function to resend referral email
     */
    public function resendEmailAction(){
        try{
            if($this->getRequest()->getParam('code')){
                $session = $this->_getCustomerSession();
                $referralDetails = Mage::getSingleton('loyaltyprogram/referral')
                                    ->getCollection()
                                    ->addReferralCodeFilter($this->getRequest()->getParam('code'))
                                    ->getData();
                $referrerLink = Mage::getUrl('customer/account/create', array('_secure'=>true))."?rfId=".$referralDetails[0]['referral_code'];
                $vars = array(
                    'name'=> $referralDetails[0]['referral_name'],
                    'referLink' => $referrerLink,
                    'referrerName' => $session->getCustomer()->getName()
                );
                $senderDetails = array('name' => $referralDetails[0]['referral_name'],
                    'email' => $referralDetails[0]['referral_email'],
                    'ccEmail' => $session->getCustomer()->getEmail()
                );
                $this->sendEmail($vars,$senderDetails);
                $this->_getSession()->addSuccess('Email resnt to referral email '.$senderDetails['email']);
            } else {
                throw new Exception("Invalid Referral code.");
            }
        } catch(Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            Mage::logException($e);
        }
        $this->_redirect('*/*/');
    }
    /**
    * Function to initialize magento session
    **/
    private function _getCustomerSession() {
        Mage::getSingleton('core/session', array('name' => 'frontend'));
        return Mage::getSingleton('customer/session');
    }

    /**
     * Function to set referral code in session which is used while registering
     *
     */
    public function setRefererAction(){
        if($this->_getCustomerSession()->getCustomer()->getId()){
            $this->_redirectUrl(Mage::helper('customer')->getDashboardUrl());
            return;
        } else {
            //echo $this->getRequest()->getParam('rfId');exit;
            $rfCode = "";
            if($this->getRequest()->getParam('rfId')!=""){
                $session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
                // set data
                //echo $this->getRequest()->getParam('rfId');exit;
                $session->setData("referralId", $this->getRequest()->getParam('rfId'));
                $rfCode = "?rfId=".$this->getRequest()->getParam('rfId');
            }
            $url = Mage::helper('customer')->getRegisterUrl().$rfCode;
            $this->_redirectUrl($url);
        }
    }
}