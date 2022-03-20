<?php 

add_action("rest_api_init", "allroomsRegisterArtistFetch");

function allroomsRegisterArtistFetch(){
    register_rest_route("allrooms", "artists", [
        "methods" => WP_REST_SERVER::READABLE,
        "callback" => "artistFetchResults"
    ]);
};

function artistFetchResults(){
    $mainQuery = new WP_Query([
        "post_type" => "artist",
        "posts_per_page" => -1,
        "order" => "ASC",
        "orderby" => "meta_value",
        "meta_key" => "sort_name",
        "meta_query" => [
      [
        "key" => "non_agency",
        "compare" => "!=",
        "value" => true
      ]
    ]
    ]);

    $results = [];

    while($mainQuery->have_posts()){
        $mainQuery->the_post();
        $tours = get_posts([
          "post_type" => "tour",
          "numberposts" => -1,
          "meta_query" => [
            [
              "key" => "related_artist",
              "compare" => "LIKE",
              "value" => '"' . get_the_ID() . '"'
            ]
          ]
        ]);
        $isOnTour = isArtistOnTour($tours);
        array_push($results, [
            "name" => get_the_title(),
            "image" => get_the_post_thumbnail_url(NULL, "previewArtist"),
            "isOnTour" => $isOnTour,
            "link" => get_permalink(),
            "lazyImage" => get_the_post_thumbnail_url(NULL, "superlazy")
        ]);
    }

    return $results;
}