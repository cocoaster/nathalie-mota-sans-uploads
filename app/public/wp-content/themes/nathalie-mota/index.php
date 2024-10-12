<?php get_header(); ?>

<div id="content" class="site-content">
    <div class="container">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                the_title('<h1>', '</h1>');
                the_content();
            endwhile;
        else :
            echo '<p>Aucun contenu trouvé.</p>';
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>
