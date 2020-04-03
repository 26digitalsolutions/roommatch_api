<?php
require("config.php");

if (isset($_GET['action']) and $_GET['action'] != "") {

  switch ($_GET['action']) {

    //
    //
    // create_account
    case 'create_account':
      include("actions/create_account.php");
    break;
    //
    //
    //

    //
    //
    // login to account
    case 'login_account':
      include("actions/login_account.php");
    break;
    //
    //
    //

    //
    //
    // update user profile picture
    case 'update_user_profile':
      include("actions/update_user_profile.php");
    break;
    //
    //
    //

    //
    //
    // get users details
    case 'get_users_details':
      include("actions/get_users_details.php");
    break;
    //
    //
    //

    //
    //
    // update email address
    case 'update_email_address':
      include("actions/update_email_address.php");
    break;
    //
    //
    //

    //
    //
    // update password
    case 'update_password':
      include("actions/update_password.php");
    break;
    //
    //
    //

    //
    //
    // create a listing
    case 'create_listing':
      include("actions/create_listing.php");
    break;
    //
    //
    //

    //
    //
    // update listing parameters
    case 'update_listing_parameters':
      include("actions/update_listing_parameters.php");
    break;
    //
    //
    //

    //
    //
    // get listings details
    case 'get_listings_details':
      include("actions/get_listings_details.php");
    break;
    //
    //
    //

    //
    //
    // create relationship user
    case 'create_relationship_user':
      include("actions/create_relationship_user.php");
    break;
    //
    //
    //

    //
    //
    // create relationship listing
    case 'create_relationship_listing':
      include("actions/create_relationship_listing.php");
    break;
    //
    //
    //

    //
    //
    // delete relationship
    case 'delete_relationship':
      include("actions/delete_relationship.php");
    break;
    //
    //
    //

    //
    //
    // get relationships list
    case 'get_relationships_list':
      include("actions/get_relationships_list.php");
    break;
    //
    //
    //

    default:
      return_api_error("incorrect_action");
    break;

  }

}
else {
  return_api_error("missing_action");
}

?>
