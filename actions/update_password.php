<?php

//
// User needs to be authentificated to perform this action
$user = check_user_authentification(true,true);
//
//

//
// check if post > old_password, new_password exist
if (!isset($_POST['current_password']) OR !isset($_POST['new_password'])) {
  return_api_error("update_password_missing_parameter");
}
//
//

//
// check if password has correct format
validate_data("password", $_POST['current_password']);
validate_data("password", $_POST['new_password']);
//
//

//
// check if current_password is correct
if (count_db(true,"users","user_id='".$user['user_id']."' AND password='".addslashes(encrypt_password($_POST['current_password']))."'") != 1) {
  return_api_error("update_password_incorrect_current_password");
}
//
//

//
// update password in DB
$c = connect_db("write");
$r = mysqli_query($c,"UPDATE users SET password='".addslashes(encrypt_password($_POST['new_password']))."' WHERE user_id='".$user['user_id']."'");
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
