<?php
// Fichier : inc/custom-post-types.php


// Enregistrement du Custom Post Type pour les Photos et les taxonomies personnalisées
function nathalie_mota_custom_post_types() {
    register_post_type('photo', array(
        'label' => __('Photos', 'nathalie-mota'),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'taxonomies' => array('category', 'post_tag', 'format'),
        'rewrite' => array('slug' => 'photos'),
        'show_in_rest' => false, // Désactiver Gutenberg
        'labels' => array(
            'name' => __('Photos', 'nathalie-mota'),
            'singular_name' => __('Photo', 'nathalie-mota'),
            'add_new' => __('Ajouter Nouvelle', 'nathalie-mota'),
            'add_new_item' => __('Ajouter Nouvelle Photo', 'nathalie-mota'),
            'edit_item' => __('Modifier Photo', 'nathalie-mota'),
            'new_item' => __('Nouvelle Photo', 'nathalie-mota'),
            'view_item' => __('Voir Photo', 'nathalie-mota'),
            'search_items' => __('Rechercher Photos', 'nathalie-mota'),
            'not_found' => __('Pas de Photos trouvées', 'nathalie-mota'),
            'not_found_in_trash' => __('Pas de Photos dans la corbeille', 'nathalie-mota'),
            'all_items' => __('Toutes les Photos', 'nathalie-mota'),
            'archives' => __('Archives des Photos', 'nathalie-mota'),
        ),
    ));

    register_taxonomy('format', 'photo', array(
        'label' => __('Formats', 'nathalie-mota'),
        'rewrite' => array('slug' => 'formats'),
        'hierarchical' => true,
    ));
}
add_action('init', 'nathalie_mota_custom_post_types');

// Désactiver Gutenberg pour le Custom Post Type "photo"
// Désactiver Gutenberg pour le Custom Post Type 'photo'
function nathalie_mota_disable_gutenberg($current_status, $post_type) {
    if ($post_type === 'photo') return false;
    return $current_status;
}
add_filter('use_block_editor_for_post_type', 'nathalie_mota_disable_gutenberg', 10, 2);

// Ajouter une métabox pour les détails de la photo
function add_custom_meta_boxes() {
    add_meta_box(
        'photo_details',
        __('Photo Details', 'nathalie-mota'),
        'render_photo_details_meta_box',
        'photo',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_custom_meta_boxes');

// Affiche les champs personnalisés pour la date de prise de vue, la référence et le type de la photo
function render_photo_details_meta_box($post) {
    wp_nonce_field('save_photo_details', 'photo_details_nonce');
    $date = get_post_meta($post->ID, '_photo_date', true);
    $reference = get_post_meta($post->ID, '_photo_reference', true);
    $type = get_post_meta($post->ID, '_photo_type', true); // Ajouter le champ personnalisé "type"
    ?>
    <p>
        <label for="photo_date"><?php _e('Date de Prise de Vue', 'nathalie-mota'); ?></label>
        <input type="date" id="photo_date" name="photo_date" value="<?php echo esc_attr($date); ?>" />
    </p>
    <p>
        <label for="photo_reference"><?php _e('Référence Photo', 'nathalie-mota'); ?></label>
        <input type="text" id="photo_reference" name="photo_reference" value="<?php echo esc_attr($reference); ?>" />
    </p>
    <p>
        <label for="photo_type"><?php _e('Type', 'nathalie-mota'); ?></label>
        <input type="text" id="photo_type" name="photo_type" value="<?php echo esc_attr($type); ?>" />
    </p>
    <?php
}


// Sauvegarde des métadonnées associées aux "photos"
function save_photo_details($post_id) {
    if (!isset($_POST['photo_details_nonce']) || !wp_verify_nonce($_POST['photo_details_nonce'], 'save_photo_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['photo_date'])) {
        update_post_meta($post_id, '_photo_date', sanitize_text_field($_POST['photo_date']));
    }
    if (isset($_POST['photo_reference'])) {
        update_post_meta($post_id, '_photo_reference', sanitize_text_field($_POST['photo_reference']));
    }
    if (isset($_POST['photo_type'])) { // Sauvegarder le champ personnalisé "type"
        update_post_meta($post_id, '_photo_type', sanitize_text_field($_POST['photo_type']));
    }
}
add_action('save_post', 'save_photo_details');

// Page de gestion des formats personnalisés
function add_format_management_page() {
    add_menu_page(
        __('Gestion des Formats', 'nathalie-mota'),
        __('Gestion des Formats', 'nathalie-mota'),
        'manage_options',
        'format-management',
        'render_format_management_page'
    );
}
add_action('admin_menu', 'add_format_management_page');

// Gestion des champs FORMATS des Custom Post Types
function render_format_management_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Gestion des Formats', 'nathalie-mota'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('delete_formats_nonce', 'delete_formats_nonce_field'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Supprimer un format', 'nathalie-mota'); ?></th>
                    <td>
                        <select name="format_id">
                            <?php
                            $formats = get_terms(array('taxonomy' => 'format', 'hide_empty' => false));
                            foreach ($formats as $format) {
                                echo '<option value="' . esc_attr($format->term_id) . '">' . esc_html($format->name) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Supprimer', 'nathalie-mota')); ?>
        </form>
        <?php
        if (isset($_POST['delete_formats_nonce_field']) && wp_verify_nonce($_POST['delete_formats_nonce_field'], 'delete_formats_nonce')) {
            $format_id = intval($_POST['format_id']);
            wp_delete_term($format_id, 'format');
            echo '<div class="updated"><p>' . __('Format supprimé.', 'nathalie-mota') . '</p></div>';
        }
        ?>
    </div>
    <?php
}

// Page d'options pour gérer les termes personnalisés
function add_taxonomy_management_page() {
    add_menu_page(
        __('Gestion des Catégories', 'nathalie-mota'),
        __('Gestion des Catégories', 'nathalie-mota'),
        'manage_options',
        'taxonomy-management',
        'render_taxonomy_management_page'
    );
}
add_action('admin_menu', 'add_taxonomy_management_page');

// Affiche la page de gestion des catégories dans le panneau d'administration
function render_taxonomy_management_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Gestion des Catégories', 'nathalie-mota'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('delete_terms_nonce', 'delete_terms_nonce_field'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Supprimer un terme de catégorie', 'nathalie-mota'); ?></th>
                    <td>
                        <select name="term_id">
                            <?php
                            $terms = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));
                            foreach ($terms as $term) {
                                if ($term->slug != 'uncategorized' && $term->slug != 'general') {
                                    echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Supprimer', 'nathalie-mota')); ?>
        </form>
        <?php
        if (isset($_POST['delete_terms_nonce_field']) && wp_verify_nonce($_POST['delete_terms_nonce_field'], 'delete_terms_nonce')) {
            $term_id = intval($_POST['term_id']);
            wp_delete_term($term_id, 'category');
            echo '<div class="updated"><p>' . __('Terme supprimé.', 'nathalie-mota') . '</p></div>';
        }
        ?>
    </div>
    <?php
}
