<?php
    function get_states($country_id){
        require 'connect.php'; // Assuming 'connect.php' contains your database connection logic

        // Prepare SQL query to select states based on country ID
        $sql_states = "SELECT * FROM `states` WHERE state_country_id = $country_id";
        $result_states = mysqli_query($conn, $sql_states);

        if ($result_states) {
            $states = array();

            while ($row = mysqli_fetch_assoc($result_states)) {
                $states[] = $row;
            }

            // Free result set
            mysqli_free_result($result_states);

            return json_encode($states);
        } else {
            return json_encode(array('error' => 'Failed to fetch states'));
        }
    }
    
    if(isset($_POST['country_code'])) {
        $country_code = $_POST['country_code'];

        $states_data = get_states($country_code);
        echo $states_data;
    } else {
        echo json_encode(array('error' => 'Country code not provided'));
    }
?>
