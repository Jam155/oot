<?php

	require_once('models/TagModel.php');

	class Tags_Controller extends WP_REST_Taxonomies_Controller {
	
		private $model;
	
		public function __construct() {

			$this->model = new TagModel();
			parent::__construct('tag');

		}
		
		public function register_routes() {

			parent::register_routes();
			
			register_rest_route( $this->namespace, '/tag', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_tags' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			));
			
			register_rest_route( $this->namespace, '/tag/(?P<id>[\d]+)', array(
				'args' => array(
					'id' => array(
						'description' => __('The tag ID required'),
						'type' => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_tag' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			));
		}
		
		public function get_tags($request) {
			$tags = $this->model->get_tags();
			$response = rest_ensure_response($tags);
			return $response;
		}
		
		public function get_tag($request) {
			$id = $request->get_param('id');
			$tag = $this->model->get_tag($id);
			$response = rest_ensure_response($tag);
			return $response;
		}
	}
	
	function kino_register_tags_rest_routes() {

		$controller = new Tags_Controller();
		$controller->register_routes();

	}

	add_action('rest_api_init', 'kino_register_tags_rest_routes');

?>
