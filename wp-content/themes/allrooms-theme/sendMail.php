<?php
    $json = file_get_contents("php://input");
    $obj = json_decode($json);

    $validSubjects = ["allgemein", "booking", "presse", "anderes"];

    $res = new stdClass();
    $res->errors = [];
    $res->success = false;
    $res->successMsg = "";

    // SANITIZE DATA
    $name = trim(filter_var($obj->name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW));
    $email = trim(strtolower(filter_var($obj->email, FILTER_SANITIZE_EMAIL)));
    $subject = trim(filter_var($obj->subject, FILTER_SANITIZE_STRING));
    $message = trim(filter_var($obj->message, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
    $privacy = trim($obj->privacy);


    // VALIDATE DATA
    if(empty($name)){
        $res->errors["name"] = langOpts("Das Namen Feld kann nicht leer bleiben.", "The name field cannot be empty");
    } 

    if(empty($email)){
        $res->errors["email"] = langOpts("Das E-Mail Feld kann nicht leer bleiben.", "The E-Mail field cannot be empty.");
    } else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $res->errors["email"] = langOpts("Ungültiger Wert für das E-Mail-Feld!", "Invalid E-Mail input!");
        }
    }
    
    if(empty($subject)){
        $res->errors["subject"] = langOpts("Das Betreff Feld kann nicht leer bleiben.", "The subject field cannot be empty.");
    } else {
        if (!preg_match("/^[a-zA-Z ]*$/", $subject) || !in_array($subject, $validSubjects)){
            $res->errors["subject"] = langOpts("Ungültiger Wert für den Betreff!", "Invalid subject field value!");
        }
    }

    if(empty($message)){
        $res->errors["message"] = langOpts("Das Nachricht Feld kann nicht leer bleiben.", "The message field cannot be empty.");
    } 

    if(empty($privacy)){
        $res->errors["privacy"] = langOpts("Du musst noch die Datenschutzvereinbarung akzeptieren.", "Please agree to the privacy policy.");
    } else {
        if(!filter_var($privacy, FILTER_VALIDATE_BOOLEAN)){
            $res->errors["privacy"] = langOpts("Ungültiger Wert!", "Invalid input!");
        }
    }

    // CHECK FOR EMPTY ERRORS-ARRAY
    if(empty($res->errors)){
        // MAKE SUCCESS TRUE FOR POSITIVE CLIENT FEEDBACK
        $res->success = true;
        $res->successMsg = langOpts("Erfolgreich gesendet!", "Successfully sent!");

        // SEND EMAIL
        $to = "info@allrooms-agency.com";
        $subject = "All Rooms Formular: ". ucfirst($subject);
        $header = "From: $email";
        $msg = wordwrap($message, 70, "\r\n");

        mail($to, $subject, $msg, $header);
    } 
        
    echo json_encode($res);
    
// LANGUAGE OPTIONS DEPENDING ON COOKIE
function langOpts($de, $en){
    if($_COOKIE['lang'] == "en"){
        if(!$en) return "Not available";
           return $en;

    } elseif($_COOKIE['lang'] == "de") {
        if(!$de) return "Nicht verfügbar";
            return $de;
    }
    return $de;
}