<?php
/**
 * @category    Lost
 * @package     Lost_Alsobought
 * @copyright   Copyright (c) 2012 George Schiopu <george@myownsummer.co.uk>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Lost_Alsobought_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	public function pre($obj)
	{
		if(is_array($obj))
		{
			echo '<pre>';
			print_r($obj);
			echo '</pre>';
		}
		else
		{
			echo '<pre>';
			print_r(get_class_methods($obj));
			echo '</pre>';
		}
		
	}
}
