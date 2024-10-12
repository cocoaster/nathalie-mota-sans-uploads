<?php
get_header(); // Inclure l'en-tête du site
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php _e('Oups ! Cette page est introuvable.', 'nathalie-mota'); ?></h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <p><?php _e('Il semble que rien n\'a été trouvé à cet endroit. Vous pouvez retourner à la', 'nathalie-mota'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('page d\'accueil', 'nathalie-mota'); ?></a>.</p>
                <p><?php _e('Si vous pensez que c\'est une erreur, veuillez nous contacter.', 'nathalie-mota'); ?></p>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); // Inclure le pied de page du site
?>
