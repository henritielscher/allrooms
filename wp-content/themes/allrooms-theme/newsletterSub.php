<?php

// GET DATA
$json = file_get_contents("php://input");
$obj = json_decode($json);

// VALID NEWSTYPES
$validNewstypes = ["privat", "business"];

// CREATE RESPONSE OBJECT
$res = new stdClass();
$res->errors = [];
$res->given = [];
$res->success = false;
$res->successMsg = null;

// SANITIZE DATA
$newstype = trim(filter_var($obj->newstype, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
$email = trim(strtolower(filter_var($obj->email, FILTER_SANITIZE_EMAIL)));
$city = null;
$company = null;

if(property_exists($obj, "city")){
    $city = trim(filter_var($obj->city, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW));
}

if(property_exists($obj, "company")){
    $company = trim(filter_var($obj->company, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW));
}

// VALIDATE DATA
$res->given["newstype"] = $newstype;
if(empty($newstype)){
    $res->errors["newstype"] = langOpts("Bitte wähle einen Newsletter-Typen aus.", "Please select one newsletter type." );
} else {
    if(!in_array($newstype, $validNewstypes)){
        $res->errors["newstype"] = langOpts("Ungültiger Newsletter-Typ!", "Invalid type of newsletter!");
    }
}

if(empty($email)){
    $res->errors["email"] = langOpts("Das E-Mail Feld kann nicht leer bleiben.", "The E-Mail field cannot be empty.");
} else {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $res->errors["email"] = langOpts("Ungültiger Wert für das E-Mail-Feld!", "Invalid E-Mail input!");
    }
}

// SEND MAIL IF NO ERRORS
if(empty($res->errors)){
    $res->success = true;
    $res->successMsg = langOpts("Abonniert!", "Subscribed!");

    $additionalLabel = null;
    $additionalField = null;
    if(isset($company)) {
        $additionalLabel = "Unternehmen";
        $additionalField = $company;

        if(empty($company)) $additionalField = "/";
    }
    if(isset($city)) {
        $additionalLabel = "Stadt"; 
        $additionalField = $city;

        if(empty($city)) $additionalField = "/";
    }

    $newstype = ucfirst($newstype);

    $to = "info@allrooms-agency.com";
    $subject = "All Rooms • Neues Newsletter-Abo";
    $header = "From: $email";
    $message = "E-Mail: $email\n\rTyp: $newstype\n\r$additionalLabel: $additionalField";

    mail($to, $subject, $message, $header);
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