<?php 
$installer = $this;
$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('loyaltyprogram_referral')};
	CREATE TABLE {$this->getTable('loyaltyprogram_referral')} (
		loyaltyprogram_referral_id bigint(20) NOT NULL AUTO_INCREMENT,
		referer_id bigint(20) UNSIGNED NOT NULL,
		referral_id bigint(20) UNSIGNED DEFAULT NULL,
		referral_email varchar(255)  DEFAULT NULL,
		referral_name varchar(255)  DEFAULT NULL,
		referral_code varchar(255)  DEFAULT NULL,
		store_id integer(10) UNSIGNED NOT NULL DEFAULT 1,
		status varchar(50) DEFAULT 0,
		refered_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		registered_on timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY(loyaltyprogram_referral_id),
		UNIQUE KEY (referral_id),
		KEY (referer_id)
	) ENGINE = InnoDB DEFAULT CHARSET=utf8;

	DROP TABLE IF EXISTS {$this->getTable('loyaltyprogram_rewards')};
	CREATE TABLE {$this->getTable('loyaltyprogram_rewards')}(
		loyaltyprogram_rewards_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		customer_id bigint(20) UNSIGNED NOT NULL DEFAULT 1,
		store_id integer(3) UNSIGNED NOT NULL DEFAULT 1,
		total_points_earned decimal(10,2) NOT NULL DEFAULT 0.00,
		total_points_spent decimal(10,2) NOT NULL DEFAULT 0.00,
		created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		modified_on timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY (loyaltyprogram_rewards_id),
		KEY (customer_id)
	)ENGINE=INNODB DEFAULT CHARSET=utf8;

	DROP TABLE IF EXISTS {$this->getTable('loyaltyprogram_rewards_transactions')};
	CREATE TABLE {$this->getTable('loyaltyprogram_rewards_transactions')}(
		loyaltyprogram_rewards_transactions_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		loyaltyprogram_rewards_id bigint(20) UNSIGNED NOT NULL DEFAULT 1,
		reward_transaction_type varchar(50) NOT NULL DEFAULT 'UNDEFINED',
		order_id varchar(100) DEFAULT NULL,
		points_earned decimal(10,2) NOT NULL default 0.00,
		point_spent decimal(10,2) NOT NULL DEFAULT 0.00,
		transaction_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		status varchar(25) NOT NULL DEFAULT 'UNDEFINED',
		PRIMARY KEY (loyaltyprogram_rewards_transactions_id),
		FOREIGN KEY (loyaltyprogram_rewards_id) REFERENCES loyaltyprogram_rewards(loyaltyprogram_rewards_id)
	)ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	");

$entity = $installer->getEntityTypeId('customer');

/*if(!$installer->attributeExists($entity, 'referral_code')) {
    $installer->removeAttribute($entity, 'referral_code');
}*/
$installer->removeAttribute($entity, 'referral_code');
$installer->addAttribute($entity, 'referral_code', array(
    'type' => 'varchar',
    'label' => 'Referral Code',
    'input' => 'text',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'default' => '0',
    'visible_on_front' => 1
));

$forms = array(
    'adminhtml_customer',
    'customer_account_edit',
    'customer_account_create'
);
$attribute = Mage::getSingleton('eav/config')->getAttribute($installer->getEntityTypeId('customer'), 'referral_code');
$attribute->setData('used_in_forms', $forms);
$attribute->save();

