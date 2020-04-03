<?php

//
// User needs to be authentificated to perform this action
$user = check_user_authentification(true,true);
//
//



//
// check if post > relationship_type, recipient_id
if (
  !isset($_POST['relationship_type']) OR
  !isset($_POST['recipient_id'])
) {
  return_api_error("create_relationship_user_missing_parameter");
}
//
//



//
// validate recipient_id
validate_data("user_id",$_POST['recipient_id']);
//
//



//
// user can not create relationship with himself
if ($_POST['recipient_id'] == $user['user_id']) {
  return_api_error("create_relationship_user_incorrect_recipient_id");
}
//
//



//
// check that relationship_type is allowed
if (!in_array($_POST['relationship_type'], $relationship_types_allowed['user']['create'])) {
  return_api_error("create_relationship_user_incorrect_relationship_type");
}
//
//



//
////
//////

$c = connect_db("write");

//
// check if recipient_id exists
if (count_db(false,"users","user_id='".addslashes($_POST['recipient_id'])."' AND account_status='active'") != 1) {
  return_api_error("create_relationship_user_recipient_not_found");
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
    (sender_id='".$user['user_id']."' AND recipient_id='".addslashes($_POST['recipient_id'])."') OR
    (sender_id='".addslashes($_POST['recipient_id'])."' AND recipient_id='".$user['user_id']."')
  )"
  ) != 0) {
  return_api_error("create_relationship_user_blocked_user");
}
//
//



//
// check if relationship exists already, if so STOP
// skip this check if relationship_type > visited_user
if ($_POST['relationship_type'] != "visited_user") {

  if (count_db(
    false,
    "relationships",
    "sender_id='".$user['user_id']."' AND recipient_id='".addslashes($_POST['recipient_id'])."'"
    ) != 0) {
    return_api_error("create_relationship_user_already_exists");
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
    time_created
  ) VALUES (
    '".addslashes($_POST['relationship_type'])."',
    '".$user['user_id']."',
    '".addslashes($_POST['recipient_id'])."',
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
      "recipient_id" => $_POST['recipient_id']
    )
  );

}
//
//

//////
////
//


?>
