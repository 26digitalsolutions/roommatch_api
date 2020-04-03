<?php

//
// User needs to be authentificated to perform this action
$user = check_user_authentification(true,true);
//
//




//
// check if post > new_email_address
if (!isset($_POST['listing_type'])) {
  return_api_error("create_listing_missing_parameter");
}
//
//




//
// check that listing_type is allowed
if (!in_array($_POST['listing_type'], $listing_types_allowed)) {
  return_api_error("create_listing_incorrect_listing_type");
}
//
//




//
// Create listing in DB and return listing_id

$c = connect_db("write");

$r = mysqli_query($c, "INSERT INTO listings (
  listing_type,
  user_id,
  status,
  time_created
) VALUES (
  '".addslashes($_POST['listing_type'])."',
  '".$user['user_id']."',
  'in_moderation',
  '".time()."'
)");
$listing_id = mysqli_insert_id($c);

if (!$r) {
  return_api_error("database_query_error");
}
else {
  return_api_success(
    "create_listing_success",
    array(
      "listing_id" => $listing_id
    )
  );
}

//
//

?>
