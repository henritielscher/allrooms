<!-- pre_get_posts Hook damit nur aktuelle Touren angezeigt werden -->
<?php
if (!isset($_COOKIE["lang"])) {
  setcookie("lang", "de", time() + 86400 * 30, "/");
}

get_header();

$today = date("Ymd");
$tourCount = 0;
$tours = $wp_query->posts;

// Make array with sorted bands and their tours
$currentTours = [];
foreach ($tours as $tour) {
  $shows = get_field("related_shows", $tour);
  $artists = get_field("related_artist", $tour);
  // Get latest show date to see if tour has to be displayed
  $showDates = [];
  foreach ($shows as $show) {
    array_push($showDates, get_field("date_from", $show));
  }
  rsort($showDates);
  $last_show = $showDates[0];

  if ($last_show >= $today) {
    // Iterate Artists and make 
    if (is_iterable($artists)) {
      foreach ($artists as $artist) {
        $sortName = get_post_meta($artist->ID, "sort_name", true);

        if (!array_key_exists($sortName, $currentTours)) $currentTours[$sortName]["artist"] = $artist;
        if (!array_key_exists("tours", $currentTours[$sortName])) $currentTours[$sortName]["tours"] = [];
        array_push($currentTours[$sortName]["tours"], $tour);
      }
    }
  }
}
// Sort by sort_name Keys
ksort($currentTours);
?>

<section class="tours-container section-container">
  <div class="tours-content">
    <?php
    if (empty($currentTours)) {
      echo "KEINE TOUR";
    } else {
      foreach ($currentTours as $entry) {
        $artist = $entry["artist"]->ID;
    ?>
        <article class="touring-artist hidden">
          <div class="dropdown-tour">
            <div class="dropdown-tour__links">
              <a href="<?= esc_url(get_the_permalink($artist)) ?>"><img src="<?= esc_url(get_the_post_thumbnail_url($artist, "previewArtist")) ?>" alt="" width="80" height="80" class="dropdown-tour__image"></a>
              <a href="<?= esc_url(get_the_permalink($artist)) ?>">
                <h5 class="dropdown-tour__artist-name"><?= get_the_title($artist) ?></h5>
              </a>
            </div>
            <i class="fas fa-caret-down dropdown-tour__toggle-button"></i>
          </div>
          <div class="artist-live-container">
            <?php
            foreach ($entry["tours"] as $tour) { ?>
              <div class="live-section">
                <?php if (get_field("presented", $tour)) { ?>
                  <div class="presenter">
                    <?php
                    echo langOpts("Präsentiert von ", "Presented by ");
                    echo removePTag("presented", $tour);
                    ?>
                  </div>
                <?php } ?>
                <div class="live-header">

                  <div class="live-line"></div>
                  <h1 class="live-title"><?= get_the_title($tour) ?></h1>
                  <div class="live-line"></div>
                </div>
                <table class="display responsive nowrap hover live-dates" id="tour-table-<?= $tourCount ?>" width="100%" data-type="tour">
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
                    foreach ($shows as $show) {
                      if (get_post_meta($show->ID, "date_from", true) >= $today) {
                        array_push($futureShows, $show);
                      }
                    }
                    usort($futureShows, function ($a, $b) {
                      $a_date = get_post_meta($a->ID, "date_from", true);
                      $b_date = get_post_meta($b->ID, "date_from", true);
                      return $a_date <=> $b_date;
                    });


                    foreach ($futureShows as $show) {
                      $showDate = manageDate($show);
                      $status = manageStatus($show);
                      $dateLinks = manageDateLinks($show);
                      $noTickets = get_field("status_is_sold_out", $show) == true || get_field("status_is_cancelled", $show) == true;
                    ?>
                      <tr <?php
                          if ($noTickets) echo "class='no-ticket'";
                          ?>>
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
      }
    }
    ?>
  </div>
</section>

<?php
get_footer();
?>