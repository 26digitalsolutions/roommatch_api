<?php

//
// User needs to be authentificated to perform this action
$user = check_user_authentification(true,true);
//
//



//
// check if post > content, listing_id exist
if (
  !isset($_POST['content'])
  !isset($_POST['listing_id'])
) {
  return_api_error("update_listing_parameters_missing_parameter");
}
//
//



//
//
//
validate_data("listing_id", $_POST['listing_id']);
$listing_id = addslashes($_POST['listing_id']);
//
//
//



//
// check that post>content is a valid json object
$content = json_decode($_POST['content'], true);
validate_data("json", $_POST['content']);
//
//



//
// check that listing exists and belongs to logged in user
if (count_db(true,"listings","listing_id='$listing_id' AND user_id='".$user['user_id']."' AND status!='deleted'") != 1) {
  return_api_error("update_listing_parameters_listing_not_found");
}
//
//



//
//
// check that parameters in post>content are allowed to be changed
foreach($content as $key=>$value)
{
  if (!in_array($key, $listing_allowed_parameters)) {
    return_api_error("content_parameter_not_allowed");
  }
}
//
//



//
////
////// more precise optional check parameter by parameter



//////
////
//



//
////
////// Update DB
$q = "";
foreach($content as $key=>$value)
{

  $q .= "DELETE FROM listings_parameters WHERE listing_id='$listing_id' AND parameter_key='".addslashes($key)."';";
  $q .= "INSERT INTO listings_parameters (
    listing_id,
    parameter_key,
    parameter_value
  ) VALUES (
    '$listing_id',
    '".addslashes($key)."',
    '".addslashes(serialize_data($key,$value))."'
  );";

}

// insert in 'elastic_queue' to be treated and added to noSQL DB
$q .= "INSERT INTO elastic_queue (type, item_id) VALUES ('listing', '$listing_id')";

$c = connect_db("write");
$r = mysqli_multi_query($c,$q);

if (!$r) {
  return_api_error("database_query_error");
}
else {

  // exit
  return_api_success(
    "data_successfully_updated",
    $content
  );

}
//////
////
//

?>
