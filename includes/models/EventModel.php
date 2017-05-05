<?php

	require_once("KCModel.php");

	/** Class for getting event data from out of the database **/
	class EventModel extends KCModel {


		function __construct() {
			$meta_columns = array(

				"start_time" => "start_time",
				"end_time" => "end_time",
				"date" => "date",
				"ticket_price_string" => "price",
				"repeat_event" => "repeatable",

			);

			$columns = array(

				"wp_posts.ID" => "post_id",
				"wp_posts.post_title" => "name",
				"meta.start_time" => "start_time",
				"meta.end_time" => "end_time",
				"meta.date" => "date",
				"meta.price" => "price",
				"meta.repeatable" => "repeatable",

			);

			$post_type = 'event';

			parent::__construct($meta_columns, $columns, $post_type);

		}

	}

?>
