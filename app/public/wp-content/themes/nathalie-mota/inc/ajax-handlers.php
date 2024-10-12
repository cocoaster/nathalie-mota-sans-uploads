<?php
// Fichier : inc/ajax-handlers.php

// Enregistrement du script AJAX pour les filtres et la pagination
function nathalie_mota_ajax_scripts() {
    wp_localize_script('custom-js', 'nathalie_mota_ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_ajax_scripts');

// Fonction AJAX pour filtrer les photos
function filter_photos() {
    try {
        // Récupère les données envoyées par AJAX pour la catégorie, le format et l'ordre de tri
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
        $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';

        // Définition des arguments pour la requête WP_Query afin de récupérer les photos selon les critères
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 8,
            'orderby' => 'date',
            'order' => $order,
            // Filtrage selon les taxonomies "category" et "format"
            'tax_query' => array(
                'relation' => 'AND',
            ),
        );
        // Ajoute le filtre par catégorie si spécifié
        if ($category && $category != 'all') {
            $args['tax_query'][] = array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $category,
            );
        }
        // Ajoute le filtre par format si spécifié
        if ($format && $format != 'all') {
            $args['tax_query'][] = array(
                'taxonomy' => 'format',
                'field' => 'slug',
                'terms' => $format,
            );
        }

        // Exécute la requête WP_Query avec les arguments définis
        $photos = new WP_Query($args);
        $total_photos = $photos->found_posts; // Nombre total de photos disponibles
        
        // Capture le contenu HTML généré par render_photo_html()
        ob_start();
        render_photo_html($photos);
        $html = ob_get_clean();

         // Retourne la réponse en JSON contenant le HTML des photos et le nombre total
        echo json_encode(array(
            'html' => $html,
            'total' => $total_photos, // Retourner le nombre total
        ));
    } catch (Exception $e) {
        echo json_encode(array(
            'error' => $e->getMessage(),
        ));
    }

    wp_die();
}
add_action('wp_ajax_filter_photos', 'filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos');

// Fonction AJAX pour charger plus de photos
function load_more_photos() {
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'offset' => $offset,
        'orderby' => 'date',
        'order' => $order,
        'tax_query' => array(
            'relation' => 'AND',
        ),
    );

    if ($category && $category != 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $category,
        );
    }

    if ($format && $format != 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => $format,
        );
    }

    $photos = new WP_Query($args);
    $loaded_photos = $photos->post_count; // Nombre de photos chargées

    ob_start();
    render_photo_html($photos);
    $html = ob_get_clean();

    echo json_encode(array(
        'html' => $html,
        'loaded' => $loaded_photos,
    ));

    wp_die();
}
add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');
?>
