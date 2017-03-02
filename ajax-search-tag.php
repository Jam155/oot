<?php

$postTags = $_REQUEST['post_tags'];

$link = mysqli_connect("localhost", "root", "", "ootdatabase");

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
$term = mysqli_real_escape_string($link, $_REQUEST['term']);
 
if(isset($term)){
    $sql = "SELECT name FROM wp_terms WHERE name LIKE '%". $term . "%' AND name NOT IN ( 'Uncategorised', '" . implode($postTags, "', '") . "' )";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                echo '<p class="result-tag">' . $row['name'] . '</p>';
            }
            mysqli_free_result($result);
        } else{
            echo '<p class="no-match-tag">' . $term . '</p>';
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}

mysqli_close($link);
?>