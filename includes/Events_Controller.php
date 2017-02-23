<?php

	require_once('models/EventModel.php');
	require_once('KCController.php');

	class Events_Controller extends KCController {

		public function __construct() {

			parent::__construct(new EventModel(), 'event');

		}

	}

	function kino_register_events_rest_routes() {

		$controller = new Events_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_events_rest_routes');

?>
