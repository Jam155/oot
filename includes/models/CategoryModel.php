<?php

	/** Class for getting category data from out of the database **/
	class CategoryModel {
		
		public function get_categories() {
			global $wpdb;
			$categories_query = "SELECT wp_terms.term_id AS id, name, slug FROM wp_terms
						INNER JOIN wp_term_taxonomy ON (wp_terms.term_id = wp_term_taxonomy.term_id)
						WHERE taxonomy = 'category'";
			$categories = $wpdb->get_results($categories_query);
			return $categories;
		}
		
		public function get_category($id) {
			global $wpdb;
			$category_query = "SELECT wp_terms.term_id AS id, name, slug FROM wp_terms
						INNER JOIN wp_term_taxonomy ON (wp_terms.term_id = wp_term_taxonomy.term_id)
						WHERE taxonomy = 'category' AND wp_terms.term_id = " . $id;

			$category = $wpdb->get_results($category_query);
			return $category;
		}
		
	}

?>
