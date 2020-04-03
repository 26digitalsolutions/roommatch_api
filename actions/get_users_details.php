<?php

//
// check if post > content exists
if (!isset($_POST['content']) OR $_POST['content'] == "") {
  return_api_error("get_users_details_missing_parameter");
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
////
//////
$q = "";
for ($i=0; $i < count($content); $i++) {

  // check that value has correct listing_id format
  validate_data("user_id", $content[$i]);

  // build db query
  $q .= "user_id='".addslashes($content[$i])."' OR ";

}
$q = rtrim($q," OR ");
//////
////
//




//
////
////// Execute request
if ($q != "") {

  $output = array();

  $c = connect_db("read");
  $r = mysqli_query($c,"SELECT * FROM users WHERE $q");
  if (!$r) {
    return_api_error("database_query_error");
  }
  else {
    if (mysqli_num_rows($r) <= 0) {
      return_api_error("get_users_details_users_not_found");
    }
    else {

      while ($d = mysqli_fetch_assoc($r)) {

        $user_id = addslashes($d['user_id']);

        // user parameters
        $user_parameters = array();
        foreach ($d as $key => $value) {
          $user_parameters[$key] = $value;
        }

        // get parameters associated to user
        $rp = mysqli_query($c,"SELECT parameter_key, parameter_value FROM users_parameters WHERE user_id='".$user_id."'");
        if (!$rp) {
          return_api_error("database_query_error");
        }
        else {
          while ($dp = mysqli_fetch_assoc($rp)) {

            $user_parameters[$dp['parameter_key']] = unserialize_data($dp['parameter_key'],$dp['parameter_value']);

          }
        }

        // hide private data in case user logged in is different from user requested
        $user_parameters = remove_sensitive_data($user_parameters);
        $user = check_user_authentification(false,false);
        if (
          !isset($user['user_id']) OR
          $user['user_id'] != $user_id
        ) {
          $user_parameters = remove_sensitive_data($user_parameters,true); 
        }

        // add to output
        $output[$user_id] = $user_parameters;

      }

      // exit result
      return_api_success(
        "get_users_details_success",
        $output
      );

    }
  }

}
//////
////
//


?>
