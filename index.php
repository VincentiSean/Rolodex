<?php
    require_once("./php/filterBtnComponent.php");
    require_once("./php/operations.php");

    $services = getServices();
    $rand = "hi";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolodex</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link type="text/css" rel="stylesheet" href="css/reset.css">
    <link type="text/css" rel="stylesheet" href="css/main.css">
</head>
<body>
    <header>
        <a id="logo-link" href="../rolodex/">
            Rolodex
        </a>
    </header>
    <main class="container">
        <div class="main-container">
            
            <!-- Filter Form -->
            <form id="filter-form" method="POST" onSubmit="showTable()">
                <div id="search-container" class="control has-icons-left">
                    <input class="input" type="search" name="clientSearch" value="" id="client-search" placeholder="Search by client name">
                    <span class="icon is-large is-left">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </span>
                </div>
                <div id="filter-container">
                    <label id="filter-label">Filters</label>
                    <!-- Add filter buttons -->
                    <div id="filter-btn-container">
                        <button class="filter-btn button is-info" name='filter' value='first_name'>First Name</button>
                        <button class="filter-btn button is-info" name='filter' value='last_name'>Last Name</button>
                        <button class="filter-btn button is-info" name='filter' value='id'>Customer ID</button>
                    </div>
                </div>
            </form>
            
            <!-- Customer Information Table -->
            <div id="customer-display" class="table-container">
                <table id="table" class="table is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Zip Code</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Contact Type</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <?php
                            if (isset($_POST['filter']) || isset($_POST['clientSearch'])) {
                                $results = getCustomers();
                                if ($results) {
                                    while ($row = mysqli_fetch_assoc($results)) {    ?>
                                    
                                    <tr>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['id']; ?></td>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['first_name']; ?></td>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['last_name']; ?></td>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['street_address']; ?></td>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['zip']; ?></td>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['city']; ?></td>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['us_state']; ?></td>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['phone']; ?></td>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['email']; ?></td>
                                        <td data-id="<?php echo $row['id']; ?>"><?php echo $row['preferred_contact']; ?></td>
                                        <td>
                                            <button class="button is-success is-small" id="edit-customer" onclick="edit(<?php echo $row['id']; ?>)">
                                                Edit
                                            </button>
                                        </td>
                                        <form method="POST">
                                        <td>
                                            <form method="POST">
                                                <input class="user-id-input" type="hidden" name="delete" data-id="<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>">
                                                <button class="button is-danger is-small" id="delete-customer" type="submit">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                        
                                <?php
                                    }
                                }
                            }
                        ?>
                    </tbody>
                </table>

            </div>

            <button id="create-btn" class="button is-success" onclick="createNewCustomer(this)">
                Create new customer
            </button>

            <div class="columns is-desktop">
                <div id="create-container" class="column is-4">   
                    <label id="create-label">Create Customer</label> 
                    <form method="POST">
                        <input id="first-name" class="create-inputs" type="text" name="firstName" placeholder="First Name" value="">
                        <input id="last-name" class="create-inputs" type="text" name="lastName" placeholder="Last Name" value="">
                        <input id="address" class="create-inputs" type="text" name="address" placeholder="Street Address" value="">
                        <input id="zip-code" class="create-inputs" type="text" name="zipCode" placeholder="ZIP Code" value="">
                        <input id="city" class="create-inputs" type="text" name="city" placeholder="City" value="">
                        <input id="state" class="create-inputs" type="text" name="state" placeholder="State" value="">
                        <input id="phone" class="create-inputs" type="tel" name="phone" placeholder="Phone #" value="">
                        <input id="email" class="create-inputs" type="email" name="email" placeholder="Email" value="">
                        <input id="preferred-contact" class="create-inputs" type="text" name="preferredContact" placeholder="Preferred Contact (phone/email)" value="">
                        <button id="add-customer-btn" class="button is-success" type="submit" name="create" onClick="addCustomer()">Add Customer</button>
                        <input class="user-id-input" type="hidden" name="update" value="">
                    </form>
                </div>

                <div id="edit-container" class="column is-6">
                    <div id="invoice-container-main">       
                        <button type="button" class="customer-btns button" id="show-invoice-btn" onClick="toggleInvoice()">Add Invoice</button>
                        <div id="add-invoice-input-container">
                            <form id="invoice-form" method="POST">
                                <div id="invoice-top-form-container">
                                    <input class="user-id-input" type="hidden" name="invoiceCustId" value="">
                                        <?php
                                            $serviceResults = getServices();
                                            if ($serviceResults) { ?>
                                                <label class="invoice-labels" for="service">Service: </label><br>
                                                <select id="service-select" class="invoice-select select" name="service">
                                                <?php 
                                                    while ($row = mysqli_fetch_assoc($serviceResults)) {  echo $row['id'];  ?>
                                                        <option value="<?php echo $row['id'].':'.$row['name']; ?>">
                                                            <?php echo $row['name']; ?>
                                                        </option>
                                                <?php
                                                    }
                                                ?>
                                                </select>
                                                <?php
                                            }
                                        ?>   
                                    <br>
                                    <label class="invoice-labels" for="fullyPaid">Fully Paid: </label><br>
                                    <select id="fully-paid" class="invoice-select select" name="fullyPaid">
                                        <option value="1">No</option>
                                        <option value="2">Yes</option>
                                    </selecT>          
                                </div>
                                      
                                <div id="invoice-date-container">
                                    <label  class="invoice-labels" for="invoiceDate">Invoice Due: </label>
                                    <input id="invoice-due-date" class="input" type="date" name="invoiceDate" value="">
                                </div>
                                
                                <div id="invoice-button-container">
                                    <button type="button" id="submit-invoice" class="button is-success" name="addInvoice" onClick="submitInvoice()">Submit Invoice</button>
                                    <button type="button" id="hide-invoice" class="button is-danger" name="hideInvoice" onClick="toggleInvoice()">Cancel Invoice</button>
                                </div>
                                
                            </form>
                        </div>
                        
                        <div id="invoice-container">
                            <table id="invoice-table" class="table is-hoverable">
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Service</th>
                                        <th>Fully Paid</th>
                                        <th>Amount Due</th>
                                        <th>Invoice Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div> 

                    <div id="note-container-main">
                        <form method="POST">
                            <textarea id="notepad" class="textarea" name="notepad"></textarea>
                            <input class="user-id-input" type="hidden" name="uid" value="">
                            <div id="note-btn-div">
                                <button type="button" id="submit-note-btn" class="button is-success" name="addNote" onClick="submitNote()">Submit Note</button>
                                <button type="button" id="cancel-note-btn" class="button is-danger" name="hideNote" onClick="toggleNote()">Cancel Note</button>
                            </div>
                        </form>
                        <button type="button" class="customer-btns button" id="show-note-btn" onClick="toggleNote()">Add Customer Note</button>
                        <div id="note-container"></div>
                    </div>
                </div>
            </div>

            
        </div>
        
    </main>
    <div id="alert-update-div">
        <div id="alert-dialog">
            <h4>Customer Updated</h4>
            <button id="close-alert-btn" class="button is-success" onClick="toggleAlert('')">Close</button>
        </div>
    </div>

    <!-- Scripts from other sources -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/5bfa6555cb.js"></script>

    <!-- Local JS files -->
    <script type="text/javascript" src="./js/helperFunctions.js"></script>
    <script type="text/javascript" src="./js/postCalls.js"></script>
    <script type="text/javascript" src="./js/index.js"></script>
</body>
</html>