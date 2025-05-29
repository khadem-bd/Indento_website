<?php
    include "../coreFunctions/connect.php";
    include "../coreFunctions/coreFunction.php";

    $email = sterilizeValue($_POST['email']);
    
    if(empty($email)){
        echo "Please enter an email address";
    }else{
        if(checkDataExistance("subscriber_list", array("email"), array($email))){
            echo "You are already subscribed.";
        }else{
            if(insert("subscriber_list", array("email"), array($email))){
                echo "Congratulations, you are now our subscriber";
            }
        }
    }
?>