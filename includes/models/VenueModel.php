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

	}

?>
