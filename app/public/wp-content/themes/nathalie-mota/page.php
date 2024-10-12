<?php
get_header(); ?>

<div id="main-content">
    <!-- Contenu de la page -->
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</div>

<?php
get_footer();
