<?php

	require_once('KCController.php');
	require_once('models/EventModel.php');
	require_once('models/OfferModel.php');

	class Venues_Controller extends KCController {

		public function __construct() {

			parent::__construct(new VenueModel(), 'venue');

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

			register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/categories', array(

				'args' => array(

					'id' => array(

						'description' => __('The Venues ID'),
						'type' => 'integer',

					),

				),
				array(
					'methods' => WP_REST_Server::READABLE,
					'callback' => array($this, 'get_categories'),
					'permission_callback' => array($this, 'get_item_permissions_check'),
					'args' => $get_item_args,
				),
				'schema' => array($this, 'get_public_item_schema'),


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

		public function get_categories($request) {

			$id = $request['id'];
			$categories = $this->model->getCategories($id);

			$response = rest_ensure_response($categories);
			return $response;

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

		protected function restructure($item) {
			
			$item->address["address_line_1"] = $item->address_line_1;
			$item->address["address_line_2"] = $item->address_line_2;
			$item->address["post_code"] = $item->post_code;
			$item->address["city"] = $item->city;

			$item->contact['phone'] = $item->phone;
			$item->contact['website'] = $item->website;
			$item->contact['twitter'] = $item->facebook;
			$item->contact['facebook'] = $item->twitter;

			unset($item->address_line_1);
			unset($item->address_line_2);
			unset($item->post_code);
			unset($item->city);
			unset($item->phone);
			unset($item->website);
			unset($item->facebook);
			unset($item->twitter);

			return $item;

		}
		
	}

	require_once('models/VenueModel.php');

	function kino_register_venue_rest_routes() {

		$controller = new Venues_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_venue_rest_routes');

?>
