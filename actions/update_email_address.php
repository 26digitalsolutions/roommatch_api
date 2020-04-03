<?php

//
// User needs to be authentificated to perform this action
$user = check_user_authentification(true,true);
//
//

//
// check if post > new_email_address
if (!isset($_POST['new_email_address'])) {
  return_api_error("update_email_address_missing_parameter");
}
//
//

//
// check that new_email_address has right format
validate_data("email_address",$_POST['new_email_address']);
//
//

//
// check that new_email_address is not already used
if (count_db(true,"users","email_address='".addslashes($_POST['new_email_address'])."' AND user_id!='".$user['user_id']."'") != 0) {
  return_api_error("update_email_address_already_used");
}
//
//

//
// update email in DB
$c = connect_db("write");
$r = mysqli_query($c,"UPDATE users SET email_address='".addslashes($_POST['new_email_address'])."' WHERE user_id='".$user['user_id']."'");
if (!$r) {
  return_api_error("database_query_error");
}
else {
  // exit data
  return_api_success("data_successfully_updated");
}
//
//

?>
