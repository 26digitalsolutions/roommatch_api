<?php

//
// User needs to be authentificated to perform this action
$user = check_user_authentification(true,true);
//
//



//
// check if post > type, id, offset, limit
if (
  !isset($_POST['type']) OR
  !isset($_POST['id']) OR
  !isset($_POST['offset']) OR
  !isset($_POST['limit'])
) {
  return_api_error("get_relationships_list_missing_parameter");
}
//
//



//
// if relationship_type is set, check that value is allowed
if (isset($_POST['relationship_type'])) {
  if (
    !in_array($_POST['relationship_type'], $relationship_types_allowed['listing']['create']) AND
    !in_array($_POST['relationship_type'], $relationship_types_allowed['user']['create'])
  ) {
    return_api_error("get_relationships_list_relationship_type_not_allowed");
  }
  $relationship_type = addslashes($_POST['relationship_type']);
}
//
//



switch ($_POST['type']) {




  //
  ////
  //////
  case 'user':

    //
    // check that new_email_address has right format
    validate_data("user_id",$_POST['id']);
    //
    //

    //
    // db query
    $q = "(
      (sender_id='".$user['user_id']."' AND recipient_id='".addslashes($_POST['id'])."') OR
      (sender_id='".addslashes($_POST['id'])."' AND recipient_id='".$user['user_id']."')
    ) AND ";

    if (isset($relationship_type)) {
      $q .= "relationship_type='$relationship_type'";
    }
    else {
      $q .= "relationship_type NOT LIKE '%visited_%'";
    }

    $c = connect_db("read");
    $nb_total = count_db(false,"relationships",$q);

    $q .= " ORDER BY relationship_id DESC LIMIT ".$_POST['offset'].",".$_POST['limit'];
    //
    //



    //
    // get relationships list from DB
    $relationships_list = array();

    $r = mysqli_query(
      $c,
      "SELECT relationship_id, relationship_type, sender_id, recipient_id, listing_id, time_created FROM relationships WHERE ".$q
    );
    if (!$r) {
      return_api_error("database_query_error");
    } else {

      if (mysqli_num_rows($r) > 0) {
        while ($d = mysqli_fetch_assoc($r)) {

          array_push($relationships_list,$d);

        }
      }

      // exit data
      return_api_success(
        "get_relationships_list_successful",
        array(
          "nb_total" => $nb_total,
          "limit" => $_POST['limit'],
          "offset" => $_POST['offset'],
          "list" => $relationships_list
        )
      );

    }
    //
    //

  break;
  //////
  ////
  //





  //
  ////
  //////
  case 'listing':

    //
    // check that new_email_address has right format
    validate_data("listing_id",$_POST['id']);
    //
    //


    //
    // db query
    $q = "SELECT relationship_id, relationship_type, sender_id, recipient_id, listing_id, time_created FROM relationships WHERE
    sender_id='".$user['user_id']."' AND
    listing_id='".addslashes($_POST['id'])."' AND ";

    if (isset($relationship_type)) {
      $q .= "relationship_type='$relationship_type'";
    }
    else {
      $q .= "relationship_type NOT LIKE '%visited_%'";
    }

    $c = connect_db("read");
    $nb_total = count_db(false,"relationships",$q);

    $q .= " ORDER BY relationship_id DESC LIMIT ".$_POST['offset'].",".$_POST['limit'];
    //
    //


    //
    // get relationships list from DB
    $relationships_list = array();

    $r = mysqli_query(
      $c,
      $q
    );
    if (!$r) {
      return_api_error("database_query_error");
    } else {

      if (mysqli_num_rows($r) > 0) {
        while ($d = mysqli_fetch_assoc($r)) {

          array_push($relationships_list,$d);

        }
      }

      // exit data
      return_api_success(
        "get_relationships_list_successful",
        array(
          "nb_total" => $nb_total,
          "limit" => $_POST['limit'],
          "offset" => $_POST['offset'],
          "list" => $relationships_list
        )
      );

    }
    //
    //

  break;
  //////
  ////
  //





  default:
    return_api_error("get_relationships_list_missing_parameter");
  break;
}

?>
