<?php

//
// check if fields are not missing
if (
  !isset($_POST['email_address']) OR
  !isset($_POST['password']) OR
  !isset($_POST['country'])
) {
  return_api_error("create_account_fields_missing");
}
//
//

//
// check if email address has correct format
validate_data("email_address", $_POST['email_address']);
//
//

//
// check if password has correct format
validate_data("password", $_POST['password']);
//
//

//
// countries allowed
if (!isset($countries_opened[$_POST['country']])) {
  return_api_error("country_not_allowed");
}
//
//

//
// check if email address doesnt already exist in DB
if (count_db(true,"users","email_address='".addslashes($_POST['email_address'])."'") != 0) {
  return_api_error("create_account_email_already_exists");
}
//
//

//
// All good > create new user
$c = connect_db("write");
$token = generate_token();

$r = mysqli_query($c,"INSERT INTO users (
  time_created,
  token,
  email_address,
  password,
  country
) VALUES (
  '".time()."',
  '$token',
  '".addslashes($_POST['email_address'])."',
  '".addslashes(encrypt_password($_POST['password']))."',
  '".addslashes($_POST['country'])."'
)");

if (!$r) {
  return_api_error("database_query_error");
}
else {

  $user_id = mysqli_insert_id($c);

  return_api_success(
    "create_account_created_sucessfully",
    array(
      "user_id" => $user_id,
      "country" => $_POST['country'],
      "token" => $token
    )
  );

}
//
//

?>
