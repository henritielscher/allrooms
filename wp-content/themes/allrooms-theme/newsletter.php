<div class="newsletter-form-modal">
  <div class="modal-container">
    <div class="close-modal">
      <i class="fas fa-times"></i>
    </div>
    <h1 class="modal-title">All Rooms Newsletter</h1>
    <form class="modal-form newsletter-form" name="newsletter-form" novalidate role="form">
      <div class="form-group newstype">
        <div>
          <input type="radio" name="newstype" id="private" value="privat" checked aria-checked="true"/>
          <label for="private"><?= langOpts("Privat", "Private") ?></label>
        </div>
        <div>
          <input
          type="radio"
          name="newstype"
          id="business"
          value="business"
        />
        <label for="business">Business</label>
        </div>
        <span class="newstype-error error-msg"></span>  
      </div>
      <div class="form-group email">
        <label for="newsletter-email">E-Mail <span aria-hidden="true">*</span></label>
        <input type="email" name="newsletter-email" id="newsletter-email" required aria-required="true" />
        <span class="email-error error-msg"></span>
      </div>
      <div class="form-group additional-input city">
          <label for="city"><?= langOpts("Stadt", "City") ?></label>
          <input type="text" name="city" id="city" placeholder="<?= langOpts("Deine Stadt (für gezielte Newsletter)", "Your City (for specific notifications)") ?>">
      </div>
      <div class="form-group additional-input company">
        <label for="company"><?= langOpts("Unternehmen", "Company") ?></label>
        <input type="text" name="company" id="company" placeholder="<?= langOpts("Name des Unternehmens", "Company Name") ?>">
      </div>
      <button type="submit" class="newsletter-form-submit submit-button" role="button"><?= langOpts("Abonnieren", "Subscribe") ?></button>
      <p><?= langOpts("Alle mit <span>*</span> gekennzeichneten Felder müssen ausgefüllt werden.", "All fields marked with <span>*</span> are required.") ?></p>
    </form>
  </div>
</div>