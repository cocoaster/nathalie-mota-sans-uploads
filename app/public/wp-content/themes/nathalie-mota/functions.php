<?php
/**
 * Nathalie Mota Theme Functions
 * 
 * Ce fichier gère les configurations du thème, l'enregistrement des scripts, 
 * des menus et l'inclusion des fichiers essentiels.
 */

// 1. Initialisation du thème : ajout des supports de base
function nathalie_mota_setup() {
    // Ajoute le support pour le titre dynamique dans l'onglet du navigateur
    add_theme_support('title-tag');

    // Ajoute le support pour les images mises en avant dans les publications
    add_theme_support('post-thumbnails');

    // Enregistre les menus de navigation
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'nathalie-mota'),
        'footer-menu' => __('Footer Menu', 'nathalie-mota')
    ));
}
add_action('after_setup_theme', 'nathalie_mota_setup');

// 2. Enregistrement des scripts et styles CSS/JS
function nathalie_mota_enqueue_scripts() {
    // Enregistrement des styles
    $styles = [
        'normalize-css' => '/assets/css/normalize.css',
        'main-css' => '/assets/css/styles.css',
        'lightbox-css' => '/assets/css/lightbox.css',
        'header-css' => '/assets/css/header.css',
        'footer-css' => '/assets/css/footer.css',
        'front-page-css' => '/assets/css/front-page.css',
        'gallery-css' => '/assets/css/gallery.css',
        'filters-css' => '/assets/css/filters.css',
        'single-photo-css' => '/assets/css/single-photo.css',
        'contact-css' => '/assets/css/contact.css',
        'animations-css' => '/assets/css/animations.css',
        '404-css' => '/assets/css/404.css',
        'fontawesome' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
    ];

    foreach ($styles as $handle => $src) {
        // // Vérifie si l'URL du style commence par 'http'
        $url = strpos($src, 'http') === 0 ? $src : get_template_directory_uri() . $src;
        // Charge le style en utilisant le nom d'identifiant unique et l'URL calculée
        wp_enqueue_style($handle, $url);
    }

    // Enregistrement des scripts
    wp_enqueue_script('jquery'); // Script jQuery de WordPress

    $scripts = [
        'custom-js' => '/assets/js/custom.js',
        'header-js' => '/assets/js/header.js',
        'contact-js' => '/assets/js/contact.js',
        'lightbox-js' => '/assets/js/lightbox.js',
        'filters-js' => '/assets/js/filters.js',
        'single-photo-js' => '/assets/js/single-photo.js',
        
    ];
    // Boucle pour enregistrer et charger chaque fichier JavaScript défini dans le tableau $scripts
    foreach ($scripts as $handle => $src) {
        wp_enqueue_script($handle, get_template_directory_uri() . $src, array('jquery'), null, true);
    }

    // Localiser le script 'load-more-js' pour passer l'URL AJAX et le nonce
    wp_localize_script('load-more-js', 'nathalie_mota_ajax', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('nathalie_mota_nonce') // Créez un nonce unique pour AJAX
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_scripts');


 foreach ($styles as $handle => $src) {
        // // Vérifie si l'URL du style commence par 'http'
        $url = strpos($src, 'http') === 0 ? $src : get_template_directory_uri() . $src;
        // Charge le style en utilisant le nom d'identifiant unique et l'URL calculée
        wp_enqueue_style($handle, $url);
    }



// 3. Inclusion des fichiers additionnels
require_once get_template_directory() . '/inc/custom-post-types.php';  // Gestion des Custom Post Types
require_once get_template_directory() . '/inc/ajax-handlers.php';      // Gestion des requêtes AJAX
require_once get_template_directory() . '/inc/hero-customizer.php';        // Personnalisation de la section Hero

function render_photo_html($photos) {
    while ($photos->have_posts()) : $photos->the_post();
        get_template_part('template-parts/photo-item');
        
    endwhile;
    wp_reset_postdata();
}




// 4. Fonction pour permettre l'upload de fichiers SVG
function add_svg_to_upload_mimes($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'add_svg_to_upload_mimes');

// 5. Rendre le troisième élément de menu non cliquable
function make_third_menu_item_non_clickable($items, $args) {
    if ($args->theme_location == 'footer-menu') {
        $count = 0; // Initialiser le compteur d'éléments de menu

        foreach ($items as $item) {
            $count++;

            // Cibler le troisième élément
            if ($count === 3) {
                // Retirer le lien et ajouter une classe spécifique
                $item->url = '#'; 
                $item->classes[] = 'non-clickable';
            }
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'make_third_menu_item_non_clickable', 10, 2);


// Supprimer la catégorie "Uncategorized" et exclure la catégorie "General" des sélecteurs personnalisés
function remove_uncategorized_category() {
    $uncategorized_id = get_cat_ID('Uncategorized');
    if ($uncategorized_id) {
        wp_delete_term($uncategorized_id, 'category');
    }
}
add_action('init', 'remove_uncategorized_category');

// Exclure "Uncategorized" et "General" des sélecteurs personnalisés
function exclude_uncategorized_and_general_term($terms, $taxonomies, $args) {
    if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
        foreach ($terms as $key => $term) {
            if (is_object($term) && ($term->slug == 'uncategorized' || $term->slug == 'general')) {
                unset($terms[$key]);
            }
        }
    }
    return $terms;
}
add_filter('get_terms', 'exclude_uncategorized_and_general_term', 10, 3);

// 9. Activer la suppression des termes de taxonomie dans les Custom Post Types
function allow_term_deletion() {
    global $wp_taxonomies;
    foreach ($wp_taxonomies as $taxonomy => $object) {
        if (in_array('photo', $object->object_type)) {
            $wp_taxonomies[$taxonomy]->public = true;
        }
    }
}
add_action('init', 'allow_term_deletion');

function get_all_photos_sorted_by_date() {
    global $wpdb;

    $query = "
        SELECT p.ID
        FROM {$wpdb->posts} p
        JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'photo'
          AND p.post_status = 'publish'
          AND pm.meta_key = '_photo_date'
        ORDER BY pm.meta_value ASC, p.ID ASC";

    return $wpdb->get_results($query);
}

// Obtenir l'index de la photo actuelle
function get_current_photo_index($current_post_id, $photos) {
    foreach ($photos as $index => $photo) {
        if ($photo->ID == $current_post_id) {
            return $index;
        }
    }
    return -1; // Indice non trouvé
}

// Formulaire de contact
function submit_contact_form() {
    // Vérifier les permissions
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'submit_contact_form_nonce')) {
        error_log('Nonce verification failed.');
        wp_send_json_error(array('message' => 'Nonce verification failed.'));
        return;
    }

    // Récupérer les données du formulaire
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $photo_reference = isset($_POST['photo_reference']) ? sanitize_text_field($_POST['photo_reference']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    error_log('Name: ' . $name);
    error_log('Email: ' . $email);
    error_log('Photo Reference: ' . $photo_reference);
    error_log('Message: ' . $message);

    // Valider les données
    if (empty($name) || empty($email) || empty($message)) {
        error_log('Missing required fields.');http://nathaliemota.local/wp-admin/plugins.php
        wp_send_json_error(array('message' => 'Veuillez remplir tous les champs obligatoires.'));
        return;
    }

    // Valider la référence de la photo si elle est fournie
    if (!empty($photo_reference)) {
        $photo_query = new WP_Query(array(
            'post_type' => 'photo',
            'meta_query' => array(
                array(
                    'key' => '_photo_reference',
                    'value' => $photo_reference,
                    'compare' => '='
                )
            )
        ));

        if (!$photo_query->have_posts()) {
            error_log('Invalid photo reference.');
            wp_send_json_error(array('message' => 'La référence de la photo est invalide.'));
            return;
        }
    }

    // Envoyer un e-mail à l'administrateur
    $to_admin = get_option('admin_email');
    $subject_admin = sprintf(__('Nouveau message de %s', 'nathalie-mota'), $name);
    $body_admin = sprintf(__('Nom: %s\nEmail: %s\nRéférence Photo: %s\nMessage: %s', 'nathalie-mota'), $name, $email, $photo_reference, $message);
    $headers = array('Content-Type: text/plain; charset=UTF-8');

    $admin_email_sent = wp_mail($to_admin, $subject_admin, $body_admin, $headers);

    // Envoyer un e-mail de confirmation à l'utilisateur
    $to_user = $email;
    $subject_user = __('Confirmation de réception de votre message', 'nathalie-mota');
    $body_user = sprintf(
        __('Bonjour %s,\n\nMerci pour votre message. Nous avons bien reçu votre demande et nous vous recontacterons sous peu.\n\nCordialement,\nNathalie Mota.', 'nathalie-mota'),
        $name
    );
    $user_email_sent = wp_mail($to_user, $subject_user, $body_user, $headers);

    if ($admin_email_sent && $user_email_sent) {
        error_log('Emails sent successfully.');
        wp_send_json_success(array('message' => __('Votre message a bien été envoyé. Vous allez recevoir un e-mail de confirmation.', 'nathalie-mota')));
    } else {
        error_log('Failed to send email.');
        wp_send_json_error(array('message' => __('Une erreur est survenue lors de l\'envoi de votre message.', 'nathalie-mota')));
    }
}
add_action('wp_ajax_submit_contact_form', 'submit_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'submit_contact_form');

?>
