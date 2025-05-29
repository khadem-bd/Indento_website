<?php
    include "../coreFunctions/connect.php";
    include "../coreFunctions/coreFunction.php";

    $name = sterilizeValue($_POST['name']);
    $companyname = sterilizeValue($_POST['companyname']);
    $email = sterilizeValue($_POST['email']);
    $phone = sterilizeValue($_POST['phone']);
    

    if(empty($name) || empty($companyname) || empty($email)){
        echo "Please fill up all the required fields";
    }else{
        $colNames = array("name", "company", "email", "phone");
        $colValues = array($name, $companyname, $email, $phone);
        if(checkDataExistance("demo_request", array("email"), array($email))){
            echo "We have already recieved your demo request previously. Our team will get in touch with you shortly";
        }else{
            if(insert("demo_request", $colNames, $colValues)){
                echo "Demo request send successfully, we will get in touch with you shortly";
            }
        }
    }
?>