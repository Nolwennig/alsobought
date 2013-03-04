<?php
/**
 * @category    Lost
 * @package     Lost_Alsobought
 * @copyright   Copyright (c) 2012 George Schiopu <george@myownsummer.co.uk>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Lost_Alsobought_Block_Product_List extends Mage_Catalog_Block_Product_Abstract
{
	protected $enabled;
	protected $prodLimit;
	protected $_productId = '';
	protected $_productIds = array();
	
	public function _construct()
	{
		$this->enabled = Mage::getStoreConfig('catalog/alsobought/enabled',Mage::app()->getStore()->getId());
		$this->prodLimit = Mage::getStoreConfig('catalog/alsobought/prodlimit',Mage::app()->getStore()->getId());
		$this->_productId = $this->getProduct()->getId();
		return parent::_construct();
	}
	
	public function getProductsList()
	{
		if(!$this->enabled)
		{
			return;
		}
		$this->_productIds = Mage::getModel('alsobought/sales')
					->getProductsRelated($this->_productId);
		if($this->_productIds)
		{
			$productsCollection = Mage::getModel('catalog/product')
						->getCollection()
						->addAttributeToSelect('*')
						->addAttributeToFilter('entity_id',array('in'=>$this->_productIds))
						->addAttributeToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
						->setPageSize($this->prodLimit);
			$productsCollection->getSelect()->order("find_in_set(e.entity_id,'".implode(',',$this->_productIds)."')");
			if(count($productsCollection))
			{
				return($productsCollection);
			}
		}
	}
}