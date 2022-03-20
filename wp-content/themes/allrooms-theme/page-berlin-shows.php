<!-- pre_get_posts Hook damit nur aktuelle Berlin-Dates angezeigt werden -->
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

$today = date("Ymd");
$berlinShows = new WP_Query(
  [
    "paged" => get_query_var("paged", 1),
    "post_type" => "show",
    "posts_per_page" => 10,
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
  ]
);
?>

<section class="section-container berlin-container">
  <nav class="pagination-container">
    <?php echo paginate_links(
      [
        "total" => $berlinShows->max_num_pages,
        "prev_next" => false
      ]
    ); ?>
  </nav>



  <?php
  if ($berlinShows->have_posts()) : while ($berlinShows->have_posts()) :
      $berlinShows->the_post();
      $relatedArtists = get_field("related_artists");
      $showDate = new DateTime(get_field("date_from"));
      $status = manageStatus(get_the_ID());
      $statusMsg = langOpts(get_field("status_message_de"), get_field("status_message_en"));
      $dateLinks = manageDateLinks(get_the_ID());
  ?>


      <!-- sold out? -->
      <article class="berlin-date">
        <div class="berlin-date__header">
          <?php if (get_field("presented")) { ?>
            <div class="presenter">
              <?php
              echo langOpts("Präsentiert von ", "Presented by ");
              echo removePTag("presented", get_the_ID());
              ?>
            </div>
          <?php } ?>
          <span class="block-divider"></span>
          <span class="date"><?php echo $showDate->format("D, d.m.Y") ?></span>
          <?php
          $artistNames = [];
          foreach ($relatedArtists as $artist) {
            array_push($artistNames, get_the_title($artist));
          }
          ?>
          <span class="artist"><?= implode(", ", $artistNames) ?></span>
          <span class="info-message"><?= $status ?></span>
          <?php if (get_field("venue_support")) { ?>
            <span class="support">Support: <?= get_field("venue_support") ?></span>
          <?php } ?>
        </div>
        <div class="berlin-date__body 
    <?php if (get_field("status_is_sold_out") == true || get_field("status_is_cancelled") == true) echo "no-ticket" ?>">
          <img src="<?= esc_url(get_the_post_thumbnail_url($relatedArtists[0], "superlazy")) ?>" data-src="<?= esc_url(get_the_post_thumbnail_url($relatedArtists[0], "landingArtist")) ?>" />
          <p class="press-text"><?php echo langOpts(get_field("press_text_de"), get_field("press_text_en")); ?></p>
          <ul class="venue-info">
            <li class="venue-name">
              <a href="<?= esc_url(get_field("venue_venue_link")) ?>" target="_blank"><?= get_field("venue_name") ?></a>
            </li>
            <li><?= get_field("venue_address") ?></li>
            <li>
              <?= langOpts("Einlass", "Doors") . ": " . get_field("date_doors") . " &middot; " . langOpts("Beginn", "Begin") . ": " . get_field("date_begin") ?>
            </li>
            <li class="date-links"><?= $dateLinks ?></li>
            <?php
            if (get_field("status_message")) { ?>
              <li class="status">
                <div class="status-title">Info</div>
                <span class="message"><?= $statusMsg ?></span>
              </li>
            <?php } ?>
          </ul>

        </div>
        <div class="berlin-date__footer">
          <div class="social-links">
            <!-- Artist Link nur, falls Agency-Künstler -->
            <a href="<?= esc_url(get_the_permalink($relatedArtists[0])) ?>" class="page-button"><?= langOpts("Künstlerseite", "Artist Page") ?></a>
            <!-- Social Links -->
            <?php
            foreach ($socialLinks as $link => $symbol) : if (get_field("links_$link", $relatedArtists[0])) : ?>
                <a href="<?= get_field("links_$link", $relatedArtists[0]) ?>" target="_blank"><i class="<?= $symbol ?>"></i></a>
            <?php
              endif;
            endforeach;
            ?>
          </div>
        </div>
      </article>
    <?php
    endwhile;
  else : ?>
    <div class="no-content">
      <h3>Sorry!</h3>
      <h4>
        <?= langOpts("Derzeit ist keine Show in Berlin geplant.", "There are currently no shows in Berlin.") ?>
      </h4>
    </div>
  <?php
  endif;


  ?>


  <nav class="pagination-container">
    <?php echo paginate_links(
      [
        "total" => $berlinShows->max_num_pages,
        "prev_next" => false
      ]
    ); ?>
  </nav>

</section>


<?php
get_footer();
?>