<?php

if (!isset($_COOKIE["lang"])) {
  setcookie("lang", "de", time() + 86400 * 30, "/");
}
get_header();

$socialLinks = [
  "website" => "fas fa-globe",
  "spotify" => "fab fa-spotify",
  "facebook" => "fab fa-facebook",
  "instagram" => "fab fa-instagram",
  "youtube" => "fab fa-youtube"
];
$heroUrl = get_field("hero")["sizes"]["superlazy"];

$agents = [];
$roles = get_field("services");

foreach ($roles as $role => $agent) {
  if (is_iterable($agent)) {
    foreach ($agent as $id) {
      if (!key_exists($id, $agents)) {
        $agents[$id] = [];
      }
      array_push($agents[$id], $role);
    }
  }
}
?>

<div class="artist-container">
  <?php if (get_field("non_agency")) : ?>
    <div class="no-content">
      <h3>Sorry!</h3>
      <h4>
        <?= langOpts("Diese Künstler:In ist nicht in unserem Roster.", "The artist you are looking for is not in our roster.") ?>
      </h4>
    </div>
  <?php else : ?>
    <div style="background-image: url(<?= $heroUrl ?>);" class="artist-hero lazy" data-src="<?= get_field("hero")["sizes"]["heroBannerLarge"] ?>">
      <h1 class="artist-hero-name"><?= get_the_title() ?></h1>
    </div>
    <section class="artist-links-container">
      <div class="link-wrapper">
        <a href="/artists" class="back-button"><i class="fas fa-caret-left"></i>
          <?php echo langOpts("Zurück", "Back"); ?>
        </a>
        <div class="artist-links">
          <h5>Links:</h5>
          <?php
          foreach ($socialLinks as $link => $symbol) : if (get_field("links_$link")) : ?>
              <a href="<?= esc_url(get_field("links_$link")) ?>" target="_blank"><i class="<?= esc_attr($symbol) ?>"></i></a>
          <?php endif;
          endforeach; ?>
        </div>
      </div>
    </section>
    <div class="artist-content-container">


      <?php
      $videos = get_field("press_youtube");
      $photos = get_field("press_photos");
      ?>

      <section class="artist-press-text-container section-container">
        <div class="artist-press-title section-title">Bio</div>
        <article class="press-text">
          <!-- Paragraphs go here -->
          <?php
          echo langOpts(get_field("press_bio_de"), get_field("press_bio_en")); ?>
        </article>
      </section>
      <section class="artist-media-container section-container">
        <div class="artist-media-title section-title">Media</div>
        <!-- SWIPER!! -->
        <div class="swiper-container swiper-media">
          <div class="swiper-wrapper">
            <?php foreach ($videos as $video) {
              if (!empty($video)) { ?>
                <div class="swiper-slide">
                  <?php echo $video; ?>
                </div>

              <?php }
            }
            foreach ($photos as $photo) {
              if (!empty($photo)) { ?>
                <div class="swiper-slide">
                  <img src="<?= $photo["sizes"]["superlazy"] ?>" alt="" class="swiper-image" data-src="<?= $photo["sizes"]["landingNews"] ?>" />
                </div>
            <?php }
            }
            ?>
          </div>
          <div class="media-pagination swiper-pagination-controls"></div>
        </div>
        <div class="media-swiper-buttons">
          <div class="swiper-button-prev media-artist">
            <i class="fas fa-chevron-left"></i>
          </div>
          <div class="swiper-button-next media-artist">
            <i class="fas fa-chevron-right"></i>
          </div>
        </div>
      </section>
      <?php
      $today = date("Ymd");
      $tours = new WP_Query(
        [
          "posts_per_page" => -1,
          "post_type" => "tour",
          "meta_query" => [
            [
              "key" => "related_artist",
              "compare" => "LIKE",
              "value" => '"' . get_the_ID() . '"'
            ]
          ]
        ]
      );

      // Make array with sorted bands and their tours
      $currentTours = [];

      foreach ($tours->posts as $tour) {
        $shows = get_field("related_shows", $tour);
        // Get latest show date to see if tour has to be displayed
        $showDates = [];
        foreach ($shows as $show) {
          array_push($showDates, get_field("date_from", $show));
        }
        rsort($showDates);
        $last_show = $showDates[0];
        if ($last_show >= $today) {
          array_push($currentTours, $tour);
        }
      }

      $tourCount = 0;

      if (!$currentTours) { ?>
        <section class="artist-live-container section-container">
          <div class="artist-live-title section-title">Live</div>
          <div class="no-live-dates">
            <p>
              <?php echo langOpts("Leider gibt es keine aktuellen Livedates.", "There are currently no upcoming live dates."); ?>
            </p>
          </div>
        </section>
      <?php } else { ?>
        <section class="artist-live-container section-container">
          <div class="artist-live-title section-title">Live</div>
          <div class="live-dates-container">
            <?php foreach ($currentTours as $tour) { ?>
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
                  <h1 class="live-title"><?= esc_html(get_the_title($tour)) ?></h1>
                  <div class="live-line"></div>
                </div>
                <table id="data-table-<?= $tourCount ?>" class="display responsive nowrap hover live-dates" width="100%" data-type="artist">
                  <?php $tourCount++; ?>
                  <thead>
                    <tr class="table-header">
                      <th>Date</th>
                      <th>City</th>
                      <th>Location</th>
                      <th style="text-align: center;">Info</th>
                      <th style="text-align: center;">Links</th>
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
        </section>
      <?php } ?>
    </div>
  <?php endif; ?>

  <section class="section-container artist-agents ">
    <?php foreach ($agents as $agent => $services) : ?>
      <div class="artist-agent">

        <div class="agent-info">
          <h5 class="agent-role">
            <?php echo implode(" / ", $services); ?>
          </h5>
          <h6 class="agents-name"><?php echo esc_html(get_the_title($agent)); ?></h6>
          <a href="mailto:<?= esc_attr(get_field("email", $agent)) ?>" class="agent-email"><?= get_field("email", $agent) ?></a>
        </div>
      </div>
    <?php
    endforeach;
    ?>
  </section>
</div>

<?php
get_footer();
?>