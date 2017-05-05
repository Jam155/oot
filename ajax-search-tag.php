<?php

$postTags = $_REQUEST['post_tags'];
$venue_name = $_REQUEST['item_id'];

$link = mysqli_connect("localhost", "root", "", "ootdatabase");

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
$term = mysqli_real_escape_string($link, $_REQUEST['term']);
 
if(isset($term)){
    //$sql = "SELECT name FROM wp_terms WHERE name LIKE '%". $term . "%' AND name NOT IN ( 'Uncategorised', '" . implode($postTags, "', '") . "' )";
	$sql = "SELECT term_taxonomy_id, name FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id WHERE name LIKE '%". $term . "%' AND name NOT IN ( 'Uncategorised', '" . implode($postTags, "', '") . "' ) AND taxonomy LIKE 'post_tag'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                echo '<p class="result-tag" data-term-id="' . $row['term_taxonomy_id'] . '" >' . $row['name'] . '</p>';
            }
            mysqli_free_result($result);
        } else{
            //echo '<p class="no-match-tag">' . $term . '</p>';
            echo '<p class="no-match-tag">No tags found. To suggest <span class="highlight">' . $term . '</span> as a new tag, please <a href="mailto:jamescastelow@kinocreative.co.uk?subject=New%20Oot%20Tag%20Suggestion&body=' . $term . '%20on%20'. $venue_name .'">email us</a></p>';
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}

mysqli_close($link);
?>