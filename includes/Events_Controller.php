<?php

	class Events_Controller extends WP_REST_Posts_Controller {

		public function __construct() {

			parent::__construct('event');

		}

		public function register_routes() {

			parent::register_routes();

		}

		public function get_offer_meta() {

			$times_query = "SELECT wp_posts.ID AS post_id, start.start_time, end.end_time, date.date, redeem.redeemable FROM wp_posts
					INNER JOIN (SELECT post_id, meta_value AS start_time FROM wp_postmeta WHERE meta_key = 'start_time') AS start ON (wp_posts.ID = start.post_id)
					INNER JOIN (SELECT post_id, meta_value AS end_time FROM wp_postmeta WHERE meta_key = 'end_time') AS end ON (wp_posts.ID = end.post_id)
					INNER JOIN (SELECT post_id, meta_value AS date FROM wp_postmeta WHERE meta_key = 'date') AS date ON (wp_posts.ID = date.post_id)
					INNER JOIN (SELECT post_id, meta_value AS redeemable FROM wp_postmeta WHERE meta_key = 'maximum_redeemable') AS redeem ON (wp_posts.ID = redeem.post_id)";

			return $times_query;

		}

		public function get_offers($request) {

			//var_dump($request);

			$offers = array("2 For 1", "50% Off");
			
			$response  = rest_ensure_response( $offers );

			return $response;


		}

		public function get_item($request) {

			global $wpdb;

			$id = $request['id'];

			$offer_query = "SELECT wp_posts.ID, post_title AS name, start_time, end_time, date, redeemable FROM wp_posts
					INNER JOIN (" . $this->get_offer_meta() . ") AS times ON (wp_posts.ID = times.post_id)
					WHERE post_type = 'offer' AND post_status = 'publish' AND ID = " . $id;

			$offer = $wpdb->get_row($offer_query);

			$response = rest_ensure_response($offer);
			return $response;

		}

		public function get_items($request) {

			global $wpdb;

			$offers_query = "SELECT wp_posts.ID, post_title AS name, start_time, end_time, date, redeemable FROM wp_posts
					 INNER JOIN (" . $this->get_offer_meta() . ") AS times ON (wp_posts.ID = times.post_id)
					 WHERE post_type = 'offer' AND post_status = 'publish'";

			$offers = $wpdb->get_results($offers_query);

			foreach ($offers as $offer) {

				/*$venue->address["address_line_1"] = $venue->address_line_1;
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
				unset($venue->facebook);*/


			}

			$response  = rest_ensure_response( $offers );

			return $response;

		}

	}

	function kino_register_offers_rest_routes() {

		$controller = new Offers_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_offers_rest_routes');

?>
