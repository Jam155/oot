<?php

	require_once("KCModel.php");

	class VenueModel extends KCModel {

		function __construct() {

			$meta_columns = array(

				"address_1" => "address_line_1",
				"address_2" => "address_line_2",
				"post_code" => "post_code",
				"city" => "city",
				"phone" => "phone",
				"website" => "website",
				"twitter" => "twitter",
				"facebook" => "facebook",

			);

			$columns = array(

				"wp_posts.ID" => "post_id",
				"wp_posts.post_title" => "name",
				"meta.address_line_1" => "address_line_1",
				"meta.address_line_2" => "address_line_2",
				"meta.post_code" => "post_code",
				"meta.city" => "city",
				"meta.phone" => "phone",
				"meta.website" => "website",
				"meta.twitter" => "twitter",
				"meta.facebook" => "facebook",

			);

			$post_type = 'venue';

			parent::__construct($meta_columns, $columns, $post_type);

		}

		public function getCategories($id) {

			global $wpdb;

			$categories_query = "SELECT wp_terms.term_id, name, slug FROM wp_terms
						INNER JOIN wp_term_taxonomy ON (wp_terms.term_id = wp_term_taxonomy.term_id)
						INNER JOIN wp_term_relationships ON (wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id)
						WHERE taxonomy = 'category' AND object_id = " . $id;

			$categories = $wpdb->get_results($categories_query);
			return $categories;

		}

		//Get any available events for this venue
		public function getEvents($id) {

			global $wpdb;

			$events_query = "SELECT ID FROM wp_posts
					 INNER JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id)
					 WHERE wp_postmeta.meta_key = 'venue' AND wp_postmeta.meta_value = " . $id . " AND wp_posts.post_type = 'event'";

			$events = $wpdb->get_results($events_query);
			return $events;

		}

		//Get any available offers for this venue
		public function getOffers($id) {

			global $wpdb;

			$offers_query = "SELECT ID FROM wp_posts
					 INNER JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id)
					 WHERE wp_postmeta.meta_key = 'venue' AND wp_postmeta.meta_value = " . $id . " AND wp_posts.post_type = 'offer'";
			
			$offers = $wpdb->get_results($offers_query);
			return $offers;

		}

		public function getTimes($id) {

			$times = get_field("opening_hours", $id);

			return $times;

		}

		public function getItem($id) {

			$item = parent::getItem($id);

			$item->categories = $this->getCategories($item->post_id);
			$item->times = $this->getTimes($item->post_id);

			return $item;

		}

		public function getItems() {

			$items = parent::getItems();

			foreach($items as $item) {

				$item->categories = $this->getCategories($item->post_id);

			}

			return $items;

		}

	}

?>
