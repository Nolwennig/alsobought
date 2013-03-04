<?php
/**
 * @category    Lost
 * @package     Lost_Alsobought
 * @copyright   Copyright (c) 2012 George Schiopu <george@myownsummer.co.uk>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Lost_Alsobought_Model_Sales extends Mage_Sales_Model_Order
{	
	var $orders_ids = array();
	
	public function _construct()
	{
		return parent::_construct();
	}
	
	public function getProductsRelated($product_id)
	{
		// Get orders IDs where the current product was ordered
		$this->orders_ids = $this->_getOrderIdsContaining($product_id);
		// Based on that, get OTHER products from the same orders
		$this->products_ids = $this->_getRelatedProducts($product_id);
		
		return($this->products_ids);
	}
	
	protected function _getOrderIdsContaining($product_id)
	{
		$ordersIds = array();
		$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		$sql="SELECT order_id FROM sales_flat_order_item WHERE product_id=".(int)$product_id." ORDER BY created_at DESC LIMIT 100";
		$res = $connection->query($sql);
		while($row=$res->fetch())
		{
			$ordersIds[]=$row['order_id'];
		}
		return($ordersIds);
	}
	
	protected function _getRelatedProducts($product_id)
	{
		if(is_array($this->orders_ids) && !empty($this->orders_ids))
		{
			$productIds=array();
			$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
			$sql="SELECT product_id, count(product_id) as incidences FROM sales_flat_order_item WHERE order_id IN (".join(',',$this->orders_ids).") AND product_id!=".(int)$product_id." GROUP BY product_id ORDER BY incidences DESC, created_at DESC LIMIT 50";
			$res = $connection->query($sql);
			while($row=$res->fetch())
			{
				$productIds[]=$row['product_id'];
			}
			return($productIds);
		}
	}
}
