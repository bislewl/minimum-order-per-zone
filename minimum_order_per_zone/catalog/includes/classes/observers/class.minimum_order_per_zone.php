<?php
/**
 * class.minimum_order_amount.php
 *
 * @copyright Copyright 2005-2007 Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2006 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: config.minimum_order_amount.php 1.0.1 20.09.2007 0:06 AndrewBerezin $
 */

/**
 * Observer class used to check minimum order amount
 *
 */
class minimum_order_per_zone extends base {
  /**
   * constructor method
   *
   * Attaches our class to the ... and watches for 4 notifier events.
   */
  function minimum_order_per_zone() {
    global $zco_notifier;
//      $_SESSION['cart']->attach($this, array('NOTIFIER_CART_GET_PRODUCTS_START', 'NOTIFIER_CART_GET_PRODUCTS_END'));
    $zco_notifier->attach($this, array('NOTIFY_HEADER_END_SHOPPING_CART', 'NOTIFY_HEADER_START_CHECKOUT_SHIPPING', 'NOTIFY_HEADER_START_CHECKOUT_PAYMENT', 'NOTIFY_HEADER_START_CHECKOUT_CONFIRMATION'));
  }
  /**
   * Update Method
   *
   * Called by observed class when any of our notifiable events occur
   *
   * @param object $class
   * @param string $eventID
   */
  function update(&$class, $eventID) {
    global $messageStack;
    global $currencies;
	global $db;
        $_SESSION['valid_to_checkout'] = false;
        if($_SESSION['cart']->count_contents() > 0 && $_SESSION['customer_zone_id'] != '' && MINIMUM_PER_ORDER_ZONE_ENABLED != 'disabled'){
            $customers_zone = $_SESSSION['customer_zone_id'];
            $min_order_per_zone_db = str_replace(" ","",MINIMUM_PER_ORDER_ZONE_VALUE);
            $min_order_raw_zones = explode(",",$min_order_per_zone_db);
            foreach($min_order_raw_zones as $zone_set){
                $zone_temp_array = explode(":",$zone_set);
                $minimum_zones[$zone_temp_array[1]] = $zone_temp_array[0];
            }
            //get zone name
            $zones_query = $db->Execute("SELECT * FROM ".TABLE_ZONES." WHERE zone_id=".(int)$customers_zone);
            $zone_name = $zones_query->fields['zone_name'];
            switch(MINIMUM_PER_ORDER_ZONE_ENABLED){
                case 'total':
                    $min_order_cart_has = $_SESSION['cart']->show_total();
                    $error_text = sprintf(TEXT_MIN_ORDER_PER_ZONE, $currencies->format($minimum_zones[$customers_zone]), $zone_name);
                    break;
                case 'count':
                    $min_order_cart_has = $_SESSION['cart']->count_contents();
                    $error_text = sprintf(TEXT_MIN_ORDER_PER_ZONE, $minimum_zones[$customers_zone]." items ", $zone_name);
                    break;
            }
            if($min_order_cart_has >= $minimum_zones[$customers_zone]){
                $_SESSION['valid_to_checkout'] = true;
            }
            else{
                $_SESSION['valid_to_checkout'] = false;
		$messageStack->add('shopping_cart', $error_text . '<br />', 'caution');
                zen_redirect(zen_href_link(FILENAME_SHOPPING_CART));
            }
        }
        if ($_SESSION['cart']->count_contents() > 0 && MIN_ORDER_AMOUNT > 0) {
			  if($_SESSION['cart']->show_total() < MIN_ORDER_AMOUNT && $_SESSION['cart']->show_total() > 0) {
				$_SESSION['valid_to_checkout'] = false;
				$messageStack->add('shopping_cart', sprintf(TEXT_ORDER_UNDER_MIN_AMOUNT, $currencies->format(MIN_ORDER_AMOUNT)) . '<br />', 'caution');
			  }
			}
  }
}
