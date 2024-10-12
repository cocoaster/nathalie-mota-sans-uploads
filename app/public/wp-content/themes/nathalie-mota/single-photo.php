<?php
/*
Template Name: Single Photo
*/

get_header(); ?>
<div class="single-photo-container">
    <div id="photo-details" class="photo-details">
        <!-- Bloc de gauche (informations) -->
        <div class="photo-fields-info">
            <div class="photo-title">
                <h1 class="field"><?php the_title(); ?></h1>
            </div>
            <div class="field-texts">
                <p class="field"><?php echo __('Référence : ', 'nathalie-mota') . esc_html(get_post_meta(get_the_ID(), '_photo_reference', true)); ?></p>
                <p class="field"><?php echo __('Catégorie : ', 'nathalie-mota') . strip_tags(get_the_term_list(get_the_ID(), 'category', '', ', ', '')); ?></p>
                <p class="field"><?php echo __('Format : ', 'nathalie-mota') . strip_tags(get_the_term_list(get_the_ID(), 'format', '', ', ', '')); ?></p>
                <p class="field"><?php echo __('Type : ', 'nathalie-mota') . esc_html(get_post_meta(get_the_ID(), 'Type', true)); ?></p>
                <p class="field"><?php echo __('Année : ', 'nathalie-mota') . date('Y', strtotime(get_post_meta(get_the_ID(), '_photo_date', true))); ?></p>
            </div>
        </div>

        <!-- Bloc de droite (image) -->
        <?php
        if (has_post_thumbnail()) {
            the_post_thumbnail('large', array('class' => 'photo-thumbnail'));
        } else {
            echo __('No image', 'nathalie-mota');
        }
        ?>  
    </div>

    <!-- Bloc inférieur contact et suivant - précédent -->
    <div id="photo-interactions" class="photo-interactions">
        <div class="contact-link">
            <p id="contact-request"><?php _e('Cette photo vous intéresse ?', 'nathalie-mota'); ?></p>
            <a href="#contact-modal" class="open-contact-modal" data-photo-reference="<?php echo esc_attr(get_post_meta(get_the_ID(), '_photo_reference', true)); ?>"><?php _e('Contact', 'nathalie-mota'); ?></a>
        </div>
            
        <div class="navigation-links">
            <?php
            $all_photos = get_all_photos_sorted_by_date();
            $current_index = get_current_photo_index(get_the_ID(), $all_photos);
            if ($current_index > 0) {
                $prev_photo = $all_photos[$current_index - 1];
                $prev_thumbnail = get_the_post_thumbnail_url($prev_photo->ID, 'thumbnail');
                echo '<a href="' . get_permalink($prev_photo->ID) . '" class="prev" data-thumbnail="' . $prev_thumbnail . '">← ' . __('', 'nathalie-mota') . '</a>';
            }

            if ($current_index < count($all_photos) - 1) {
                $next_photo = $all_photos[$current_index + 1];
                $next_thumbnail = get_the_post_thumbnail_url($next_photo->ID, 'thumbnail');
                echo '<a href="' . get_permalink($next_photo->ID) . '" class="next" data-thumbnail="' . $next_thumbnail . '">' . __('', 'nathalie-mota') . ' →</a>';
            }
            ?>
            <div id="thumbnail-preview">
                <img id="thumbnail-image" src="" alt="Thumbnail">
            </div>
        </div>
    </div>

    <!-- Section "Vous aimerez aussi" -->
    <div class="bottom-section">
        <!-- Texte "Vous aimerez aussi" -->
        <div class="related-section">
            <p><?php _e('VOUS AIMEREZ AUSSI', 'nathalie-mota'); ?></p>
        </div>

        <!-- Photos similaires -->
        <div id="related-photos" class="related-photos">
            <?php
            $categories = get_the_terms(get_the_ID(), 'category');
            $category_ids = array();
            if ($categories) {
                foreach ($categories as $category) {
                    $category_ids[] = $category->term_id;
                }
            }

            $related_args = array(
                'post_type' => 'photo',
                'posts_per_page' => 2,
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                    ),
                ),
            );

            $related_photos = new WP_Query($related_args);

            if ($related_photos->have_posts()) :
                while ($related_photos->have_posts()) : $related_photos->the_post();
                    get_template_part('template-parts/photo-item');
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>' . __('No related photos found', 'nathalie-mota') . '</p>';
            endif;
            ?>
        </div>
    </div>
</div>

<!-- Inclure la lightbox -->
<?php get_template_part('template-parts/lightbox'); ?>

<?php get_footer(); ?>
