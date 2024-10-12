<?php
// Fichier : inc/ajax-handlers.php

/**
 * Enregistrement des scripts AJAX pour les filtres et la pagination
 */
function nathalie_mota_ajax_scripts() {
    wp_localize_script('custom-js', 'nathalie_mota_ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_ajax_scripts');

/**
 * Fonction AJAX pour filtrer les photos
 */
function filter_photos() {
    try {
        // Récupération des données envoyées par AJAX pour la catégorie, le format et l'ordre de tri
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
        $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';

        // Définition des arguments pour la requête WP_Query afin de récupérer les photos
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 8,
            'orderby' => 'date',
            'order' => $order,
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

        // Exécute la requête WP_Query
        $photos = new WP_Query($args);
        $total_photos = $photos->found_posts; // Nombre total de photos disponibles
        
        // Capture le contenu HTML généré par render_photo_html()
        ob_start();
        render_photo_html($photos);
        $html = ob_get_clean();

        // Retourne la réponse en JSON
        echo json_encode(array(
            'html' => $html,
            'total' => $total_photos,
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

/**
 * Fonction AJAX pour charger plus de photos
 */
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

/**
 * Gestion de l'envoi du formulaire de contact via AJAX
 */
function submit_contact_form() {
    // Vérification du nonce pour des raisons de sécurité
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'submit_contact_form_nonce')) {
        error_log('Nonce verification failed.');
        wp_send_json_error(array('message' => 'Nonce verification failed.'));
        return;
    }

    // Récupération des données du formulaire
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $photo_reference = isset($_POST['photo_reference']) ? sanitize_text_field($_POST['photo_reference']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    // Vérification de la présence des champs obligatoires
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => 'Veuillez remplir tous les champs obligatoires.'));
        return;
    }

    // Validation de la référence photo si fournie
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
            wp_send_json_error(array('message' => 'La référence de la photo est invalide.'));
            return;
        }
    }

    // Envoi d'un e-mail à l'administrateur
    $to_admin = get_option('admin_email');
    $subject_admin = sprintf(__('Nouveau message de %s', 'nathalie-mota'), $name);
    $body_admin = sprintf(__('Nom: %s\nEmail: %s\nRéférence Photo: %s\nMessage: %s', 'nathalie-mota'), $name, $email, $photo_reference, $message);
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    $admin_email_sent = wp_mail($to_admin, $subject_admin, $body_admin, $headers);

    // Envoi d'un e-mail de confirmation à l'utilisateur
    $to_user = $email;
    $subject_user = __('Confirmation de réception de votre message', 'nathalie-mota');
    $body_user = sprintf(
        __('Bonjour %s,\n\nMerci pour votre message. Nous avons bien reçu votre demande et nous vous recontacterons sous peu.\n\nCordialement,\nNathalie Mota.', 'nathalie-mota'),
        $name
    );
    $user_email_sent = wp_mail($to_user, $subject_user, $body_user, $headers);

    if ($admin_email_sent && $user_email_sent) {
        wp_send_json_success(array('message' => __('Votre message a bien été envoyé. Vous allez recevoir un e-mail de confirmation.', 'nathalie-mota')));
    } else {
        wp_send_json_error(array('message' => __('Une erreur est survenue lors de l\'envoi de votre message.', 'nathalie-mota')));
    }
}
add_action('wp_ajax_submit_contact_form', 'submit_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'submit_contact_form');
?>
