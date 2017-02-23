<?php

	class Offers_Controller extends WP_REST_Posts_Controller {

		private $model;

		public function __construct() {

			$this->model = new OfferModel();

			parent::__construct('offer');

		}

		public function register_routes() {

			parent::register_routes();

		}

		public function get_item($request) {

			$id = $request['id'];
			$offer = $this->model->getItem($id);

			$response = rest_ensure_response($offer);
			return $response;

		}

		public function get_items($request) {

			$offers = $this->model->getItems();

			foreach ($offers as $offer) {
				
				/** In case of clean up **/

			}

			$response  = rest_ensure_response( $offers );

			return $response;

		}

	}

	require_once('models/OfferModel.php');

	function kino_register_offers_rest_routes() {

		$controller = new Offers_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_offers_rest_routes');

?>
