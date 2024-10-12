<?php
/**
 * Nathalie Mota Theme Functions
 * 
 * Ce fichier gère les configurations de base du thème, l'enregistrement des scripts, 
 * les menus et l'inclusion des fichiers essentiels pour des fonctionnalités avancées.
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
    // Liste des styles CSS à charger
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

    // Charge jQuery par défaut pour les scripts du thème
    wp_enqueue_script('jquery'); // Script jQuery de WordPress
    // Liste des scripts JavaScript à charger
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



// 9. Récupération des photos triées par date de prise de vue
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

// 10. Récupérer l'index de la photo actuelle dans un ensemble de photos triées
function get_current_photo_index($current_post_id, $photos) {
    foreach ($photos as $index => $photo) {
        if ($photo->ID == $current_post_id) {
            return $index;
        }
    }
    return -1; // Indice non trouvé
}



?>
