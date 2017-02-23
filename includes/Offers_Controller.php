<?php

	require_once('KCController.php');

	class Offers_Controller extends KCController {

		public function __construct() {

			parent::__construct(new OfferModel(), 'offer');

		}

	}

	require_once('models/OfferModel.php');

	function kino_register_offers_rest_routes() {

		$controller = new Offers_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_offers_rest_routes');

?>
