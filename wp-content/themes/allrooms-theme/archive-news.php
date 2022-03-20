<?php

if (!isset($_COOKIE["lang"])) {
  setcookie("lang", "de", time() + 86400 * 30, "/");
}

get_header();
global $wp_query;
// var_dump($wp_query);
?>

<div class="news-container section-container">
  <nav class="pagination-container">
    <?php echo paginate_links(
      [
        "total" => $wp_query->max_num_pages,
        "prev_next" => false
      ]
    ); ?>
  </nav>
  <section class="news-box">


    <?php
    $tourCount = 0;
    $socialLinks = [
      "website" => "fas fa-globe",
      "spotify" => "fab fa-spotify",
      "facebook" => "fab fa-facebook",
      "instagram" => "fab fa-instagram",
      "youtube" => "fab fa-youtube"
    ];

    while (have_posts()) {
      the_post();
      $relatedArtist = get_field("related_artist")[0];
      $isOnTour = !empty(get_field("related_tours", $relatedArtist));
    ?>


      <div class="news-item" role="article">
        <header class="news-item__header">
          <div class="news-item__header-info">
            <div class="news-item__header-info__image">
              <?php
              if ($isOnTour) echo "<div class='on-tour'>On Tour</div>";
              ?>

              <!-- square image -->
              <img src="<?= get_the_post_thumbnail_url(NULL, "superlazy") ?>" data-src="<?= get_the_post_thumbnail_url(NULL, "newsPage") ?>" alt="" />
            </div>
            <h5 class="news-item__band"><?php echo get_the_title($relatedArtist->ID) ?></h5>

        </header>
        <div class="news-item__content">
          <div class="content-header">
            <h6 class="content-date"><?= get_the_date("D, d.m.Y") ?></h6>
            <h4 class="content-headline"><?= get_the_title() ?></h4>
          </div>
          <article class="content-text">

            <!-- <p>news-text</p> -->
            <?php echo the_content(); ?>
            <!-- falls es eine  gefeaturete Tour gibt -->
            <?php
            if (get_field("is_tourpost")) :
              $featuredTour = get_field("featured_tour");
              foreach ($featuredTour as $tour) :

            ?>
                <div class="artist-live-container">
                  <?php if (get_field("presented", $tour)) { ?>
                    <div class="presenter">
                      <?php
                      echo langOpts("Präsentiert von ", "Presented by ");
                      echo removePTag("presented", $tour);
                      ?>
                    </div>
                  <?php } ?>
                  <div class="live-header">
                    <h1 class="live-title"><?= get_the_title($tour); ?></h1>
                    <div class="live-line"></div>
                  </div>
                  <table class="display responsive nowrap hover live-dates" id="tour-table-<?= $tourCount ?>" width="100%" data-type="news">

                    <?php $tourCount++; ?>

                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>City</th>
                        <th>Location</th>
                        <th style="text-align: center;">Links</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $shows = get_field("related_shows", $tour);
                      usort($shows, function ($a, $b) {
                        $a_date = get_post_meta($a->ID, "date_from", true);
                        $b_date = get_post_meta($b->ID, "date_from", true);
                        return $a_date <=> $b_date;
                      });

                      foreach ($shows as $show) :
                        $showDate = manageDate($show);
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
                          <td class="date-links"><?= $dateLinks ?>
                        </tr>

                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
            <?php
              endforeach;
            endif;
            wp_reset_postdata();
            ?>

          </article>
          <div class="link-box-small">
            <!-- Falls Band im Roster -->
            <a href="<?= esc_url(the_permalink($relatedArtist->ID)) ?>" class="page-button"><?= langOpts("Künstlerseite", "Artist Page") ?></a>

            <div class="link-box-small__artist-links">
              <?php
              foreach ($socialLinks as $link => $symbol) : if (get_field("links_$link", $relatedArtist->ID)) : ?>
                  <a href="<?= get_field("links_$link", $relatedArtist->ID) ?>" target="_blank"><i class="<?= $symbol ?>" aria-hidden="true"></i></a>
              <?php endif;
              endforeach; ?>
            </div>
          </div>
        </div>

      </div>
    <?php } ?>

    <nav class="pagination-container">
      <?php echo paginate_links(
        [
          "total" => $wp_query->max_num_pages,
          "prev_next" => false
        ]
      ); ?>
    </nav>
  </section>
</div>

<?php
wp_reset_postdata();
get_footer(); ?>