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
class minimumOrderPerZone extends base {
  /**
   * constructor method
   *
   * Attaches our class to the ... and watches for 4 notifier events.
   */
  function __construct(){
    global $zco_notifier;
    $zco_notifier->attach($this, array('NOTIFY_HEADER_START_CHECKOUT','NOTIFY_HEADER_START_CHECKOUT_SHIPPING'));
  }
  /**
   * Update Method
   *
   * Called by observed class when any of our notifiable events occur
   *
   * @param object $class
   * @param string $eventID
   */
  function update(&$class, $eventID, $paramsArray) {
    global $messageStack;
    global $currencies;
	global $db;
        $_SESSION['valid_to_checkout'] = false;
        if($_SESSION['cart']->count_contents() > 0 && isset($_SESSION['customer_zone_id'])){
            $customers_session_zone = $_SESSION['customer_zone_id'];
            $min_order_per_zone_db = str_replace(" ","",MINIMUM_PER_ORDER_ZONE_VALUE);
            $min_order_raw_zones = explode(",",$min_order_per_zone_db);
            foreach($min_order_raw_zones as $zone_set){
                $zone_temp_array = explode(":",$zone_set);
                $minimum_zones[$zone_temp_array[1]] = $zone_temp_array[0];
            }
            //get zone name
            $to_geo_zone_query = $db->Execute("SELECT * FROM ".TABLE_ZONES_TO_GEO_ZONES." WHERE zone_id=".(int)$customers_session_zone);
            $geo_zones_query = $db->Execute("SELECT * FROM ".TABLE_ZONES_TO_GEO_ZONES." WHERE zone_id=".(int)$to_geo_zone_query->fields['geo_zone_id']);
            $zone_name = $geo_zones_query->fields['geo_zone_name'];
            $min_values_for_zone = array();
            while(!$to_geo_zone_query->EOF){
                $zones_customer_in = $to_geo_zone_query->fields['geo_zone_id'];
                if(isset($minimum_zones[$zones_customer_in])){
                    $min_values_for_zone[] = $minimum_zones[$zones_customer_in];
                }
                $to_geo_zone_query->MoveNext();
            }
            rsort($min_values_for_zone);
            $min_req_in_zone_raw = $min_values_for_zone[0];
            switch(MINIMUM_PER_ORDER_ZONE_ENABLED){
                case 'total':
                    $min_order_cart_has = number_format($_SESSION['cart']->show_total(),2);
                    $error_text = sprintf(TEXT_MIN_ORDER_PER_ZONE, $currencies->format($minimum_zones[$customers_zone]), $zone_name);
                    $min_req_in_zone = number_format($min_req_in_zone_raw,2);
                    break;
                case 'count':
                    $min_order_cart_has = (int)$_SESSION['cart']->count_contents();
                    $min_req_in_zone = (int)$min_req_in_zone_raw;
                    $error_text = sprintf(TEXT_MIN_ORDER_PER_ZONE, $minimum_zones[$customers_zone]." items ", $zone_name);
                    break;
            }
            if($min_order_cart_has > $min_req_in_zone){
                $_SESSION['valid_to_checkout'] = true;
            }
            else{
                $_SESSION['valid_to_checkout'] = false;
		$messageStack->add('shopping_cart', $error_text . '<br />', 'caution');
                zen_redirect(zen_href_link(FILENAME_SHOPPING_CART));
            }
        }
  }
}
