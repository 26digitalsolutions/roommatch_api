<?php
$api_errors = [
  "database_access_error" => "Database access error.",
  "database_query_error" => "Error while querying database.",
  "wrong_api_key" => "Incorrect or missing api key.",
  "missing_action" => "Action parameter missing.",
  "incorrect_action" => "Incorrect action parameter.",
  "user_authentification_failed" => "User authentification failed.",
  "country_not_allowed" => "Country not allowed",
  "password_wrong_format" => "Password should be 5 to 50 characters long.",
  "email_wrong_format" => "Incorrect email address format.",
  "content_not_json" => "Content must be a valid json object.",
  "content_parameter_not_allowed" => "Content contains not allowed parameter(s).",
  "listing_id_incorrect_format" => "Listing id has incorrect format",
  "user_id_incorrect_format" => "User id has incorrect format",
  "relationship_id_incorrect_format" => "Relationship id has incorrect format",

  "create_account_fields_missing" => "Parameter(s) missing (email_address, password, country).",
  "create_account_email_already_exists" => "This email address is already associated to an account.",

  "login_account_fields_missing" => "Parameter(s) missing (email_address, password).",
  "login_account_email_wrong_format" => "Incorrect email address format.",
  "login_account_account_not_found" => "No account found with those credentials",

  "update_user_profile_missing_parameter" => "Parameter(s) missing (content).",
  "update_user_profile_content_age_incorrect" => "Incorrect age parameter.",

  "get_users_details_missing_parameter" => "Parameter(s) missing (content).",
  "get_users_details_users_not_found" => "Users not found.",

  "update_password_missing_parameter" => "Parameter(s) missing (old_password, new_password).",
  "update_password_incorrect_current_password" => "Current password entered is incorrect.",

  "update_email_address_missing_parameter" => "Parameter(s) missing (new_email_address).",
  "update_email_address_already_used" => "This email address is already associated to an account.",

  "update_listing_parameters_missing_parameter" => "Parameter(s) missing (listing_id, content).",
  "update_listing_parameters_listing_not_found" => "Listing not found.",

  "get_listings_details_missing_paramater" => "Parameter(s) missing (content).",
  "get_listings_details_listing_not_found" => "Listings not found.",

  "create_listing_missing_parameter" => "Parameter(s) missing (listing_type).",
  "create_listing_incorrect_listing_type" => "Incorrect listing type.",

  "create_relationship_user_missing_parameter" => "Parameter(s) missing (relationship_type, recipient_id).",
  "create_relationship_user_incorrect_relationship_type" => "Incorrect relationship type.",
  "create_relationship_user_recipient_not_found" => "Recipient (recipient_id) not found or account not active.",
  "create_relationship_user_incorrect_recipient_id" => "Incorrect recipient_id, equals user_id.",
  "create_relationship_user_blocked_user" => "Relationship can not be created if any of the two users have blocked the other.",
  "create_relationship_user_already_exists" => "This relationship already exists.",

  "create_relationship_listing_missing_parameter" => "Parameter(s) missing (relationship_type, listing_id).",
  "create_relationship_listing_incorrect_relationship_type" => "Incorrect relationship type.",
  "create_relationship_listing_not_found" => "Listing (listing_id) not found or listing not active or listing belongs to sender (sender can not create relationship with himself or own listings).",
  "create_relationship_listing_blocked_user" => "Relationship can not be created if any of the two users have blocked the other.",
  "create_relationship_listing_already_exists" => "This relationship already exists.",

  "delete_relationship_missing_parameter" => "Parameter(s) missing (relationship_id).",
  "delete_relationship_not_found" => "Relationship not found.",

  "get_relationships_list_missing_parameter" => "Parameter(s) missing (type, id, relationship_type (optional), offset, limit).",
  "get_relationships_list_relationship_type_not_allowed" => "Relationship type not allowed."
];
?>
