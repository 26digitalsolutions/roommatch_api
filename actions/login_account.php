<?php

//
// check if fields are not missing
if (
  !isset($_POST['email_address']) OR
  !isset($_POST['password'])
) {
  return_api_error("login_account_fields_missing");
}
//
//

//
// check if email address has correct format...
validate_data("email_address", $_POST['email_address']);
//
//

//
// check if an account exists with those credentials
$c = connect_db("read");
$r = mysqli_query($c,"SELECT * FROM users WHERE email_address='".addslashes($_POST['email_address'])."' AND password='".addslashes(encrypt_password($_POST['password']))."' AND account_status='active'");
if ($r) {
  if (mysqli_num_rows($r) == 1) {
    $d = mysqli_fetch_assoc($r);

    // create user parameters object & hide sensitive data
    $user_parameters = array();
    foreach ($d as $key => $value) {
      $user_parameters[$key] = $value;
    }
    $user_parameters = remove_sensitive_data($user_parameters);

    // get parameters associated to user
    $r = mysqli_query($c,"SELECT parameter_key, parameter_value FROM users_parameters WHERE user_id='".addslashes($d['user_id'])."'");
    if (!$r) {
      return_api_error("database_query_error");
    }
    else {
      while ($d = mysqli_fetch_assoc($r)) {
        $user_parameters[$d['parameter_key']] = $d['parameter_value'];
      }
    }

    // exit data
    return_api_success(
      "login_account_successful",
      $user_parameters
    );

  } else {
    return_api_error("login_account_account_not_found");
  }
} else {
  return_api_error("database_query_error");
}
//
//

?>
