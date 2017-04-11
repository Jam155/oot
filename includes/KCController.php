<?php

	class KCController extends WP_REST_Posts_Controller {

		protected $model;
		protected $type;

		function __construct($model, $type) {
			
			$this->model = $model;
			parent::__construct($type);

		}

		public function register_routes() {
			
			parent::register_routes();

		}

		/** Function to restructure items, mainly used in child classes **/
		protected function restructure($item) {

			return $item;

		}

		protected function hav($delta) {

			return (1 - cos($delta)) / 2;

		}

		public function get_item($request) {

			$id = $request['id'];

			$item = $this->model->getItem($id);
			$item = $this->restructure($item);

			$response = rest_ensure_response($item);
			return $response;

		}

		public function get_items($request) {

			$items = $this->model->getItems();

			$longitude = $request->get_param("longitude");
			$latitude = $request->get_param("latitude");

			foreach($items as $item) {

				$item = $this->restructure($item);

			}

			$response = rest_ensure_response($items);
			return $response;

		}

	}

?>
