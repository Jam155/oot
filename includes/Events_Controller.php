<?php

	class Events_Controller extends WP_REST_Posts_Controller {

		private $model;

		public function __construct() {

			$this->model = new EventModel();

			parent::__construct('event');

		}

		public function register_routes() {

			parent::register_routes();

		}

		public function get_item($request) {

			$id = $request['id'];
			$event = $this->model->get_item($id);

			$response = rest_ensure_response($event);
			return $response;

		}

		public function get_items($request) {

			$events = $this->model->get_items();

			foreach ($events as $event) {
				
				/* In case of cleanup */
			}

			$response  = rest_ensure_response( $events );

			return $response;

		}

	}

	require_once('models/EventModel.php');

	function kino_register_events_rest_routes() {

		$controller = new Events_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_events_rest_routes');

?>
