<?php

	/** Class for getting tag data from out of the database **/
	class TagModel {
		
		public function get_tags() {
			global $wpdb;
			$tags_query = "SELECT wp_terms.term_id AS id, name, slug FROM wp_terms
						INNER JOIN wp_term_taxonomy ON (wp_terms.term_id = wp_term_taxonomy.term_id)
						WHERE taxonomy = 'post_tag'";
			$tags = $wpdb->get_results($tags_query);
			return $tags;
		}
		
		public function get_tag($id) {
			global $wpdb;
			$tag_query = "SELECT wp_terms.term_id AS id, name, slug FROM wp_terms
						INNER JOIN wp_term_taxonomy ON (wp_terms.term_id = wp_term_taxonomy.term_id)
						WHERE taxonomy = 'post_tag' AND wp_terms.term_id = " . $id;
			$tag = $wpdb->get_results($tag_query);
			return $tag;
		}

	}

?>
