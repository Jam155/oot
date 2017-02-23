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

		public function get_item($request) {

			$id = $request['id'];
			$item = $this->model->getItem($id);

			$item = $this->restructure($item);

			$response = rest_ensure_response($venue);
			return $response;

		}

		public function get_items($request) {

			$items = $this->model->getItems();

			foreach($items as $item) {

				$item = $this->restructure($item);

			}

			$response = rest_ensure_response($items);
			return $response;

		}

	}

?>
