<?php 
if(!isset($_COOKIE["lang"])){
  setcookie("lang", "de", time() + 86400*30, "/");
}
get_header(); 
?>

<section class="section-container not-found-container">
  <h1><?= langOpts("Hoppla!", "Sorry!") ?></h1>
  <h4><?= langOpts("Die angefragte Seite konnte leider nicht gefunden werden.", "Unfortunately the requested page can't be found.") ?></h4>
  <p><?= langOpts("Vielleicht findest du über die Navigationsleiste, was du suchst...?!", "Maybe you can find it by using the page navigation...?!") ?></p>
</section>
<?php get_footer(); ?>