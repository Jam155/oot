<?php

	require_once('KCController.php');

	class Offers_Controller extends KCController {

		public function __construct() {

			parent::__construct(new OfferModel(), 'offer');

		}

		public function register_routes() {

			parent::register_routes();

			register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/redeem', array(

				'args' => array(

					'id' => array(

						'description' => __('The Offer ID'),
						'type' => 'integer',

					),

				),
				array(

					'methods'		=> WP_REST_Server::READABLE,
					'callback'		=> array($this, 'redeem_offer'),
					'permission_callback'	=> array($this, 'get_item_permissions_check'),
					'args'			=> $get_item_args,
				),

			));

		}

		public function redeem_offer($request) {

			$response = $this->model->redeem($request['id']);
			$response = rest_ensure_response($response);
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
