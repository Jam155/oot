<?php

	/** Class for getting event data from out of the database **/
	class EventModel {

		private $meta_columns = array(

			"start_time" => "start_time",
			"end_time" => "end_time",
			"date" => "date",
			"ticket_price" => "price",
			"repeat_event" => "repeatable",

		);
		private $columns = array(

			"wp_posts.ID" => "post_id",
			"wp_posts.post_title" => "name",
			"meta.start_time" => "start_time",
			"meta.end_time" => "end_time",
			"meta.date" => "date",
			"meta.price" => "price",
			"meta.repeatable" => "repeatable",

		);

		private function get_meta() {

			$meta_query = "";
			$select_statement = "SELECT wp_posts.ID as post_id,";

			foreach ($this->meta_columns AS $meta_key => $meta_value) {

				$select_statement .= $meta_value . ",";
				$meta_query .= " INNER JOIN (SELECT post_id, meta_value AS " . $meta_value . " FROM wp_postmeta WHERE meta_key = '" . $meta_key . "') AS " . $meta_value . " ON (wp_posts.ID = " . $meta_value . ".post_id)";

			}

			$select_statement = rtrim($select_statement, ',');
			$select_statement .= " FROM wp_posts";

			$meta_query = "INNER JOIN ( " . $select_statement . $meta_query . " ) AS meta ON (wp_posts.ID = meta.post_id)";

			return $meta_query;
			
		}

		private function get_select() {

			$select = "SELECT ";

			foreach ($this->columns AS $column => $name) {

				$select .= $column . " AS " . $name . ",";

			}

			$select = rtrim($select, ',');
			$select .= " FROM wp_posts ";
			return $select;

		}

		public function get_items() {

			global $wpdb;

			$items_query =	$this->get_select() .
					$this->get_meta() .
					" WHERE post_type = 'event' AND post_status = 'publish'";
			
			$items = $wpdb->get_results($items_query);
			return $item;


		}

		public function get_item($item_id) {

			global $wpdb;

			$item_query = 	$this->get_select() .
					$this->get_meta() .
					" WHERE post_type = 'event' AND post_status = 'publish' AND ID = " . $item_id;

			$item = $wpdb->get_row($item_query);
			return $item;

		}

	}

?>
