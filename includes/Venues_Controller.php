<?php

	class Venues_Controller extends WP_REST_Posts_Controller {

		public function __construct() {

			parent::__construct('venue');

		}

		public function register_routes() {

			parent::register_routes();

			register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/offers', array(

				'args' => array(
					'id' => array(
						'description' => __('The Venues ID'),
						'type' => 'integer',
					),
				),
				array(
					'methods'	=> WP_REST_Server::READABLE,
					'callback' 	=> array( $this, 'get_offers'),
					'permission_callback' => array($this, 'get_item_permissions_check'),
					'args' 		=> $get_item_args,
				),
				array(
					'methods' 	=> WP_REST_Server::CREATABLE,
					'callback'	=> array( $this, 'create_item'),
					'permission_callback' => array($this, 'get_item_permissions_check'),
					'args'		=> $get_item_args,
				),
				'schema' => array( $this, 'get_public_item_schema'),

			));

		}

		public function get_offers($request) {

			//var_dump($request);

			$offers = array("2 For 1", "50% Off");
			
			$response  = rest_ensure_response( $offers );

			return $response;


		}

		public function get_venue_address_query() {

			$address_query = "SELECT wp_posts.ID, address_line_1, address_line_2, post_code, city FROM wp_posts 
					  INNER JOIN (SELECT post_id, meta_value AS address_line_1 FROM wp_postmeta WHERE meta_key = 'address_1') AS address1 ON (wp_posts.ID = address1.post_id)
					  INNER JOIN (SELECT post_id, meta_value AS address_line_2 FROM wp_postmeta WHERE meta_key = 'address_2') AS address2 ON (wp_posts.ID = address2.post_id)
					  INNER JOIN (SELECT post_id, meta_value AS post_code FROM wp_postmeta WHERE meta_key = 'post_code') AS address3 ON (wp_posts.ID = address3.post_id)
					  INNER JOIN (SELECT post_id, meta_value AS city FROM wp_postmeta WHERE meta_key = 'city') AS address4 ON (wp_posts.ID = address4.post_id)";

			return $address_query;

		}

		public function get_venue_contact_query() {

			$contact_query = "SELECT wp_posts.ID, phone, website, twitter, facebook FROM wp_posts
					INNER JOIN (SELECT post_id, meta_value AS phone FROM wp_postmeta WHERE meta_key = 'phone') AS phone ON (wp_posts.ID = phone.post_id)
					INNER JOIN (SELECT post_id, meta_value AS website FROM wp_postmeta WHERE meta_key = 'website') AS website ON (wp_posts.ID = website.post_id)
					INNER JOIN (SELECT post_id, meta_value AS twitter FROM wp_postmeta WHERE meta_key = 'twitter') AS twitter ON (wp_posts.ID = twitter.post_id)
					INNER JOIN (SELECT post_id, meta_value AS facebook FROM wp_postmeta WHERE meta_key = 'facebook') AS facebook ON (wp_posts.ID = facebook.post_id)";

			return $contact_query;

		}

		public function get_venue_address($id) {

			global $wpdb;
			
			$address = array();

			$address_query = "SELECT meta_value, address_line_2 FROM wp_postmeta 
					  INNER JOIN (SELECT meta_value AS address_line_2 FROM wp_postmeta WHERE meta_key = 'address_2') AS address2 ON (wp_postmeta.post_id = address2.post_id)
					  WHERE post_id = " . $id . " AND meta_key = 'address_1'";

			$address_query = "SELECT address_line_1, address_line_2, post_code, city FROM wp_posts 
			INNER JOIN (SELECT post_id, meta_value AS address_line_1 FROM wp_postmeta WHERE meta_key = 'address_1') AS address1 ON (wp_posts.ID = address1.post_id)
			INNER JOIN (SELECT post_id, meta_value AS address_line_2 FROM wp_postmeta WHERE meta_key = 'address_2') AS address2 ON (wp_posts.ID = address2.post_id)
			INNER JOIN (SELECT post_id, meta_value AS post_code FROM wp_postmeta WHERE meta_key = 'post_code') AS address3 ON (wp_posts.ID = address3.post_id)
			INNER JOIN (SELECT post_id, meta_value AS city FROM wp_postmeta WHERE meta_key = 'city') AS address4 ON (wp_posts.ID = address4.post_id)
			WHERE wp_posts.ID = " . $id;

			$address = $wpdb->get_row($address_query);

			return $address;

		}

		public function get_venue_contact_details($id) {

			global $wpdb;

			$address_query = "SELECT phone, website, twitter, facebook FROM wp_posts
INNER JOIN (SELECT post_id, meta_value AS phone FROM wp_postmeta WHERE meta_key = 'phone') AS phone ON (wp_posts.ID = phone.post_id)
INNER JOIN (SELECT post_id, meta_value AS website FROM wp_postmeta WHERE meta_key = 'website') AS website ON (wp_posts.ID = website.post_id)
INNER JOIN (SELECT post_id, meta_value AS twitter FROM wp_postmeta WHERE meta_key = 'twitter') AS twitter ON (wp_posts.ID = twitter.post_id)
INNER JOIN (SELECT post_id, meta_value AS facebook FROM wp_postmeta WHERE meta_key = 'facebook') AS facebook ON (wp_posts.ID = facebook.post_id)
WHERE wp_posts.ID = " . $id;

			$contact = $wpdb->get_row($address_query);

			return $contact;

		}

		public function get_items($request) {

			global $wpdb;

			$venues_query = "SELECT wp_posts.ID, post_title AS name, contact.*, address.* FROM wp_posts 
					INNER JOIN (" . $this->get_venue_address_query() . ") AS address ON (address.ID = wp_posts.ID)
					INNER JOIN (" . $this->get_venue_contact_query() . ") AS contact ON (contact.ID = wp_posts.ID)
					WHERE post_type = 'venue' AND post_status = 'publish'";

			$venues = $wpdb->get_results($venues_query);

			foreach ($venues as $venue) {

				$venue->address["address_line_1"] = $venue->address_line_1;
				$venue->address["address_line_2"] = $venue->address_line_2;
				$venue->address["post_code"] = $venue->post_code;
				$venue->address["city"] = $venue->city;

				$venue->contact['phone'] = $venue->phone;
				$venue->contact['website'] = $venue->website;
				$venue->contact['twitter'] = $venue->twitter;
				$venue->contact['facebook'] = $venue->facebook;


				//$venue->contact = $this->get_venue_contact_details($venue->ID);

				unset($venue->address_line_1);
				unset($venue->address_line_2);
				unset($venue->post_code);
				unset($venue->city);

				unset($venue->phone);
				unset($venue->website);
				unset($venue->twitter);
				unset($venue->facebook);


			}

			//$venues = array("Hello, World!");
			$response  = rest_ensure_response( $venues );

			return $response;

		}

	}

	function kino_register_venue_rest_routes() {

		$controller = new Venues_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_venue_rest_routes');

?>
