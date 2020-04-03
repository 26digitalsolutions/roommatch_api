<?php

//
// check if post > content exists
if (!isset($_POST['content']) OR $_POST['content'] == "") {
  return_api_error("get_listings_details_missing_paramater");
}
//
//



//
// check that post>content is a valid json object
$content = json_decode($_POST['content'], true);
validate_data("json", $_POST['content']);
//
//



//
////
//////
$q = "";
for ($i=0; $i < count($content); $i++) {

  // check that value has correct listing_id format
  validate_data("listing_id", $content[$i]);

  // build db query
  $q .= "listing_id='".addslashes($content[$i])."' OR ";

}
$q = rtrim($q," OR ");
//////
////
//




//
////
////// Execute request
if ($q != "") {

  $output = array();

  $c = connect_db("read");
  $r = mysqli_query($c,"SELECT * FROM listings WHERE $q");
  if (!$r) {
    return_api_error("database_query_error");
  }
  else {
    if (mysqli_num_rows($r) <= 0) {
      return_api_error("get_listings_details_listing_not_found");
    }
    else {

      while ($d = mysqli_fetch_assoc($r)) {

        $listing_id = $d['listing_id'];

        // listing parameters
        $listing_parameters = array();
        foreach ($d as $key => $value) {
          $listing_parameters[$key] = $value;
        }

        // get listing parameters from table listing_parameters
        $rp = mysqli_query($c,"SELECT parameter_key, parameter_value FROM listings_parameters WHERE listing_id='$listing_id'");
        if (!$rp) {
          return_api_error("database_query_error");
        }
        else {
          while ($dp = mysqli_fetch_assoc($rp)) {

            $listing_parameters[$dp['parameter_key']] = unserialize_data($dp['parameter_key'],$dp['parameter_value']);

          }
        }

        // add to output
        $output[$listing_id] = $listing_parameters;

      }

      // exit result
      return_api_success(
        "get_listings_details_success",
        $output
      );

    }
  }

}
//////
////
//

?>
