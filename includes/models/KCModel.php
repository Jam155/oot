<?php

	class KCModel {

		protected $meta_columns = array();
		protected $columns = array();
		protected $post_type = '';

		function __construct($meta_columns, $columns, $post_type) {

			$this->meta_columns = $meta_columns;
			$this->columns = $columns;
			$this->post_type = $post_type;

		}

		protected function getMeta() {

			$meta_query = "";
			$select_statement = "SELECT wp_posts.ID as post_id,";

			foreach($this->meta_columns AS $meta_key => $meta_value) {

				$select_statement .= $meta_value . ",";
				$meta_query .= " INNER JOIN (SELECT post_id, meta_value AS " . $meta_value . " FROM wp_postmeta WHERE meta_key = '" . $meta_key . "') AS " . $meta_value . " ON (wp_posts.ID = " . $meta_value . ".post_id)";

			}

			$select_statement = rtrim($select_statement, ',');
			$select_statement .= " FROM wp_posts";

			$meta_query = "INNER JOIN ( " . $select_statement . $meta_query . " ) AS meta ON (wp_posts.ID = meta.post_id)";

			return $meta_query;

		}

		protected function getSelect() {

			$select = "SELECT ";

                        foreach ($this->columns AS $column => $name) {

                                $select .= $column . " AS " . $name . ",";

                        }

                        $select = rtrim($select, ',');
                        $select .= " FROM wp_posts ";
                        return $select;

		}

		public function getItems() {

			global $wpdb;

			$items_query = 	$this->getSelect() .
					$this->getMeta() .
					" WHERE post_type = '" . $this->post_type . "' AND post_status = 'publish'";

			$items = $wpdb->get_results($items_query);
			return $items;

		}

		public function getItem($item_id) {
			
			global $wpdb;

			$item_query = $this->getSelect() .
					$this->getMeta() .
					" WHERE post_type = '" . $this->post_type . "' AND post_status = 'publish' AND ID = " . $item_id;

			$item = $wpdb->get_row($item_query);
			return $item;
		}

	}

?>
