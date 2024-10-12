<?php
// template-parts/photo-item.php

$image_url = wp_get_attachment_url(get_post_thumbnail_id());
$reference = get_post_meta(get_the_ID(), '_photo_reference', true);
$categories = get_the_terms(get_the_ID(), 'category');
$category_names = wp_list_pluck($categories, 'name');
?>
<div class="photo-item" 
    data-full-image="<?php echo esc_attr($image_url); ?>" 
    data-reference="<?php echo esc_attr($reference); ?>" 
    data-category="<?php echo esc_attr(implode(', ', $category_names)); ?>">
    <a href="<?php the_permalink(); ?>" class="photo-link">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium_large'); ?>
        <?php else : ?>
            <?php _e('No image', 'nathalie-mota'); ?>
        <?php endif; ?>
    </a>
    <div class="photo-overlay">
        <a href="<?php the_permalink(); ?>" class="icon eye"><i class="fa fa-eye"></i></a>
        <div class="icon fullscreen"><i class="fa fa-expand"></i></div>
        <div class="photo-info">
            <span class="photo-reference"><?php echo esc_attr($reference); ?></span>
            <span class="photo-category"><?php echo esc_attr(implode(', ', $category_names)); ?></span>
        </div>
    </div>
</div>
