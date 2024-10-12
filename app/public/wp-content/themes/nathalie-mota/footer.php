<footer class="site-footer">
    <div class="container">
        <nav class="footer-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer-menu',
                'menu_class' => 'footer-menu',
                'container' => 'ul'
            ));
            ?>
        </nav>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
