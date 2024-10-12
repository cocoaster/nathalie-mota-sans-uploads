<?php
get_header();
?>

<div class="photo-details">
    <div class="photo-info">
        <h1><?php the_title(); ?></h1>
        <p><?php _e('Date de Prise de Vue', 'nathalie-mota'); ?>: <?php echo get_post_meta(get_the_ID(), '_photo_date', true); ?></p>
        <p><?php _e('Référence Photo', 'nathalie-mota'); ?>: <?php echo get_post_meta(get_the_ID(), '_photo_reference', true); ?></p>
        <p><?php _e('Catégorie', 'nathalie-mota'); ?>: <?php the_terms(get_the_ID(), 'category'); ?></p>
        <p><?php _e('Format', 'nathalie-mota'); ?>: <?php the_terms(get_the_ID(), 'format'); ?></p>
    </div>
    <div class="photo-image">
        <?php the_post_thumbnail('full'); ?>
    </div>
</div>

<?php
get_footer();
?>
