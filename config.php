<?php

//
// dev
header('Access-Control-Allow-Origin: *');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// dev
//

require("config/general_settings.php");
require("config/errors_list.php");
require("config/success_list.php");


//
////
////// DB access
function connect_db($type) {

  switch ($type) {

    // write DB
    case 'write':
      $sql_server = "localhost";
      $sql_user = "root";
      $sql_passwd = "root";
      $sql_db = "roomz";
    break;

    // read DB
    case 'read':
      $sql_server = "localhost";
      $sql_user = "root";
      $sql_passwd = "root";
      $sql_db = "roomz";
    break;

    default:
      return_api_error("database_access_error");
    break;

  }

  $connect = mysqli_connect($sql_server, $sql_user, $sql_passwd, $sql_db);
  if (!$connect OR mysqli_connect_errno()) {
    return_api_error("database_access_error");
  }
  else {
    return $connect;
  }

}
//////
////
//



//
////
////// count from DB
function count_db($connect_to_db,$table,$where="")
{

  if ($connect_to_db == true) {
    $c = connect_db("read");
  }
  else {
    global $c;
  }

  $req = mysqli_query($c,"Select count(*) from $table");
  if ($where != "")
  {
    $req = mysqli_query($c,"Select count(*) from $table where $where");
  }

  if ($connect_to_db == true) {
    mysqli_close($c);
  }

  if (!$req) {
    return_api_error("database_query_error");
  }
  else {
    $nb = mysqli_fetch_array($req);
    return $nb[0];
  }

}
//////
////
//




//
////
////// API Errors

function return_api_error($error_code) {
  global $api_errors;
  // close db connection if opened
  global $c;
  if (isset($c)) {
    mysqli_close($c);
    unset($c);
  }
  // exit
  http_response_code(400);
  exit(json_encode(array("status" => "error", "code" => $error_code, "message" => $api_errors[$error_code])));
}

//////
////
//


//
////
////// API success

function return_api_success($success_code, $data="") {
  global $api_success;
  // close db connection if opened
  global $c;
  if (isset($c)) {
    mysqli_close($c);
    unset($c);
  }
  // exit
  $data_to_return = array(
    "status" => "success",
    "code" => $success_code,
    "message" => $api_success[$success_code],
    "content" => $data
  );
  http_response_code(200);
  exit(json_encode($data_to_return));
}

//////
////
//


//
////
////// Check if user is authentificated
function check_user_authentification($connect_to_db,$return_error) {
  if (
    !isset(getallheaders()['user_id']) OR getallheaders()['user_id'] == "" OR
    !isset(getallheaders()['token']) OR getallheaders()['token'] == "" OR count_db($connect_to_db,"users","user_id='".getallheaders()['user_id']."' AND token='".getallheaders()['token']."' AND account_status='active'") != 1
  ) {
    if ($return_error == true) {
      return_api_error("user_authentification_failed");
    }
  } else {
    return array(
      "user_id" => addslashes(getallheaders()['user_id']),
      "token" => addslashes(getallheaders()['token'])
    );
  }
}
//////
////
//


//
////
////// validate data (format, type...)
function validate_data($type, $to_check) {
  switch ($type) {

    //
    // email address
    case 'email_address':

      if (!filter_var($to_check, FILTER_VALIDATE_EMAIL)) {
        return_api_error("email_wrong_format");
      }

    break;
    //
    //

    //
    // password format
    case 'password':

      if (strlen($to_check) < 5 OR strlen($to_check) > 50) {
        return_api_error("password_wrong_format");
      }

    break;
    //
    //

    //
    // check if valid json
    case 'json':

      if (json_decode($to_check, true) == null) {
        return_api_error("content_not_json");
      }

    break;
    //
    //

    //
    // listing id format
    case 'listing_id':

      if (!ctype_digit($to_check)) {
        return_api_error("listing_id_incorrect_format");
      }

    break;
    //
    //

    //
    // user id format
    case 'user_id':

      if (!ctype_digit($to_check)) {
        return_api_error("user_id_incorrect_format");
      }

    break;
    //
    //

    //
    // relationship_id format
    case 'relationship_id':

      if (!ctype_digit($to_check)) {
        return_api_error("relationship_id_incorrect_format");
      }

    break;
    //
    //

  }
}
//////
////
//



//
////
////// serialize/unserialize data (if serialized), objects are serialized() before stored to db
function serialize_data($key,$value) {

  if (strpos($key, "serialized_") !== false) {
    $value = serialize($value);
  }

  return $value;
}

function unserialize_data($key,$value) {

  if (strpos($key, "serialized_") !== false) {
    $value = unserialize($value);
  }

  return $value;
}
//////
////
//




//
////
////// Remove sensitive data from objects
function remove_sensitive_data($object,$advanced=false) {

  // password
  if (isset($object['password'])) {
    unset($object['password']);
  }

  //
  // advanced, ex: when user is logged in, and request data regarding another user
  if ($advanced == true) {

    // token
    if (isset($object['token'])) {
      unset($object['token']);
    }

    // email address
    if (isset($object['email_address'])) {
      unset($object['email_address']);
    }

    // firebase_identification_token
    if (isset($object['firebase_identification_token'])) {
      unset($object['firebase_identification_token']);
    }

  }
  //
  //

  return $object;
}
//////
////
//




//
////
////// query noSQL db
function nosql_query($type, $id, $data) {
  echo "<pre>";
  echo json_encode($data);
  echo "<pre>";
}
//////
////
//


//
////
////// encrypt password
function encrypt_password($password) {
  return md5($password);
}
//////
////
//


//
////
////// generate token
function generate_token(){
  return md5(mt_rand(1111,9999).mt_rand(111111,999999).mt_rand(1111,9999));
}
//////
////
//

?>
