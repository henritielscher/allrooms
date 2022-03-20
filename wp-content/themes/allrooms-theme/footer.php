</main>
<footer class="footer-wrapper" role="contentinfo">
  <section class="footer-container">
    <div class="footer-top">
      <nav class="footer-nav-left">
        <ul>
          <li><a href="">
            <?php echo langOpts("Impressum", "Impress"); ?>
          </a></li>
          <li><a href="">
            <?php echo langOpts("Datenschutz", "Privacy"); ?>
          </a></li>
        </ul>
      </nav>
      <div class="footer-newsletter">
        
        <?php include("newsletter.php"); ?>

        <h3 class="newsletter-title">
          <?php echo langOpts("Keine News Mehr Verpassen?", "Missing Out on our News?"); ?>
        </h3>
        <p class="newsletter-text">
          <?php echo langOpts("Abonniere einfach unseren ALL ROOMS Newsletter.", "Just subscribe to our ALL ROOMS Newsletter."); ?>
        </p>
        <button class="open-newsletter-modal standard-button">
          <?php echo langOpts("Abonnieren", "Subscribe"); ?>
        </button>
      </div>
      <nav class="footer-nav-right">
        <h6>
          <?php echo langOpts("ALL ROOMS auf Social Media", "ALL ROOMS on Social Media"); ?>
        </h6>
        <div class="footer-social-media-links">
          <a href="https://www.instagram.com/allroomsagency/" target="_blank"
            ><i class="fab fa-instagram" aria-hidden="true"></i
          ></a>
          <a href="https://www.facebook.com/allroomsagency/" target="_blank"
            ><i class="fab fa-facebook" aria-hidden="true"></i
          ></a>
          <a href="https://open.spotify.com/user/gncdnjees7r0yy526urkgr343?si=ae42d9414de04158" target="_blank"
            ><i class="fab fa-spotify" aria-hidden="true"></i
          ></a>
        </div>
      </nav>
    </div>

    <p class="footer-bottom">
      &copy;&nbsp;<span class="current-year"><?= date("Y") ?></span>&nbsp;ALL ROOMS
    </p>
  </section>
</footer>
</div>

<?php wp_footer(); ?>


</body>
</html>