<?php

//
// User needs to be authentificated to perform this action
$user = check_user_authentification(true,true);
//
//



//
// check if post > relationship_id
if (!isset($_POST['relationship_id'])) {
  return_api_error("delete_relationship_missing_parameter");
}
//
//



//
// validate relationship_id
validate_data("relationship_id",$_POST['relationship_id']);
//
//



$c = connect_db("write");



//
// check if relationship exists and has been created by logged in user
if (count_db(
    false,
    "relationships",
    "relationship_id='".addslashes($_POST['relationship_id'])."' AND sender_id='".$user['user_id']."'"
  ) != 1) {

    return_api_error("delete_relationship_not_found");

}
//
//



//
// delete relationship
$r = mysqli_query(
  $c,
  "DELETE FROM relationships WHERE relationship_id='".addslashes($_POST['relationship_id'])."'"
);
if (!$r) {
  return_api_error("database_query_error");
} else {

  // exit data
  return_api_success(
    "delete_relationship_successful",
    array(
      "relationship_id" => $_POST['relationship_id']
    )
  );

}
//
//

?>
