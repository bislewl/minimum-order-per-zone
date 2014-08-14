<?php
// use $configuration_group_id where needed
$db->Execute("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) "
        . "VALUES ('Enable for Order Total or Item Count', 'MINIMUM_PER_ORDER_ZONE_ENABLED', 'disabled', 'Select if you would like the minimum order per zone to use the total amount OR the total number of items.', ".$configuration_group_id.", '2', 'zen_cfg_select_option(array(\'disabled\', \'total\', \'count\'), ', now());");
$db->Execute("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) "
        . "VALUES ('Minimum Values', 'MINIMUM_PER_ORDER_ZONE_VALUE', '', 'Enter the minimum order per zone for ALL zones you wish to ship to. in the following format. <br /> Amount:Zone <br />Example: <i>50.00:12,150.00:17</i>', ".$configuration_group_id.", '3',  now());");


// For Admin Pages

$zc150 = (PROJECT_VERSION_MAJOR > 1 || (PROJECT_VERSION_MAJOR == 1 && substr(PROJECT_VERSION_MINOR, 0, 3) >= 5));
if ($zc150) { // continue Zen Cart 1.5.0
  // delete configuration menu
  $db->Execute("DELETE FROM ".TABLE_ADMIN_PAGES." WHERE page_key = 'configMinOrderPerZone' LIMIT 1;");
  // add configuration menu
  if (!zen_page_key_exists('configMinOrderPerZone')) {
    if ((int)$configuration_group_id > 0) {
      zen_register_admin_page('configMinOrderPerZone',
                              'BOX_CONFIGURATION_MINIMUM_ORDER_PER_ZONE', 
                              'FILENAME_CONFIGURATION',
                              'gID=' . $configuration_group_id, 
                              'configuration', 
                              'Y',
                              $configuration_group_id);
        
      $messageStack->add('Enabled Minimum Order Per Zone Configuration Menu.', 'success');
    }
  }
}
