<?php 
if(!isset($_COOKIE["lang"])){
  setcookie("lang", "de", time() + 86400*30, "/");
}
get_header();
?>

<header class="services__header">
  <p class="services__header__introduction">
    <?php echo langOpts(
      "ALL ROOMS ist eine Independent-Musikagentur mit Sitz in Berlin. Wir arbeiten
      genreübergreifend mit verschiedensten Künstler*innen zusammen, entwickeln
      Strategien, formen Visionen und setzen sie mithilfe eines tollen Netzwerks
      um. Gute Musik gehört vor das richtige Publikum - egal ob live oder digital
      - und wir machen es möglich.",
      "ALL ROOMS is an independent music agency based in Berlin. With a wide variety of artists, we work across different genres, develop strategies, shape visions and make them come real with the help of a great network. Good music needs to be heard by the right audience - whether live or digital - and we make that possible."
    ) 
    ;?>
  </p>
</header>
<section class="section-container services-container">
  <div class="services__topics">
    <article class="services__topic">
      <img src="<?= get_theme_file_uri("/public/assets/tourbooking.png") ?>" alt="" />
      <div class="services__topic__content">
        <h3 class="services__headline">Tour-Booking & Local Shows</h3>
        <p class="services__description">
          <?php echo langOpts(
            "Jedes Jahr stehen mehr als 200 Konzerte für unsere Acts auf dem Plan.
            Wir vertreten aktuell etwa 20 Künstler*innen, hauptsächlich aus
            Europa, und legen unseren Fokus auf Live-Shows im deutschsprachigen
            Raum. Hinzu kommen jedes Jahr etwa 30 lokale Konzertproduktionen, bei
            denen wir mit tollen Agenturen und Firmen zusammenarbeiten. Einmal im
            Jahr veranstalten wir ein kleines Tastemaker Festival im Cassiopeia im
            Herzen von Berlin-Friedrichshain - das ALL ROOMS Festival bringt
            aufstrebende Acts und das Publikum zusammen.", 
            "Every year more than 200 live concerts are on our artists schedules. We currently represent around 20 artists, mainly from Europe, and focus on live shows in the German-speaking countries. In addition, there are around 30 local concert productions we do every year, on which we work with many great booking agencies and other companies. Once a year, we host a small tastemaker festival at Cassiopeia in the heart of Berlin-Friedrichshain - the ALL ROOMS Festival brings several emerging acts and the audience together."
            ) 
          ;?>

          
        </p>
      </div>
    </article>
    <article class="services__topic">
      <img src="<?= get_theme_file_uri("/public/assets/management.png") ?>" alt="" />
      <div class="services__topic__content">
        <h3 class="services__headline">Management</h3>
        <p class="services__description">
          <?php echo langOpts("Für einige unserer Acts arbeiten wir neben dem Live-Booking auch als
          Künstler-Management. Wir arbeiten Hand in Hand an neuen Ideen und der
          gesamten künstlerischen Vision, produzieren Content, vermarkten,
          promoten und netzwerken. Um unsere Ziele zu erreichen, haben wir
          großartige Partner*innen an Board mit denen wir Monat für Monat
          wachsen.", "For some of our acts, in addition to live booking, we also do the artist management. We work hand in hand on new ideas and the entire artist vision, create content, market it, promote and network. To accomplish our goals, we have great partners on board who grow with us month after month.") ;?>
          
        </p>
      </div>
    </article>
    <article class="services__topic">
      <img src="<?= get_theme_file_uri("/public/assets/marketing.png") ?>" alt="" />
      <div class="services__topic__content">
        <h3 class="services__headline">Online PR & Marketing</h3>
        <p class="services__description">
          <?php echo langOpts("Um neue Musik erfolgreich in den Medien zu platzieren, braucht es
          starke Geschichten, guten Content und Kontinuität. Wir überlegen mit
          euch, was ihr wirklich zu sagen habt, wer eure Zielgruppe ist,
          bereiten den Content auf und eine gute Story vor. Das ist die
          Grundlage, um einerseits in den Online-Medien mit Reviews,
          Besprechungen oder Interviews stattzufinden. Wir denken kreativ und
          unkonventionell. Welche Ideen gibt es außerhalb der konventionellen
          PR? Wie kann Musik anderweitig platziert und vermarktet werden? Wie
          erreichen wir gesteckte Ziele auf Spotify, gibt es Marken und andere
          Influencer, die den Lifestyle und den Klang eurer Musik verkörpern und
          lässt sich hier eine Zusammenarbeit anstreben? Lasst es uns
          herausfinden.", "To successfully place new music in the media, you need good stories, good content and continuity. We would like to think with you about what you really have to say, who your target group is, prepare the content and a strong story. This is the basis for taking place on the one hand in the online media with reviews, presentations, or interviews. We think creatively and unconventionally. What ideas are there outside of conventional PR? How can music be placed and marketed elsewhere? How do we achieve the goals we have set on Spotify, are there brands and other influencers who embody the lifestyle and sound of your music and can we collaborate with them? Let's find it out.") ;?>
          
        </p>
      </div>
    </article>
    <article class="services__topic">
      <img src="<?= get_theme_file_uri("/public/assets/socialmedia.png") ?>" alt="" />
      <div class="services__topic__content">
        <h3 class="services__headline">Social-Media-Marketing</h3>
        <p class="services__description">
          <?php echo langOpts("Wir helfen dabei, euren musikalischen Content mithilfe von Facebook-,
          Instagram- und/oder YouTube-Anzeigen an die richtige Zielgruppe
          auszuspielen. Wir haben langjährige Erfahrungen bei dem Advertising
          von Single-Releases, Musikvideos und Konzerten. Hierbei geht es
          keinesfalls darum, Unsummen an Geld auszugeben, sondern lieber
          zielgerichtet kleine Budgets auszuspielen und damit spürbare Effekte
          zu erzielen.", "We want to help you target your musical content to the right audience with the help of Facebook, Instagram and/or YouTube ads. We have many years of experience in advertising single releases, music videos and concerts. This is by no means about spending huge sums of money, but rather targeting small budgets and thus achieving noticeable effects.") ;?>
          
        </p>
      </div>
    </article>
  </div>
  <div class="contact-cta"></div>
  <div class="credit-link">
    All Illustrations by
    <a href="https://streamlineicons.com/">Streamline.</a>
  </div>
</section>

<?php 
get_footer();
?>