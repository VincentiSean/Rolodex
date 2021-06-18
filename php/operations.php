<?php
    require_once("./php/filterBtnComponent.php");
    require_once("./php/db.php");

    $con = createDB();

    // Handle creating customer
    if (isset($_POST['create'])) {
        createCustomer();
    }


    function createCustomer() {

        // Get all customer information from POST 
        //  form request via escaping/validating function call
        $firstName = validateInput('firstName');
        $lastName = validateInput('lastName');
        $address = validateInput('address');
        $zipCode = validateInput('zipCode');
        $city = validateInput('city');
        $state = validateInput('state');
        $phone = validateInput('phone');
        $email = validateInput('email');
        $preferredContact = validateInput('preferredContact');


        // Check to see if all NOT NULL fields exist
        if (
            $firstName &&
            $lastName &&
            $address &&
            $zipCode &&
            $city &&
            $state &&
            $phone
        ) {
            
            $sql = "INSERT INTO customerinfo (first_name, last_name, street_address, zip, city, us_state, phone, email, preferred_contact) 
                        VALUES ('$firstName', '$lastName', '$address', '$zipCode', '$city', '$state', '$phone', '$email', '$preferredContact');";
            echo $sql;
            if (mysqli_query($GLOBALS['con'], $sql)) {
                echo "Yay!";
            } else {
                echo "Error ".mysqli_error($GLOBALS['con']);
            }
        } else {
            echo "Provide all required input";
        }
    }

    // Takes a string as input and finds the 
    //  POST request associated with the string
    //  and validates/escapes the input from the user
    function validateInput($val) {
        $validInput = mysqli_real_escape_string($GLOBALS['con'], trim($_POST[$val]));
        if (empty($validInput)) {
            return false;
        } else {
            return $validInput;
        }
    }
?>