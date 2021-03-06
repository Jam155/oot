<?php

	require_once("KCModel.php");

	class OfferModel extends KCModel {

		function __construct() {

			$meta_columns = array(

				"start_time" => "start_time",
				"end_time" => "end_time",
				"date" => "date",
				"maximum_redeemable" => "redeemable",

			);

			$columns = array(

				"wp_posts.ID" => "post_id",
				"wp_posts.post_title" => "name",
				"meta.start_time" => "start_time",
				"meta.end_time" => "end_time",
				"meta.date" => "date",
				"meta.redeemable" => "redeemable",

			);

			$post_type = 'offer';

			parent::__construct($meta_columns, $columns, $post_type);

		}

	}
