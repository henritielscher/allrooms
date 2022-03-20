<?php 
if(!isset($_COOKIE["lang"])){
  setcookie("lang", "de", time() + 86400*30, "/");
}

get_header(); ?>

<div class="artists-container section-container">
  <section class="all-artists">
    <div class="artist-controls">
      <div data-sort="ontour">
        <button class="standard-button filter-btn">on tour</button>
      </div>
      <div class="filter-active filter-mode" data-sort="grid">
        <i class="fas fa-th-large"></i>
      </div>
      <div class="filter-mode" data-sort="list">
        <i class="fas fa-bars"></i>
      </div>
    </div>
    <div class="artist-grid">
    

    </div>
  </section>
</div>

<?php 
get_footer();
?>