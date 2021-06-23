<?php
    require_once(__DIR__."/db.php");

    $con = createDB();

    // Handle creating customer
    if (isset($_POST['create'])) {
        createCustomer();
    }

    if (isset($_POST['update'])) {
        updateCustomerInfo();
    }

    if (isset($_POST['noteSubmitted'])) {
        submitNote();
    }

    if (isset($_POST['delete'])) {
        deleteCustomer();
    }

    if (isset($_POST['addInvoice'])) {
        addInvoice();
    }

    if (isset($_POST['noteCust'])) {
        displayNotes($_POST['noteCust']);
    }

    if (isset($_POST['invoiceCust'])) {
        displayInvoices($_POST['invoiceCust']);
    }


    function createCustomer() {

        // Prepare statment 
        $stmt = $GLOBALS['con']->prepare("INSERT INTO customerInfo (first_name, last_name, street_address, zip, city, us_state, phone, email, preferred_contact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $firstName, $lastName, $address, $zipCode, $city, $state, $phone, $email, $preferredContact);


        // Get all customer information from POST 
        //  form request via escaping/validating function call
        $firstName = validateInput('firstName', 'post');
        $lastName = validateInput('lastName', 'post');
        $address = validateInput('address', 'post');
        $zipCode = validateInput('zipCode', 'post');
        $city = validateInput('city', 'post');
        $state = validateInput('state', 'post');
        $phone = validateInput('phone', 'post');
        $email = validateInput('email', 'post');
        $preferredContact = validateInput('preferredContact', 'post');

        $stmt->execute();            
        $stmt->close();
    }

    // Takes a string as input and finds the 
    //  POST request associated with the string
    //  and validates/escapes the input from the user
    function validateInput($val, $type) {
        if ($type === 'post') {
            $validInput = mysqli_real_escape_string($GLOBALS['con'], trim($_POST[$val]));
        } else {
            $validInput = mysqli_real_escape_string($GLOBALS['con'], trim($_GET[$val]));
        }
        if (empty($validInput)) {
            return false;
        } else {
            return $validInput;
        }
    }


    // Get custom information from db
    function getCustomers() {
        $filter = $_POST['filter'];         // User's selected filter (default "Amount Owed")
        $search = $_POST['clientSearch'];   // User's entered search term

        if ($search === "") {
            $stmt = $GLOBALS['con']->prepare("SELECT * FROM customerInfo ORDER BY $filter");
            // $stmt->bind_param("s", $filter);
        } else {
            $stmt = $GLOBALS['con']->prepare("SELECT * FROM customerInfo WHERE first_name LIKE ? OR last_name LIKE ? ORDER BY $filter");
            $stmt->bind_param("ss", $search, $search);
        }

        // $result = mysqli_query($GLOBALS["con"], $sql);
        $stmt->execute();
        $result = $stmt->get_result();
  
        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
    }

    // Updates selected customer with new information
    function updateCustomerInfo() {

        $stmt = $GLOBALS['con']->prepare("UPDATE customerInfo SET first_name=?, last_name=?, street_address=?, zip=?, city=?, us_state=?, phone=?, email=?, preferred_contact=? WHERE id=?");
        $stmt->bind_param("ssssssssss", $firstName, $lastName, $address, $zipCode, $city, $state, $phone, $email, $preferredContact, $uid);

        // Get all customer information from POST 
        //  form request via escaping/validating function call
        $uid = validateInput('uid', 'post');
        $firstName = validateInput('firstName', 'post');
        $lastName = validateInput('lastName', 'post');
        $address = validateInput('address', 'post');
        $zipCode = validateInput('zipCode', 'post');
        $city = validateInput('city', 'post');
        $state = validateInput('state', 'post');
        $phone = validateInput('phone', 'post');
        $email = validateInput('email', 'post');
        $preferredContact = validateInput('preferredContact', 'post');
            
        $stmt->execute();
        $stmt->close();    
    }

    // Adds a note to customerNotes table of selected customer
    function submitNote() {
        $stmt = $GLOBALS['con']->prepare("INSERT INTO customerNotes (customer_id, note) VALUES (?, ?)");
        $stmt->bind_param("ss", $uid, $note);
        
        $note = validateInput('notepad', 'post');
        $uid = validateInput('uid', 'post');

        $stmt->execute();
        $stmt->close();  
    }

    // Deletes all notes and customer info of selected customer
    function deleteCustomer() {

        // Delete all notes of customer
        $stmt = $GLOBALS['con']->prepare("DELETE FROM customerNotes WHERE customer_id = ?");
        $stmt->bind_param("s", $uid);

        $uid = validateInput('delete', 'post');

        $stmt->execute();
        $stmt->close();


        // Delete customer 
        $stmt = $GLOBALS['con']->prepare("DELETE FROM customerInfo WHERE id = ?");
        $stmt->bind_param("s", $uid);
    
        $uid = validateInput('delete', 'post');

        $stmt->execute();
        $stmt->close();  
    }

    // Retrieves all service in db for selector in invoice form
    function getServices() {
        $sql = 'SELECT * FROM services';

        $results = mysqli_query($GLOBALS['con'], $sql);

        if (mysqli_num_rows($results) > 0) {
            return $results;
        }
    }

    // Adds an invoice to customer with $custId
    function addInvoice() {
        $stmt = $GLOBALS['con']->prepare("INSERT INTO invoices (customer_id, amount_due, invoice_due_date, service_id, service_name, fully_paid)
        VALUES (?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("ssssss", $custId, $amountDue, $invoiceDate, $serviceId, $serviceName, $paid);


        $custId = validateInput('invoiceCustId', 'post');
        $invoiceDate = validateInput('invoiceDate', 'post');
        list($serviceId, $serviceName) = explode(":", validateInput('service', 'post')); // 'service id : service name'
        $paid = validateInput('fullyPaid', 'post');     // 1 === 'no' : 2 === yes'
        $amountDue = '';

        // Get the price of the service
        $sql = "SELECT price FROM services WHERE id = $serviceId";
        $result = mysqli_query($GLOBALS['con'], $sql);
        if ($result) {
            $amountDue = mysqli_fetch_assoc($result); 
            $amountDue = $amountDue['price'];
        }

        $stmt->execute();
        $stmt->close();  
    }

    // Displays invoices of customer with custId
    function displayInvoices($custId) {
        $sql = "SELECT * FROM invoices  
                WHERE customer_id = $custId
            ";
        
        $result = mysqli_query($GLOBALS["con"], $sql);
        if ($result) {
            $rows = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } 
         
    }

    // Displays notes of customer with custId
    function displayNotes($custId) {
        $sql = "SELECT * FROM customerNotes  
                WHERE customer_id = $custId
            ";
        
        $result = mysqli_query($GLOBALS["con"], $sql);
        if ($result) {
            $rows = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        } 
         
    }

?>