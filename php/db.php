<?php
    function createDB() {
        $server = "";
        $user = "";
        $pass = "";
        $db="rolodex";

        // Create DB Connection
        $con = mysqli_connect($server, $user, $pass);
        
        // Checl for connection
        if (!$con) {
            die("Connection Failed: ".mysqli_connect_error());
        }

        // Create DB
        $sql = "CREATE DATABASE IF NOT EXISTS $db";
        if (mysqli_query($con, $sql)) {
            $con = mysqli_connect($server, $user, $pass, $db);

            // Add customerInfo table
            $sql = "CREATE TABLE IF NOT EXISTS customerInfo(
                        id INT(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        first_name VARCHAR(25) NOT NULL,
                        last_name VARCHAR(25) NOT NULL,
                        street_address VARCHAR(50) NOT NULL,
                        zip CHAR(5) NOT NULL,
                        city VARCHAR(25) NOT NULL,
                        us_state VARCHAR(25) NOT NULL,
                        phone VARCHAR(25) NOT NULL,
                        email VARCHAR(50),
                        preferred_contact VARCHAR(10)
                    );
            ";
            
            // Add customerNotes table
            $sql .= "CREATE TABLE IF NOT EXISTS customerNotes(
                        id INT(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        customer_id INT(15) NOT NULL,
                        note VARCHAR(200) NOT NULL,
                        FOREIGN KEY (customer_id) REFERENCES customerInfo(id)
                    );
                ";

            // Add services table 
            $sql .= "CREATE TABLE IF NOT EXISTS services(
                        id INT(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(25) NOT NULL,
                        description VARCHAR(100),
                        price DOUBLE PRECISION NOT NULL
                    );
                ";

            // Add invoices table 
            $sql .=  "CREATE TABLE IF NOT EXISTS invoices(
                        id INT(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        customer_id INT(15) NOT NULL,
                        amount_due DOUBLE PRECISION NOT NULL,
                        invoice_due_date DATE,
                        service_id INT(15) NOT NULL,
                        service_name VARCHAR(25) NOT NULL,
                        fully_paid BOOL NOT NULL,
                        FOREIGN KEY (customer_id) REFERENCES customerInfo(id),
                        FOREIGN KEY (service_id) REFERENCES services(id)
                    );
            ";

            
            

            if (mysqli_multi_query($con, $sql)) {
                do {
                    if ($result = mysqli_store_result($con)) {
                        while($row = $result.fetch_row()) {
                            printf("%s\n",$row);
                        }
                        $result.free();
                    }
                } while (mysqli_next_result($con));
            } else {
                echo "Error while creating tables ".mysqli_error($con);
            }

            $sql = "SELECT * FROM services";
            $results = mysqli_query($con, $sql);
            if (mysqli_num_rows($results) === 0) {
                $sql =  "INSERT INTO services (name, description, price)
                        VALUES ('Schedule C', 'Business Income', 180.00);    
                    ";

                $sql .= "INSERT INTO services (name, description, price)
                        VALUES ('Schedule D', 'Gains and Losses', 110.00);    
                    ";

                $sql .= "INSERT INTO services (name, description, price)
                        VALUES ('Schedule E', 'Rental Income', 130.00);    
                    ";

                $sql .= "INSERT INTO services (name, description, price)
                        VALUES ('Schedule F', 'Farm Income', 175.00);    
                    ";
                
                $sql .= "INSERT INTO services (name, description, price)
                        VALUES ('Form 940', 'Federal Unemployment', 60.00);    
                    ";

                $sql .= "INSERT INTO services (name, description, price)
                        VALUES ('Form 8965', 'Health Coverage Exemptions', 50.00);    
                    ";

                $sql .= "INSERT INTO services (name, description, price)
                        VALUES ('Form 1095-A', 'Health Insurance Marketplace', 50.00);    
                    ";


                if (mysqli_multi_query($con, $sql)) {
                    do {
                        if ($result = mysqli_store_result($con)) {
                            while($row = $result.fetch_row()) {
                                printf("%s\n",$row);
                            }
                            $result.free();
                        }
                    } while (mysqli_next_result($con));
                    return $con;
                } else {
                    echo "Error while creating tables".mysqli_error($con);
                }
            }

            return $con;

        } else {
            echo "Error while creating database".mysqli_error($con);
        }
    }
?>
