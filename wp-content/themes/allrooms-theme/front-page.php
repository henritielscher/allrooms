<?php

if (!isset($_COOKIE["lang"])) {
  setcookie("lang", "de", time() + 86400 * 30, "/");
}

get_header();

$news = new WP_Query(
  [
    "post_type" => "news",
    "posts_per_page" => 5,
    "orderby" => "date",
    "order" => "DESC"
  ]
);
$tableCount = 0;
?>

<div class="landing-container">

  <?php if ($news->found_posts > 0) { ?>
    <section class="news-landing-container section-container">
      <div class="swiper-container swiper-news">
        <div class="landing-frame">
          <h1>
            <span id="ne" class="landing-section-title">NE</span><span id="ws" class="landing-section-title">WS</span>
          </h1>
          <h1 class="landing-mobile-title">NEWS</h1>
        </div>
        <div class="swiper-wrapper">
          <?php
          while ($news->have_posts()) {
            $news->the_post();
            $relatedArtist = get_field("related_artist");
          ?>

            <div class="swiper-slide">
              <div class="news-overlay overlay">
                <div class="news-overlay-header"><?php
                                                  foreach ($relatedArtist as $artist) {
                                                    echo get_the_title($artist);
                                                  } ?></div>
                <div class="news-overlay-title"><?= get_the_title() ?></div>
              </div>
              <img src="<?= get_the_post_thumbnail_url(NULL, "superlazy") ?>" data-src="<?= get_the_post_thumbnail_url(NULL, "landingNews") ?>" />
            </div>


          <?php }
          wp_reset_postdata();
          ?>

        </div>
        <div class="swiper-button-prev news">
          <i class="fas fa-chevron-left"></i>
        </div>
        <div class="swiper-button-next news">
          <i class="fas fa-chevron-right"></i>
        </div>
      </div>
      <div class="news-controls swiper-pagination-controls">
        <div class="nav-rects"></div>

        <a href="<?= esc_url(site_url("/news")) ?>" class="page-button">
          <!-- <i class="fas fa-caret-right"></i> -->
          <?php echo langOpts("Alle News", "All News"); ?>
        </a>
      </div>
    </section>
  <?php }  ?>

  <section class="artists-landing-container section-container">
    <div class="swiper-container swiper-artists">
      <div class="landing-frame">
        <h1>
          <span id="art" class="landing-section-title">art</span><span id="ists" class="landing-section-title">ists</span>
        </h1>
        <h1 class="landing-mobile-title">ARTISTS</h1>
      </div>
      <div class="swiper-wrapper">
        <?php
        $artists = new WP_Query(
          [
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
              ],
            ]
          ]
        );

        while ($artists->have_posts()) {
          $artists->the_post();
          // GET ALL TOURS RELATED TO THE ARTIST
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
        ?>
          <div class="swiper-slide">
            <div class="artist-overlay overlay">
              <?php if ($isOnTour) echo "<div class='on-tour'>ON TOUR</div>"; ?>
              <div class="artist-name">
                <h3><?= get_the_title() ?></h3>
              </div>
            </div>
            <img src="<?= get_the_post_thumbnail_url(NULL, "superlazy") ?>" data-src="<?= get_the_post_thumbnail_url(NULL, "landingArtist") ?>" alt="" />
          </div>

        <?php }
        wp_reset_postdata();
        ?>

      </div>
      <div class="swiper-button-prev artists">
        <i class="fas fa-chevron-left"></i>
      </div>
      <div class="swiper-button-next artists">
        <i class="fas fa-chevron-right"></i>
      </div>
    </div>
    <a href="<?= esc_url(site_url("/artists")) ?>" class="page-button">
      <!-- <i class="fas fa-caret-right"></i> -->
      <?php echo langOpts("Alle Künstler:Innen", "All Artists"); ?>
    </a>
  </section>

  <section class="services-landing-container section-container lazy">
    <div class="services-content">
      <div class="services-frame">
        <h2>
          <span>Booking.&nbsp;</span><span>Promotion.&nbsp;</span>
          <span>Management.&nbsp;</span>
        </h2>
      </div>

      <a href="<?= esc_url(site_url("/services")) ?>" class="page-button">
        <!-- <i class="fas fa-caret-right"></i> -->
        <?php echo langOpts("Unsere Services", "Our Services"); ?>
      </a>
    </div>
  </section>

  <section class="live-landing-container section-container">
    <div class="live-frame">
      <h1 class="landing-mobile-title">LIVE</h1>

      <h1>
        <span id="li" class="landing-section-title">LI</span><span id="ve" class="landing-section-title">VE</span>
      </h1>

      <div class="live-section">
        <div class="live-header">
          <div class="live-line"></div>
          <h1 class="live-title">upcoming</h1>
          <div class="live-line"></div>
        </div>
        <table id="data-table<?= $tableCount ?>" class="display responsive nowrap hover live-dates" width="100%" data-type="upcoming">
          <?php $tableCount++; ?>
          <thead>
            <tr class="table-header">
              <th>Date</th>
              <th>Band</th>
              <th>City</th>
              <th>Location</th>
              <th style="text-align: center;">Info</th>
              <th style="text-align: center;">Links</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $today = date("Ymd");
            $shows = new WP_Query(
              [
                "post_type" => "show",
                "posts_per_page" => 5,
                "meta_key" => "date_from",
                "orderby" => "meta_value_num",
                "order" => "ASC",
                "meta_query" => [
                  [
                    "key" => "date_from",
                    "compare" => ">=",
                    "value" => $today,
                    "type" => "numeric"
                  ],
                  [
                    "key" => "city",
                    "compare" => "NOT LIKE",
                    "value" => "Berlin",
                    "type" => "string"
                  ]
                ]
              ]
            );

            while ($shows->have_posts()) {
              $shows->the_post();

              $relatedArtists = get_field("related_artists");
              $showDate = manageDate(get_the_ID());
              $status = manageStatus(get_the_ID());
              $dateLinks = manageDateLinks(get_the_ID());
              $noTickets = get_field("status_is_sold_out") == true || get_field("status_is_cancelled") == true;

              foreach ($relatedArtists as $artist) { ?>
                <tr <?php
                    if ($noTickets) echo "class='no-ticket'";
                    ?>>
                  <td class="date"><?= $showDate ?></td>
                  <td class="band">
                    <a href="<?= the_permalink($artist->ID) ?>"><?php echo get_the_title($artist->ID) ?></a>

                  </td>
                  <td class="city"><?= get_field("city") ?>, <?= get_field("country") ?></td>
                  <td class="venue">
                    <a href="<?= get_field("location_venue_link", get_the_ID()) ?>" target="_blank"><?= get_field("venue_name", get_the_ID()) ?></a>
                  </td>
                  <td class="info-message">
                    <?php
                    if (empty($status)) {
                      echo "-";
                    } else {
                      echo $status;
                    }
                    ?>
                  </td>
                  <td class="date-links"><?= $dateLinks ?></td>
                </tr>

            <?php }
            };
            wp_reset_postdata();
            ?>


          </tbody>
        </table>

        <a href="/tours" class="page-button">

          <?php echo langOpts("Alle Shows", "All Shows"); ?>
        </a>
      </div>
      <div class="live-section">
        <div class="live-header">
          <div class="live-line"></div>
          <h1 class="live-title">berlin</h1>
          <div class="live-line"></div>
        </div>
        <table id="data-table-<?= $tableCount ?>" class="display responsive nowrap hover live-dates" width="100%" data-type="berlin">
          <thead>
            <tr class="table-header">
              <th>Date</th>
              <th>Band</th>
              <th>Support</th>
              <th>Location</th>
              <th style="text-align: center;">Info</th>
              <!-- <th>Info</th> -->
              <th style="text-align: center;">Links</th>
              <!-- <th>Links</th> -->
            </tr>
          </thead>
          <tbody>

            <?php
            $today = date("Ymd");
            $berlinShows = new WP_Query([
              "post_type" => "show",
              "posts_per_page" => 5,
              "meta_key" => "date_from",
              "orderby" => "meta_value_num",
              "order" => "ASC",
              "meta_query" => [
                [
                  "key" => "city",
                  "compare" => "LIKE",
                  "value" => "Berlin"
                ],
                [
                  "key" => "date_from",
                  "compare" => ">=",
                  "value" => $today,
                  "type" => "numeric"
                ]
              ]

            ]);

            while ($berlinShows->have_posts()) {
              $berlinShows->the_post();
              $status = manageStatus(get_the_ID());
              $showDate = manageDate(get_the_ID());
              $dateLinks = manageDateLinks(get_the_ID());
              $relatedArtists = get_field("related_artists");
              $noTickets = get_field("status_is_sold_out") == true || get_field("status_is_cancelled") == true;

              foreach ($relatedArtists as $artist) { ?>
                <tr <?php
                    if ($noTickets) echo "class='no-ticket'";
                    ?>>
                  <td class="date"><?= $showDate ?></td>
                  <td class="band">
                    <a href="<?= the_permalink($artist->ID) ?>"><?= get_the_title($artist->ID) ?></a>
                  </td>
                  <td class="support">
                    <?php if (get_field("venue_support")) {
                      echo the_field("venue_support");
                    } else {
                      echo "TBA";
                    }

                    ?>
                  </td>
                  <td class="venue">
                    <a href="<?= get_field("location_venue_link", get_the_ID()) ?>" target="_blank"><?= get_field("venue_name", get_the_ID()) ?></a>
                  </td>
                  <td class="info-message"><?= $status ?></td>
                  <td class="date-links"><?= $dateLinks ?></td>
                </tr>
            <?php
              }
            }
            wp_reset_postdata();
            ?>



          </tbody>
        </table>

        <a href="<?= esc_url(site_url("/berlin")) ?>" class="page-button">
          <?php echo langOpts("Alle Berlin Shows", "All Berlin Shows"); ?>
        </a>
      </div>
    </div>
  </section>
</div>
<?php get_footer();
?>