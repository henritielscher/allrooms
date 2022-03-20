<?php

// Get Hashed Stylesheets from Webpack 
// OLD FILES HAVE TO BE ERASED MANUALLY BEFORE BUILD
function getHashedFileNames(){
    $dirPublic = new DirectoryIterator(get_stylesheet_directory()."/public");

    foreach($dirPublic as $file){
        $pathinfo = pathinfo($file, PATHINFO_EXTENSION);
        if($pathinfo === "js" || $pathinfo === "css"){
            $fullName = basename($file); // FULL FILE NAME IN FOLDER
            $name = substr(basename($fullName), 0, strpos(basename($fullName), ".")); // = scripts or styles

            if($pathinfo === "js") {
                wp_enqueue_script("main-$name", get_template_directory_uri() . "/public\/" . $fullName, NULL, NULL, true);
            }
            if($pathinfo === "css"){
                wp_enqueue_style($name, get_template_directory_uri() . "/public\/" . $fullName, NULL, NULL, false);
            }
        }
    }
}

// Function to remove p-Tag from WYSIWYG-fields
function removePTag( $field, $rel ) {
	remove_filter('acf_the_content', 'wpautop');
	the_field( $field, $rel, false, false );
	add_filter('acf_the_content', 'wpautop');
}

// Gets correct date format for festival or show
function manageDate($rel){
    $showDate = new DateTime(get_field("date_from", $rel));
    if(get_field("date_to", $rel)){
        global $endDate;
        $endDate = new DateTime(get_field("date_to", $rel));
    }

    if(get_field("status_is_festival", $rel) || get_field("date_to", $rel)){
        if($showDate == $endDate){
            return $showDate->format("D, d.m.Y");
        } else {
            return $showDate->format("D, d.m.Y") . " - <br>" . $endDate->format("D, d.m.Y");
        }
    } else {
        return $showDate->format("D, d.m.Y");
    }
}

function isArtistOnTour($tours){
    $today = date("Ymd");
    foreach($tours as $tour){
        $shows = get_field("related_shows", $tour);
        $showDates = [];
            foreach($shows as $show){
                array_push($showDates, get_field("date_from", $show));
            }
        rsort($showDates);
        $last_show = $showDates[0];

        if($last_show >= $today){
            return true;
        }
    }
    return false;
}

// Function to manage Show-Status visualisation
function manageStatus($rel){
    $states = ["sold_out", "relocated", "postponed", "cancelled"];    
    $result = [];

    foreach($states as $status){
        if(get_field("status_is_$status", $rel)){
            $statusText = "";
            if($status === "relocated"){
                $statusText = langOpts("Verlegt", "Relocated");
            }

            if($status === "postponed"){
                $statusText = langOpts("Verschoben", "Postponed");
            }

            if($status === "cancelled"){
                $statusText = langOpts("Abgesagt", "Cancelled");
            }

            if($status === "sold_out"){
                $statusText = langOpts("Ausverkauft", "Sold Out");
            }

            array_push($result, "<div class='status-box $status'>$statusText</div>");
        }
    }

    // Status-Message Logic for Single Artist and Tour Page
    if(is_singular("artist") || is_post_type_archive("tour")){
        $hasStatusMsg = get_field("status_message", $rel);
        if($hasStatusMsg){
            $msg = langOpts(get_field("status_message_de", $rel), get_field("status_message_en", $rel));
            array_push($result, "<span class='status-message'>$msg</span>");
        }
    }

    if(empty($result)){
        if(is_page("berlin")) return;
         return "-";
    }

    $strResult = implode("", $result);
    return $strResult;
}

function manageDateLinks($rel){
    $fb = esc_url(get_field("links_facebook", $rel));
    $ticket = esc_url(get_field("links_tickets", $rel));
    $result = [];

    if($fb){
        array_push($result, "<a href=$fb target='_blank'><button class='fb-link'>FB</button></a>");
    }
    if($ticket){
        array_push($result, "<a href=$ticket target='_blank'><button class='ticket-link'>tickets</button></a>");
    }

    $strResult = implode("", $result);

    if(empty($strResult)){
        if(is_page("berlin")) return;
        return "-";
    } 
    return $strResult;
}

// Translate Function
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

// Change Title Dynamically
function changeTheTitle($title){
    $post_fix = "ALL ROOMS";
    
    if(is_front_page()) $title = "Home";

    if(is_post_type_archive("news")) $title = "News";

    if(is_post_type_archive("artist")) $title = "Artists";

    if(is_singular("artist")) $title = get_the_title();

    if(is_post_type_archive("tour")) $title = "On Tour";

    if(is_page("berlin")) $title = "Berlin Shows";

    if(is_page("services")) $title = "Our Services";

    if(is_page("contact")) $title = "Contact";

    return $title . " - " . $post_fix;
}

add_filter("pre_get_document_title", "changeTheTitle");