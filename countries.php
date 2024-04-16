<?php
// Check if country_id is set in the POST request
if(isset($_POST['country_id'])) {
    require 'connect.php';
    $country_phonecode = $_POST['country_id'];


    $sql = "SELECT ph_mask FROM `phone_masks` WHERE ph_country_shortname = (SELECT country_shortname FROM countries WHERE country_phonecode = $country_phonecode LIMIT 1)";
    $result = mysqli_query($conn, $sql);

    $data = mysqli_fetch_assoc($result);
    $ph_mask = $data['ph_mask'];
    $ph_mask = str_replace("+".$country_phonecode."-", "", $ph_mask);
    // $ph_mask = str_replace("+".$country_id."-", "", $ph_mask);

    $data = array(
        'ph_mask' => $ph_mask,
    );    
    // Return the data as JSON
    echo json_encode($data);
} else {
    // If country_id is not set in the POST request, return an error message
    echo "Error: country_id is not set";
}
?>
