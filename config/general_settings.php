<?php

//
////
////// list of countries opened

$countries_opened = array(
  "US" => array(
    "country_code" => "US",
    "country_name" => "United States",
    "domain_name" => "roomrapido.com"
  ),
  "UK" => array(
    "country_code" => "UK",
    "country_name" => "United Kingdom",
    "domain_name" => "roomrapido.co.uk"
  )
);

//////
////
//




//
////
////// List of allowed parameters in table 'users_parameters'

$user_allowed_parameters = array(
  "user_type",
  "ip_address",
  "profile_picture",
  "first_name",
  "last_name",
  "gender",
  "date_of_birth",
  "occupation",
  "phone_number",
  "presentation",
  "location", // string "lat, long"
  "serialized_search_location_metas", // json
  "search_min_rent",
  "search_max_rent",
  "ideal_flatmate_age_range",
  "ideal_flatmate_gender"
);

//////
////
//





//
////
////// List of allowed parameters in table 'listings_parameters'

$listing_allowed_parameters = array(
  "ip_address",
  "title",
  "description",
  "location", // string "lat, long"
  "serialized_location_metas",
  "property_type",
  "property_nb_rooms",
  "property_nb_bathrooms",
  "property_nb_flatmates",
  "property_flatmates_gender",
  "serialized_property_amenities",
  "serialized_rich_data", // data extracted from google map api (transportation around, restaurants, hospitals...)
  "date_available",
  "monthly_rent",
  "deposit",
  "minimum_lease_time",
  "maximum_lease_time",
  "serialized_list_bills_included",
  "room_type",
  "furnished",
  "couples_accepted",
  "pets_accepted",
  "smokers_accepted",
  "ideal_flatmate_gender",
  "ideal_flatmate_age_range"
);

//////
////
//






//
////
////// List of allowed 'listing_type'

$listing_types_allowed = array(
  "room"
);

//////
////
//








//
////
////// relationship types

$relationship_types_allowed = array(

  //
  "listing" => array(

    "create" => array(

      "favorite_listing",
      "interest_listing",
      "visited_listing"

    ),

    "delete" => array(

      "favorite_listing"

    )

  ),

  //
  "user" => array(

    "create" => array(

      "favorite_user",
      "interest_user",
      "blocked_user",
      "visited_user"

    ),

    "delete" => array(

      "favorite_user",
      "blocked_user"

    )

  )

);

//////
////
//


?>
