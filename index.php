<?php if(!defined('ABS_PATH')) exit();
/*
Plugin Name: Open Street Maps
Plugin URI: https://www.osclass.org/
Description: Open Street Maps with MapQuest GeoCoding API
Version: 1.0.4 mod
Author: Oleksiy Muzalyev & dev101
Author URI: https://forums.osclass.org/index.php?action=profile;u=37193
Plugin update URI: openstreetmaps
*/

// Get API key @ https://developer.mapquest.com/
const MAPQUEST_API_KEY = 'you_public_key_here';

// OpenStreetMap CSS
osc_enqueue_style('leaflet', osc_base_url() . 'oc-content/plugins/open_street_maps-master/css/leaflet.css');

// OpenStreetMap JavaScript
osc_register_script('leaflet', osc_base_url() . 'oc-content/plugins/open_street_maps-master/js/leaflet.js');
osc_enqueue_script('leaflet');

function osm_maps_location() {
	$item = osc_item();
	require 'map.php';
}

function osm_insert_geo_location($item) {

	$itemId = $item['pk_i_id'];
	$aItem = Item::newInstance()->findByPrimaryKey($itemId);
	$addr_comp = array();

	if (isset($aItem['s_country'])) {	
		$addr_comp[] = $aItem['s_country'];
	}

	if (isset($aItem['s_region'])) {
		$addr_comp[] = $aItem['s_region'];
	}

	if (isset($aItem['s_city'])) {	
		$addr_comp[] = $aItem['s_city'];
	}

	if (isset($aItem['s_address'])) {
		$addr_comp[] = $aItem['s_address'];
	}

	$address = implode(',' , $addr_comp);

	$lat = '';
	$lng = '';
	$xml = simplexml_load_file(sprintf('https://open.mapquestapi.com/nominatim/v1/search.php?key=' . MAPQUEST_API_KEY . '&q=%s&format=xml&addressdetails=1&limit=1', $address));

	foreach ($xml->place as $mpl) {
		if (isset($mpl['lat'])) {
			$lat = $mpl['lat'];
		}
		if (isset($mpl['lon'])) {
			$lng = $mpl['lon'];
		}
	}

	ItemLocation::newInstance()->update( array('d_coord_lat' => $lat ,'d_coord_long' => $lng) , array('fk_i_item_id' => $itemId) );

}

/**
 * debug function | embed into footer.php
 * <!-- OpenStreetMap Debug -->
 * <?php osm_debug(osc_item()); ?>
 */
function osm_debug($item = array()) {

	if ( osc_is_admin_user_logged_in() && osc_is_ad_page() ) {

		if (empty($item)) {
			$item = osc_item();
		}

		$itemId = $item['pk_i_id'];
		$aItem = Item::newInstance()->findByPrimaryKey($itemId);
		$addr_comp = array();

		if (isset($aItem['s_address'])) {
			$addr_comp[] = $aItem['s_address'];
		}

		if (isset($aItem['s_city'])) {	
			$addr_comp[] = $aItem['s_city'];
		}

		if (isset($aItem['s_region'])) {
			$addr_comp[] = $aItem['s_region'];
		}

		if (isset($aItem['s_country'])) {	
			$addr_comp[] = $aItem['s_country'];
		}

		$address = implode(',' , $addr_comp);

		$lat = '';
		$lng = '';
		$xml = simplexml_load_file(sprintf('https://open.mapquestapi.com/nominatim/v1/search.php?key=' . MAPQUEST_API_KEY . '&q=%s&format=xml&addressdetails=1&limit=1', $address));

		foreach ($xml->place as $mpl) {
			if (isset($mpl['lat'])) {
				$lat = $mpl['lat'];
			}
			if (isset($mpl['lon'])) {
				$lng = $mpl['lon'];
			}
		}

		// debug
		echo '<pre>';
		print_r($aItem);
		echo '</pre>';

		echo '<pre>';
		print_r($address);
		echo '</pre>';

		echo '<pre>';
		print_r($xml);
		echo '</pre>';

	}

}

// Activate Plugin
osc_register_plugin(osc_plugin_path(__FILE__), '');

// Uninstall Link
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', '');

// Location Hooks
osc_add_hook('location', 'osm_maps_location');
osc_add_hook('posted_item', 'osm_insert_geo_location');
osc_add_hook('edited_item', 'osm_insert_geo_location');

// Debug OSM Plugin
// osc_add_hook('footer', 'osm_debug');

?>
