<?php
    include "../coreFunctions/connect.php";
    include "../coreFunctions/coreFunction.php";

    $name = sterilizeValue($_POST['name']);
    $company = sterilizeValue($_POST['company']);
    $email = sterilizeValue($_POST['email']);
    $phone = sterilizeValue($_POST['phone']);
    $message = sterilizeValue($_POST['message']);
    

    if(empty($name) || empty($company) || empty($email) || empty($message)){
        echo "Please fill up all the required fields";
    }else if(!isValidEmail($email)){
        echo "Invalid email address format";
    }else if(containsSlang($message)){
        echo "Your message contains slangs / inappropriate words";
    }else{
        $colNames = array("name", "company", "email", "phone", "message");
        $colValues = array($name, $company, $email, $phone, $message);
        if(insert("website_contactus_message", $colNames, $colValues)){
            echo "Message send successfully";
        }else{
            echo "Failed to send your message. Please try again";
        }
    }
?>