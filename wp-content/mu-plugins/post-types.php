<?php

function allrooms_post_types(){

// ************************************************************
// *                        AGENTS                            *
// ************************************************************

    register_post_type("agent", [
        // "has_archive" => false,
        "supports" => ["custom-fields", "title", "thumbnail"],
        "public" => true,
        "publicly_queryable" => false,
        "labels" => [
            "name" => "Agents",
            "add_new_item" => "Add New Agent",
            "edit_item" => "Edit Agent",
            "all_items" => "All Agents",
            "singular_name" => "Agent",
            "not_found" => "No Agents found."
        ],
        "description" => "Booking agents of the agency.",
        "menu_icon" => "dashicons-id"
    ]);

    function createAgentColumns($columns){
        $newColumns = [];
        $newColumns["cb"] = "cb";
        $newColumns["title"] = "Name";
        $newColumns["email"] = "E-Mail";
        $newColumns["thumbnail"] = "Bild";

        return $newColumns;
    }

    function addCustomAgentColumns($column, $post_id){
        switch($column){
            case "title":
                echo get_the_title();
                break;
            case "email":
                echo get_field("email");
                break;
            case "thumbnail":
                the_post_thumbnail("adminPreview");
                break;
        }
    }

    add_filter("manage_agent_posts_columns", "createAgentColumns");
    add_action("manage_agent_posts_custom_column", "addCustomAgentColumns", 10, 2);

// ************************************************************
// *                        ARTISTS                           *
// ************************************************************

    register_post_type("artist", [
        "rewrite" => ["slug" => "artists"],
        "has_archive" => true,
        "supports" => ["title", "custom-fields", "thumbnail"],
        "public"=>true,
        "labels" => [
            "name" => "Artists",
            "add_new_item" => "Add New Artist",
            "edit_item" => "Edit Artist",
            "all_items" => "All Artists",
            "singular_name" => "Artist"
        ],
        "menu_icon" => "dashicons-format-audio"
    ]);

    function createArtistColumns($columns){
        $customColumns = [];

        $customColumns["cb"] = "cb";
        $customColumns["title"] = "Name";
        $customColumns["thumbnail"] = "Bild";
        $customColumns["press_text"] = "Pressetext";
        $customColumns["services"] = "Services";

        return $customColumns;
    }

    function addCustomArtistColumns($column, $post_id){
        $services = ["booking" => "Booking", "management" => "Management", "marketing" => "Marketing", "social_media" => "Social Media"];

        switch($column){
            case "title":
                echo get_the_title();
                break;
            case "thumbnail":
                the_post_thumbnail("adminPreview");
                break;
            case "press_text":
                if(empty(get_field("press_bio_de"))){
                    echo "/";
                    break;
                }
                echo wp_trim_words(get_field("press_bio_de"), 25);
                break;
            case "services":
                $serviceOutput = [];
                foreach($services as $key => $value){
                    if(get_field("services_$key")){
                        array_push($serviceOutput, $value);
                    }
                }
                    echo implode(" / ", $serviceOutput);
                    break;
        }
    }

    // function setCustomArtistColumnsSortable($columns){
    //     $columns["title"] = "title";

    //     return $columns;
    // }

        // Custom Artist Columns
    add_filter("manage_artist_posts_columns", "createArtistColumns");
    add_action("manage_artist_posts_custom_column", "addCustomArtistColumns", 10, 2);
    // add_filter("manage_edit-artist_sortable_columns", "setCustomArtistColumnsSortable");

// ************************************************************
// *                        NEWS                              *
// ************************************************************

    register_post_type("news", [
        "has_archive" => true,
        "supports" => ["title", "custom-fields", "excerpt", "thumbnail", "editor"],
        "public"=>true,
        "labels" => [
            "name" => "News",
            "add_new_item" => "Add New News",
            "edit_item" => "Edit News",
            "all_items" => "All News",
            "singular_name" => "News"
        ],
        "menu_icon" => "dashicons-megaphone"
    ]);

    // NEWS COLUMNS
    function createNewsColumns($columns){
        $customColumns = [];

        $customColumns["cb"] = "cb";
        $customColumns["date"] = "Datum";
        $customColumns["title"] = "Headline";
        $customColumns["artist"] = "Artist(s)";
        $customColumns["isTourPost"] = "Featured Tour";
        $customColumns["content"] = "Textauszug";
        $customColumns["thumbnail"] = "Bild";

        return $customColumns;
    }

    function addCustomNewsColumns($column, $post_id){
        $relatedArtist = get_field("related_artist")[0];
        $relatedTour = get_field("featured_tour");

        switch($column){
            case "artist":
                // echo get_($relatedArtist->ID);
                echo get_the_title($relatedArtist->ID);
                break;
            case "isTourPost":
                if($relatedTour){
                    echo get_the_title($relatedTour[0]->ID);
                    break;
                }
                echo "/";
                break;
            case "content":
                echo wp_trim_words(get_the_content(), 25);
                break;
            case "thumbnail":
                the_post_thumbnail("adminPreview");
                break;
        }
    }

    // function setCustomNewsColumnsSortable($columns){
    //     $columns["artist"] = "artist";

    //     return $columns;
    // }
    
    // Custom News Columns
    add_filter("manage_news_posts_columns", "createNewsColumns");
    add_action("manage_news_posts_custom_column", "addCustomNewsColumns", 10, 2);
    // add_filter("manage_edit-news_sortable_columns", "setCustomNewsColumnsSortable");


// ************************************************************
// *                        SHOWS                             *
// ************************************************************

    register_post_type("show", [
        "has_archive" => true,
        "supports" => ["custom-fields"],
        "public"=>true,
        "labels" => [
            "name" => "Shows",
            "add_new_item" => "Add New Show",
            "edit_item" => "Edit Show",
            "all_items" => "All Shows",
            "singular_name" => "Shows"
        ],
        "menu_icon" => "dashicons-calendar"
    ]);

    // SHOW COLUMNS
    function createShowColumns($columns){
        $newColumns = [];
        $newColumns["cb"] = "cb";
        $newColumns["event_date"] = "Datum";
        $newColumns["artist"] = "Artist(s)";
        $newColumns["city"] = "Stadt / Land";
        $newColumns["venue"] = "Venue";
        $newColumns["tour"] = "Tour(en)";

        return $newColumns;
    }

    function addCustomShowColumns($column, $post_id){
        $dateString = new DateTime(get_field("date_from", $post_id));
        $artists = get_field("related_artists", $post_id);
        $tours = get_field("related_tours", $post_id);

        switch($column){
            case "event_date": 
                echo $dateString->format("d.m.Y");
                break;
            case "artist":
                $artistOutput = [];
                    foreach($artists as $artist){
                        array_push($artistOutput ,get_the_title($artist));
                    };
                    echo implode(", ", $artistOutput);
                break;
            case "city":
                echo get_field("city", $post_id) . ", " . get_field("country", $post_id) ;
                break;
            case "venue":
                echo get_field("venue_name", $post_id);
                break;
            case "tour":
                if(!$tours){
                    echo "/";
                    break;
                } else {
                    $tourOutput = [];
                    foreach($tours as $tour){
                        array_push($tourOutput, get_the_title($tour));
                    };
                    echo implode(", ", $tourOutput);
                    break;
                }
        };
    }

    function setCustomShowColumnsSortable($columns){
        $columns["event_date"] = "event_date";
        $columns["venue"] = "venue";
        $columns["city"] = "city";

        return $columns;
    }

    add_filter("manage_show_posts_columns", "createShowColumns");
    add_action("manage_show_posts_custom_column", "addCustomShowColumns", 10, 2); // 10 = priority, 2 = number of args to pass
    add_filter("manage_edit-show_sortable_columns", "setCustomShowColumnsSortable");
    
// ************************************************************
// *                        TOUR                              *
// ************************************************************

    register_post_type("tour", [
        "rewrite" => ["slug" => "tours"],
        "has_archive" => true,
        "supports" => ["title", "custom-fields"],
        "public"=>true,
        "labels" => [
            "name" => "Tours",
            "add_new_item" => "Add New Tour",
            "edit_item" => "Edit Tour",
            "all_items" => "All Tours",
            "singular_name" => "Tours"
        ],
        "menu_icon" => "dashicons-airplane"
    ]);

    // TOUR COLUMNS
    function createTourColumns($columns){
        $customColumns = [];

        $customColumns["cb"] = "cb";
        $customColumns["artists"] = "Artist(s)";
        $customColumns["title"] = "Name";
        $customColumns["shows"] = "Shows";
        $customColumns["cities"] = "Städte";

        return $customColumns;
    }

    function addCustomTourColumns($column, $post_id){
        $relatedArtists = get_field("related_artist");
        $relatedShows = get_field("related_shows");

        switch($column){
            case "artists":
                if(!$relatedArtists){
                    echo "n/a";
                } else {
                    $artistOutput = [];
                foreach($relatedArtists as $artist){
                    array_push($artistOutput, get_the_title($artist));
                }
                echo implode(" / ", $artistOutput);
                }
                break;
            case "title":
                echo get_the_title();
                break;
            case "shows":
                if($relatedShows){
                    echo count($relatedShows);
                    break;
                }
                echo "0";
                break;
            case "cities":
                if($relatedShows){
                    $cityOutput = [];
                    foreach($relatedShows as $show){
                    array_push($cityOutput, get_field("city", $show));
                    }
                    echo implode(", ", $cityOutput);
                    break;
                }
                echo "/";
                break;    
        }
    }

    // function setCustomTourColumnsSortable($columns){
    //     $columns["artists"] = "artists";

    //     return $columns;
    // }

    add_filter("manage_tour_posts_columns", "createTourColumns");
    add_action("manage_tour_posts_custom_column", "addCustomTourColumns", 10, 2);
    // add_filter("manage_edit-tour_sortable_columns", "setCustomTourColumnsSortable");
}

add_action("init", "allrooms_post_types");

function createShowSearchTitle($post_id){
    if(!is_admin()) return;
    if($_POST["post_type"] === "show"){

        $artistField = "field_60d78011cfaf7";
        $venueField = "field_60ce8a7ea5b86";
        $venueNameField = "field_60ce8aa2a5b87";
        $dateField = "field_60ce88b3d4005";
        $dateFromField = "field_60ce890bd4006";
        $cityField = "field_60ce8b5ca5b8b";
        $countryField = "field_60ce8b71a5b8c";
        
        $eventDate = new DateTime($_POST["acf"][$dateField][$dateFromField]);
        $relatedArtist = get_the_title(intval($_POST["acf"][$artistField][0]));
        $location = $_POST["acf"][$cityField] . ", " . $_POST["acf"][$countryField];
        $venue = $_POST["acf"][$venueField][$venueNameField];
        
        $old_title = get_the_title($post_id);
        $new_title =  $eventDate->format("D, d.m.Y") . " - " . $relatedArtist . " in ". $location . " @ " . $venue; 

        if($old_title != $new_title){
            wp_update_post([
                "ID" => $post_id,
                "post_title" => $new_title
            ]);
        } 
    }
}

add_action("acf/save_post", "createShowSearchTitle");

function setLastTourDatePostMeta($post_id){
    if(!is_admin()) return;
    if($_POST["post_type"] === "tour"){
        $relatedShowsField = "field_60d79d46c8bac";
        $relatedShows = $_POST["acf"][$relatedShowsField];
        $showDates = [];
        foreach($relatedShows as $show){
            array_push($showDates, get_field("date_from", intval($show)));
        }
        rsort($showDates);
        update_post_meta($post_id, "last_show", $showDates[0]);
    }
}
add_action("acf/save_post", "setLastTourDatePostMeta", 100);


function acfShowRelationshipQuery($args, $field, $post_id){
    $today = date("Ymd");
    $args["posts_per_page"] = 30;
    $args["meta_key"] = "date_from";
    $args["orderby"] = ["date_from" => "ASC"];
    $args["meta_query"] = [
            [
                "key" => "date_from",
                "compare" => ">=",
                "value" => $today,
                "type" => "numeric"
            ]
        ];

    return $args;
}
add_filter('acf/fields/relationship/query/name=related_shows', 'acfShowRelationshipQuery', 10, 3);

// CUSTOM SORTING FOR ADMIN BACKEND!!

function customPostsOrderby($query){
    if(!is_admin() || !$query->is_main_query()) return $query;
    
    if ( $query->query["post_type"] === "artist" && "title" === $query->get('orderby')) {
        $query->set("meta_key", "sort_name");
        $query->set("orderby", "meta_value");
    }
    
    if ( $query->query["post_type"] === "show" ) {
        if("venue" === $query->get( 'orderby')) {
            $query->set("meta_key", "venue_name");
            $query->set("orderby", "meta_value");
        }
        
        if("city" === $query->get( 'orderby')) {
            $query->set("meta_key", "city");
            $query->set("orderby", "meta_value");
        }
        
        if("event_date" === $query->get("orderby")){
            $query->set("meta_key", "date_from");
            $query->set("orderby", "meta_value_num");
        }
    }

    // if ( $query->query["post_type"] === "news" && "artist" === $query->get("orderby") ){
    //     $query->set("meta_key", "related_artist");
    //     $query->set("orderby", "meta_value");
    //     $query->set("value", '"' . get_the_ID() . '"');
    // }
}
add_action("pre_get_posts", "customPostsOrderby");

function makeArtistsSortable($post_id, $post_after, $post_before){
    if(!is_admin()) return;
    if($post_before->post_type === "artist"){
        if(strpos($post_after->post_title, "The") === 0) {
            $sortName = ltrim($post_after->post_title, "The ");
            update_post_meta($post_id, "sort_name", strtolower($sortName));
        } else {
            update_post_meta($post_id, "sort_name", strtolower($post_after->post_title));
        }
    }
}
add_action("post_updated", "makeArtistsSortable", 10, 3);