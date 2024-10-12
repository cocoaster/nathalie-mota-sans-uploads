<?php
/**
 * Hero Customizer Settings
 * 
 * Ce fichier gère l'ajout de la section Hero dans le Customizer.
 */

/**
 * Enregistre la section Hero et l'image de couverture dans le Customizer.
 * 
 * @param WP_Customize_Manager $wp_customize Instance de la classe Customizer.
 */
function nathalie_mota_customizer_register($wp_customize) {
    // Ajouter une section pour la photo du hero
    $wp_customize->add_section('hero_section', array(
        'title'    => __('Image de couverture', 'nathalie-mota'), // Traduction du titre
        'priority' => 30,
    ));

    // Ajouter un paramètre pour l'image
    $wp_customize->add_setting('hero_image', array(
        'default'   => '',
        'transport' => 'refresh',
    ));

    // Ajouter un contrôle pour l'image
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_image', array(
        'label'    => __('Télécharger l\'image de couverture', 'nathalie-mota'), // Traduction du label
        'section'  => 'hero_section',
        'settings' => 'hero_image',
    )));
}

add_action('customize_register', 'nathalie_mota_customizer_register');
