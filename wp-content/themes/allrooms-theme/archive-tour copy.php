<!-- pre_get_posts Hook damit nur aktuelle Touren angezeigt werden -->
<?php 
if(!isset($_COOKIE["lang"])){
  setcookie("lang", "de", time() + 86400*30, "/");
}


get_header();
// der Query muss so verändert werden, dass die Touren 

$today = date("Ymd");
$tourCount = 0;
$artists = new WP_Query(
  [
    "post_type" => "artist",
    "posts_per_page" => -1,
    "order" => "ASC",
    "orderby" => "meta_value",
    "meta_key" => "sort_name",
    "meta_query" => [
      [
        "key" => "related_tours",
        "compare" => "!=",
        "value" => ""
      ]
    ]
  ]
);
// var_dump($today);
$currentTouringArtists = [];

?>

<section class="tours-container section-container">
  <div class="tours-content">
    
    <?php 
    if($artists->have_posts()) :
      while($artists->have_posts()) :
        $artists->the_post(); 
        ?>

        <article class="touring-artist hidden">
        <div class="dropdown-tour">
          <!-- Small Artist Picture -->
          <img src="<?= esc_url(get_the_post_thumbnail_url(NULL, "previewArtist")) ?>" alt="" width="80" height="80">
          <h5><?= the_title(); ?></h5>
        </div>
        <div class="artist-live-container">

        <?php 
        $tours = get_field("related_tours");
        foreach($tours as $tour){ ?>
        <div class="live-section">
          <?php if(get_field("presented", $tour)){ ?>
            <div class="presenter">
              <?php 
              echo langOpts("Präsentiert von ", "Presented by ");
              echo removePTag("presented", $tour);
              ?>
            </div>
          <?php } ?> 
          <div class="live-header">
            
            <h1 class="live-title"><?= get_the_title($tour) ?></h1>
            <div class="live-line"></div>
          </div>
          <table
          class="display responsive nowrap hover live-dates"
          id="tour-table-<?= $tourCount ?>"
          width="100%"
          data-type="tour"
          >
        <?php $tourCount++; ?>
            <thead>
              <tr>
                <th>Date</th>
                <th>City</th>
                <th>Location</th>
                <th style="text-align: center">Info</th>
                <th style="text-align: center">Links</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            $shows = get_field("related_shows", $tour);
            $futureShows = [];
            $today = date("Ymd");
            foreach($shows as $show){
              if(get_post_meta($show->ID, "date_from", true) >= $today){
                array_push($futureShows, $show);
              }
            }
            usort($futureShows, function($a, $b){
              $a_date = get_post_meta($a->ID, "date_from", true);
              $b_date = get_post_meta($b->ID, "date_from", true);
              return $a_date <=> $b_date;
            });


            foreach($futureShows as $show) {
              $showDate = manageDate($show);
              $status = manageStatus($show);
              $dateLinks = manageDateLinks($show);
              $noTickets = get_field("status_is_sold_out", $show) == true || get_field("status_is_cancelled", $show) == true;
              ?>
              <tr 
                <?php 
                  if($noTickets) echo "class='no-ticket'";            
                ?>
              >
                  <td class="date"><?= $showDate ?></td>
                  <td class="city"><?= get_field("city", $show) ?>, <?= get_field("country", $show) ?></td>
                  <td class="venue">
                    <a href="<?= get_field("location_venue_link", $show) ?>" target="_blank"><?= get_field("venue_name", $show) ?></a>
                  </td>
                  <td class="info-message"><?= $status ?></td>
                  <td class="date-links"><?= $dateLinks ?></td>
                </tr>
              <?php } ?> 
            </tbody>
          </table>
          </div>
          <?php } ?>
          </div>
      </article>


    <?php 
      endwhile;
    endif; 
    ?>
  </div>
</section>

<?php 
get_footer();
?>