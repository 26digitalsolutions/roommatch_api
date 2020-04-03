<?php

//
// User needs to be authentificated to perform this action
$user = check_user_authentification(true,true);
//
//



//
// check if post > content exists
if (!isset($_POST['content']) OR $_POST['content'] == "") {
  return_api_error("update_user_profile_missing_parameter");
}
//
//




//
// check that post>content is a valid json object
$content = json_decode($_POST['content'], true);
validate_data("json", $_POST['content']);
//
//




//
//
// check that parameters in post>content are allowed to be changed
foreach($content as $key=>$value)
{
  if (!in_array($key, $user_allowed_parameters)) {
    return_api_error("content_parameter_not_allowed");
  }
}
//
//




//
////
////// more precise optional check parameter by parameter

//
// age
if (
  isset($content['age']) AND
  (
    !ctype_digit($content['age']) OR
    $content['age'] < 13 OR
    strlen($content['age']) > 3
  )
) {
  return_api_error("update_user_profile_content_age_incorrect");
}
//
//

//////
////
//




//
////
////// Update DB
$q = "";
foreach($content as $key=>$value)
{

  $q .= "DELETE FROM users_parameters WHERE user_id='".$user['user_id']."' AND parameter_key='".addslashes($key)."';";
  $q .= "INSERT INTO users_parameters (
    user_id,
    parameter_key,
    parameter_value
  ) VALUES (
    '".$user['user_id']."',
    '".addslashes($key)."',
    '".addslashes(serialize_data($key,$value))."'
  );";

}

// insert in 'elastic_queue' to be treated and added to noSQL DB
$q .= "INSERT INTO elastic_queue (type, item_id) VALUES ('user', ".$user['user_id'].")";

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
