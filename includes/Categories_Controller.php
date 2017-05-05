<?php

	require_once('models/CategoryModel.php');

	class Categories_Controller extends WP_REST_Terms_Controller {
	
		private $model;
	
		public function __construct() {

			$this->model = new CategoryModel();
			parent::__construct('category');

		}
		
		public function register_routes() {

			parent::register_routes();
			
			register_rest_route( $this->namespace, '/category', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_categories' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'			  => WP_REST_Server::CREATABLE,
					'callback'			  => array( $this, 'create_item'),
					'permission_callback' => array($this, 'get_item_permissions_check'),
					'args'				  => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema'),
			));
			
			register_rest_route( $this->namespace, '/category/(?P<id>[\d]+)', array(
				'args' => array(
					'id' => array(
						'description' => __('The category ID required'),
						'type' => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_category' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			));
		}
		
		public function get_categories($request) {
			$categories = $this->model->get_categories();
			$response = rest_ensure_response($categories);
			return $response;
		}
		
		public function get_category($request) {
			$id = $request->get_param('id');
			$category = $this->model->get_category($id);
			$response = rest_ensure_response($category);
			return $response;
		}
	}
	
	function kino_register_categories_rest_routes() {

		$controller = new Categories_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_categories_rest_routes');

?>
