<?php
    require_once("./php/filterBtnComponent.php");
    require_once("./php/operations.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolodex</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <header>
        <a id="logo-link" href="/">
            Rolodex
        </a>
    </header>
    <main>
        <div class="main-container">
            
            <form id="filter-form">
                <input type="search" name="clientSearch" id="client-search">
                <label id="filter-label">Filters</label>
                <div class="filter-container">
                    <!-- Add filter buttons -->
                    <?php filterBtn('service', 'Service') ?>
                    <?php filterBtn('amountOwed', 'Amount Owed') ?>
                    <?php filterBtn('custID', 'Customer ID') ?>
                    <?php filterBtn('custName', 'Customer Name') ?>
                </div>
            </form>
            
            <div id="customer-display">
                <table id="table-header">
                    <thead>
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody id="table-body"></tbody>
                </table>

            </div>

            <button class="create-btn" onclick="createNewCustomer(this)">
                Create new customer
            </button>

            <div id="create-container">
                <form method="POST">
                    <input type="text" name="firstName" placeholder="First Name">
                    <input type="text" name="lastName" placeholder="Last Name">
                    <input type="text" name="address" placeholder="Street Address">
                    <input type="text" name="zipCode" placeholder="ZIP Code">
                    <input type="text" name="city" placeholder="City">
                    <input type="text" name="state" placeholder="State">
                    <input type="tel" name="phone" placeholder="Phone #">
                    <input type="email" name="email" placeholder="Email">
                    <input type="text" name="preferredContact" placeholder="Preferred Contact (phone/email)">
                    <textarea name="customerNote" cols="30" rows="10"></textarea>
                    <button type="submit" name="create">Add Customer</button>
                </form>
            </div>
        </div>
    </main>

    <script src="index.js"></script>
</body>
</html>