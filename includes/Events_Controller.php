<?php

	class Events_Controller extends WP_REST_Posts_Controller {

		public function __construct() {

			parent::__construct('event');

		}

		public function register_routes() {

			parent::register_routes();

		}

		public function get_event_meta() {

			$times_query = "SELECT wp_posts.ID AS post_id, start.start_time, end.end_time, date.date, price.price, repeatable.repeatable FROM wp_posts
					INNER JOIN (SELECT post_id, meta_value AS start_time FROM wp_postmeta WHERE meta_key = 'start_time') AS start ON (wp_posts.ID = start.post_id)
					INNER JOIN (SELECT post_id, meta_value AS end_time FROM wp_postmeta WHERE meta_key = 'end_time') AS end ON (wp_posts.ID = end.post_id)
					INNER JOIN (SELECT post_id, meta_value AS date FROM wp_postmeta WHERE meta_key = 'date') AS date ON (wp_posts.ID = date.post_id)
					INNER JOIN (SELECT post_id, meta_value AS price FROM wp_postmeta WHERE meta_key = 'ticket_price') AS price ON (wp_posts.ID = price.post_id)
					INNER JOIN (SELECT post_id, meta_value AS repeatable FROM wp_postmeta WHERE meta_key = 'repeat_event') AS repeatable ON (wp_posts.ID = repeatable.post_id)";

			return $times_query;

		}

		public function get_events($request) {

			//var_dump($request);

			$events = array("2 For 1", "50% Off");
			
			$response  = rest_ensure_response( $events );

			return $response;


		}

		public function get_item($request) {

			global $wpdb;

			$id = $request['id'];

			$event_query = "SELECT wp_posts.ID, post_title AS name, start_time, end_time, date, repeatable FROM wp_posts
					INNER JOIN ( " . $this->get_event_meta() . " ) AS meta ON (wp_posts.ID = meta.post_id)
					WHERE post_type = 'event' AND post_status = 'publish' AND ID = " . $id;

			$event = $wpdb->get_row($event_query);

			$response = rest_ensure_response($event);
			return $response;

		}

		public function get_items($request) {

			global $wpdb;

			$events_query = "SELECT wp_posts.ID, post_title AS name, start_time, end_time, date, repeatable FROM wp_posts
					 INNER JOIN ( " . $this->get_event_meta() . " ) AS meta ON (wp_posts.ID = meta.post_id)
					 WHERE post_type = 'event' AND post_status = 'publish'";

			$events = $wpdb->get_results($events_query);

			foreach ($events as $event) {

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

			$response  = rest_ensure_response( $events );

			return $response;

		}

	}

	function kino_register_events_rest_routes() {

		$controller = new Events_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_events_rest_routes');

?>
