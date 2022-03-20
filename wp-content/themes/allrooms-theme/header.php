<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body>
  <div class="overflow-wrapper">

    <header class="nav">
      <nav class="navbar" role="navigation">
        <div class="navbar-lang-container">
          <div class="navbar-lang <?php if ($_COOKIE["lang"] == "en") echo "toggle-lang"; ?>">
            <div class="navbar-lang-choice <?php if ($_COOKIE["lang"] == "de" || !$_COOKIE["lang"]) echo "active-lang"; ?>" data-lang="de">DE</div>
            <div class="navbar-lang-choice <?php if ($_COOKIE["lang"] == "en") echo "active-lang"; ?>" data-lang="en">EN</div>
            <div class="navbar-lang-selector"></div>
          </div>
        </div>

        <div class="navbar-center">
          <ul class="navbar-left">
            <li class="navbar-link <?php if (is_post_type_archive('news')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/news")) ?>" alt="News Page">News</a></li>
            <li class="navbar-link <?php if (is_post_type_archive('artist') || is_singular("artist")) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/artists")) ?>">Artists</a></li>
            <li class="navbar-link <?php if (is_post_type_archive('tour')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/tours")) ?>">On Tour</a></li>
          </ul>
          <a href="/">
            <div class="navbar-logo">
              <img src="<?php echo get_theme_file_uri("public/assets/allrooms.png") ?>" alt="" class="navbar-logo-top" aria-hidden="true">
              <img src="<?php echo get_theme_file_uri("public/assets/agency.png") ?>" alt="" class="navbar-logo-bottom" aria-hidden="true">
            </div>
          </a>
          <ul class="navbar-right">
            <li class="navbar-link <?php if (is_page('berlin')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/berlin-shows")) ?>">Berlin</a></li>
            <li class="navbar-link <?php if (is_page('services')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/services")) ?>">Services</a></li>
            <li class="navbar-link <?php if (is_page('contact')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/contact")) ?>">Contact</a></li>
          </ul>
        </div>
        <div class="navbar-social-media">
          <a href="https://www.instagram.com/allroomsagency/" target="_blank"><i class="fab fa-instagram" aria-hidden="true"></i></a>
          <a href="https://www.facebook.com/allroomsagency/" target="_blank"><i class="fab fa-facebook" aria-hidden="true"></i></a>
          <a href="https://open.spotify.com/user/gncdnjees7r0yy526urkgr343?si=ae42d9414de04158" target="_blank"><i class="fab fa-spotify" aria-hidden="true"></i></a>
        </div>
        <div class="navbar-burger">
          <div class="line1"></div>
          <div class="line2"></div>
          <div class="line3"></div>
        </div>
        <div class="navbar-mobile">
          <div class="navbar-mobile-container">
            <ul class="navbar-mobile-links">
              <li class="navbar-mobile-link <?php if (is_post_type_archive('news')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/news")) ?>">News</a></li>
              <li class="navbar-mobile-link <?php if (is_post_type_archive('artist')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/artists")) ?>">Artists</a></li>
              <li class="navbar-mobile-link <?php if (is_post_type_archive('tour')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/tours")) ?>">On Tour</a></li>
              <li class="navbar-mobile-link <?php if (is_page('berlin')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/berlin-shows")) ?>">Berlin</a></li>
              <li class="navbar-mobile-link <?php if (is_page('services')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/services")) ?>">Services</a></li>
              <li class="navbar-mobile-link <?php if (is_page('contact')) echo 'navbar-link--active'; ?>"><a href="<?= esc_url(site_url("/contact")) ?>">Contact</a></li>
            </ul>
            <div class="navbar-mobile-social-media">
              <a href="https://www.facebook.com/allroomsagency/" target="_blank"><i class="fab fa-facebook" aria-hidden="true"></i></a>
              <a href="https://www.instagram.com/allroomsagency/" target="_blank"><i class="fab fa-instagram" aria-hidden="true"></i></a>
              <a href="https://open.spotify.com/user/gncdnjees7r0yy526urkgr343?si=ae42d9414de04158" target="_blank"><i class="fab fa-spotify" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
      </nav>

    </header>
    <main class="main-wrapper">