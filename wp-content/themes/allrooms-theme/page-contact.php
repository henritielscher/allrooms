<?php
if (!isset($_COOKIE["lang"])) {
  setcookie("lang", "de", time() + 86400 * 30, "/");
}
get_header();
?>

<section class="contact-container section-container">
  <div class="contact-content">
    <div class="contact-left">
      <div class="contact-box">
        <h3 class="contact__title"><?= langOpts("Kontakt", "Contact") ?></h3>
        <p class="contact__address">
          ALL ROOMS UG <?= langOpts("(haftungsbeschränkt)", "(limited liability)") ?><br>
          Helmholtzstraße 26<br>
          12459 Berlin
        </p>
        <p class="contact__email">E-Mail: <a href="mailto:info@allrooms-agency.com">info@allrooms-agency.com</a></p>
        <p class="contact__phone">
          <?php echo langOpts(
            "Telefon: +49 (0) 30 - 53218819<br><span>(Montag bis Freitag, 10:00 - 15:00)</span>",
            "Phone: +49 (0) 30 - 53218819<br><span>(Monday to Friday, 10:00 - 15:00)</span>"
          ) ?>
      </div>
      <div class="contact-agents">
        <?php
        $agents = new WP_Query(
          [
            "post_type" => "agent",
            "posts_per_page" => -1,
            "oderby" => "post_title",
            "order" => "ASC"
          ]
        );

        if ($agents->have_posts()) : while ($agents->have_posts()) : $agents->the_post(); ?>
            <article class="agent-item">
              <h4 class="agent-name"><?= the_title() ?></h4>
              <div class="agent-content">

                <div class="agent-infos">
                  <p class="agent-infos__role">
                    <?php
                    if (get_the_title() == "Stefan Schumacher") {
                      echo langOpts(
                        "Geschäftsführer<br> Booking, Marketing, Management",
                        "CEO<br> Booking, Marketing, Management"
                      );
                    }
                    if (get_the_title() == "Camilo Betancourt") {
                      echo "Public Relations, Booking";
                    }
                    ?>
                  </p>
                  <p class="agent-infos__contact">
                    <?= langOpts("Kontakt:", "Contact:") ?><br>
                    <a href="mailto:<?= get_field("email") ?>"><?= the_field("email") ?></a>
                  </p>
                </div>
              </div>
            </article>
        <?php
          endwhile;
        endif;
        ?>
      </div>
    </div>

    <!-- CONTACT FORM -->
    <form class="contact-form" novalidate role="form">

      <div class="form-group">
        <label for="name">Name <span>*</span></label>
        <input type="text" name="name" id="name" required placeholder="<?= langOpts("Name", "Your Name") ?>" autofocus>
        <span class="name-error error-msg"></span>
      </div>
      <div class="form-group">
        <label for="email"><?= langOpts("E-Mail Adresse <span>*</span>", "E-Mail Address <span>*</span>") ?></label>
        <input type="email" name="email" id="email" required placeholder="<?= langOpts("E-Mail-Adresse", "E-Mail Address") ?>">
        <span class="email-error error-msg"></span>
      </div>
      <div class="form-group">
        <label for="subject"><?= langOpts("Betreff <span>*</span>", "Subject <span>*</span>") ?></label>
        <select name="subject" id="subject" required>
          <option value="allgemein"><?= langOpts("Allgemein", "General") ?></option>
          <option value="booking">Booking</option>
          <option value="presse"><?= langOpts("Presse", "Press") ?></option>
          <option value="anderes"><?= langOpts("Anderes", "Other") ?></option>
        </select>
        <span class="subject-error error-msg"></span>
      </div>
      <div class="form-group">
        <label for="message"><?= langOpts("Nachricht <span>*</span>", "Message <span>*</span>") ?></label>
        <textarea name="message" id="message" cols="30" rows="10" placeholder="<?= langOpts("Deine Nachricht", "Your Message") ?>" required></textarea>
        <span class="message-error error-msg"></span>
      </div>
      <div class="form-group">
        <input type="checkbox" name="privacy" id="privacy" required><span> *</span>
        <label for="privacy">
          <?php
          echo langOpts(
            "Hiermit erkläre ich mich mit der <a href=''>Datenschutzvereinbarung</a> einverstanden.",
            "I hereby agree with the <a href=''> Privacy Policy</a>."
          );
          ?>
        </label>
      </div>
      <span class="privacy-error error-msg"></span>
      <button type="submit" class="contact-form-submit submit-button" role="button"><?= langOpts("Senden", "Send") ?></button>
      <p><?= langOpts("Alle mit <span>*</span> gekennzeichneten Felder müssen ausgefüllt werden.", "All fields marked with <span>*</span> are required.") ?></p>
    </form>
  </div>
</section>

<?php
get_footer();
?>