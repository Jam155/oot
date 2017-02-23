<?php

	require_once('models/EventModel.php');
	require_once('models/OfferModel.php');

	class Venues_Controller extends WP_REST_Posts_Controller {

		private $model;

		public function __construct() {

			$this->model = new VenueModel();

			parent::__construct('venue');

		}

		public function register_routes() {

			parent::register_routes();

			register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/offer', array(

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

			register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/event', array(

				'args' => array(
					'id' => array(
						'description' => __('The Venues ID'),
						'type' => 'integer',
					),
				),
				array(
					'methods'		=> WP_REST_Server::READABLE,
					'callback'		=> array($this, 'get_events'),
					'permission_callback'	=> array($this, 'get_item_permissions_check'),
					'args'			=> $get_item_args,
				),
				'schema' => array($this, 'get_public_item_schema'),
			));

		}

		public function get_events($request) {

			$eventModel = new EventModel();
			$id = $request['id'];
			$events = $this->model->getEvents($id);
			$eventsArray = array();

			foreach($events as $event) {
				
				$eventsArray[] = $eventModel->getItem($event->ID);

			}
			
			$response = rest_ensure_response( $eventsArray );
			return $response;

		}

		public function get_offers($request) {

			$offerModel = new OfferModel();
			$id = $request['id'];
			$offers = $this->model->getOffers($id);
			$offersArray = array();

			foreach($offers as $offer) {

				$offersArray[] = $offerModel->getItem($offer->ID);

			}
			
			$response  = rest_ensure_response( $offersArray );
			return $response;


		}

		public function get_item($request) {

			$id = $request['id'];
			$venue = $this->model->getItem($id);

			$venue->address["address_line_1"] = $venue->address_line_1;
			$venue->address["address_line_2"] = $venue->address_line_2;
			$venue->address["post_code"] = $venue->post_code;
			$venue->address["city"] = $venue->city;

			$venue->contact['phone'] = $venue->phone;
			$venue->contact['website'] = $venue->website;
			$venue->contact['twitter'] = $venue->twitter;
			$venue->contact['facebook'] = $venue->facebook;

			unset($venue->address_line_1);
			unset($venue->address_line_2);
			unset($venue->post_code);
			unset($venue->city);

			unset($venue->phone);
			unset($venue->website);
			unset($venue->twitter);
			unset($venue->facebook);

			$response = rest_ensure_response($venue);
			return $response;

		}

		public function get_items($request) {

			$venues = $this->model->getItems();

			foreach ($venues as $venue) {

				$venue->address["address_line_1"] = $venue->address_line_1;
				$venue->address["address_line_2"] = $venue->address_line_2;
				$venue->address["post_code"] = $venue->post_code;
				$venue->address["city"] = $venue->city;

				$venue->contact['phone'] = $venue->phone;
				$venue->contact['website'] = $venue->website;
				$venue->contact['twitter'] = $venue->twitter;
				$venue->contact['facebook'] = $venue->facebook;

				unset($venue->address_line_1);
				unset($venue->address_line_2);
				unset($venue->post_code);
				unset($venue->city);

				unset($venue->phone);
				unset($venue->website);
				unset($venue->twitter);
				unset($venue->facebook);


			}

			$response  = rest_ensure_response( $venues );

			return $response;

		}

	}

	require_once('models/VenueModel.php');

	function kino_register_venue_rest_routes() {

		$controller = new Venues_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_venue_rest_routes');

?>
