<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<main class="container" style="padding:40px 20px; max-width: 900px; margin: 0 auto;">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                <h1><?php the_title(); ?></h1>
                <div>
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <h1><?php esc_html_e('Ничего не найдено', 'basketball-wetten'); ?></h1>
    <?php endif; ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
