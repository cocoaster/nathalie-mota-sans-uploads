<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
    <div class="container">
        <div class="logo">
            <h1 class="site-title">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
            </h1>
        </div>
        <button class="hamburger" aria-label="Toggle navigation">
            <span class="hamburger-icon"></span>
        </button>
        <nav class="main-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'menu_class' => 'nav-menu',
                'container' => 'ul'
            ));
            ?>
        </nav>
    </div>
</header>
<?php get_template_part('template-parts/contact-modal'); ?>

<?php wp_footer(); ?>
</body>
</html>
