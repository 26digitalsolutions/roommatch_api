<?php

//
// User needs to be authentificated to perform this action
$user = check_user_authentification(true,true);
//
//



//
// check if post > relationship_type, listing_id
if (
  !isset($_POST['relationship_type']) OR
  !isset($_POST['listing_id'])
) {
  return_api_error("create_relationship_listing_missing_parameter");
}
//
//



//
// validate listing_id
validate_data("listing_id",$_POST['listing_id']);
//
//



//
// check that relationship_type is allowed
if (!in_array($_POST['relationship_type'], $relationship_types_allowed['listing']['create'])) {
  return_api_error("create_relationship_listing_incorrect_relationship_type");
}
//
//



//
////
//////

$c = connect_db("write");

//
// check if listing_id exists
// get user_id of the listing's owner
$r = mysqli_query(
  $c,
  "SELECT user_id FROM listings WHERE listing_id='".addslashes($_POST['listing_id'])."' AND status='active' AND user_id!='".$user['user_id']."'"
);
if (!$r) {
  return_api_error("database_query_error");
} else {
  if (mysqli_num_rows($r) == 1) {

    $d = mysqli_fetch_assoc($r);
    $listing_user_id = addslashes($d['user_id']);

  } else {
    return_api_error("create_relationship_listing_not_found");
  }
}
//
//



//
// check if users have not blocked each others
if (count_db(
  false,
  "relationships",
  "relationship_type='blocked_user' AND
  (
    (sender_id='".$user['user_id']."' AND listing_id='".$listing_user_id."') OR
    (sender_id='".$listing_user_id."' AND listing_id='".$user['user_id']."')
  )"
  ) != 0) {
  return_api_error("create_relationship_listing_blocked_user");
}
//
//



//
// check if relationship exists already, if so STOP
// skip this check if relationship_type > visited_listing
if ($_POST['relationship_type'] != "visited_listing") {

  if (count_db(
    false,
    "relationships",
    "sender_id='".$user['user_id']."' AND listing_id='".addslashes($_POST['listing_id'])."'"
    ) != 0) {
    return_api_error("create_relationship_listing_already_exists");
  }

}
//
//



//
// create relationship
$r = mysqli_query(
  $c,
  "INSERT INTO relationships (
    relationship_type,
    sender_id,
    recipient_id,
    listing_id,
    time_created
  ) VALUES (
    '".addslashes($_POST['relationship_type'])."',
    '".$user['user_id']."',
    '".$listing_user_id."',
    '".addslashes($_POST['listing_id'])."',
    '".time()."'
  )"
);
if (!$r) {
  return_api_error("database_query_error");
} else {

  $relationship_id = mysqli_insert_id($c);

  // exit data
  return_api_success(
    "create_relationship_successful",
    array(
      "relationship_id" => $relationship_id,
      "listing_id" => $_POST['listing_id']
    )
  );

}
//
//

//////
////
//


?>
