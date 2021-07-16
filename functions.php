<?php

function my_theme_enqueue_styles()
{
    $parenthandle = 'parent-style';
    $theme        = wp_get_theme();
    wp_enqueue_style(
        $parenthandle,
        get_template_directory_uri() . '/style.css',
        array(),
        $theme->parent()->get('Version')
    );
    wp_enqueue_style(
        'child-style',
        get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles', 11);

add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    }
    return $title;
});

/** Google fonts
 */
function euinsects_custom_fonts() {
	wp_enqueue_style(
		'my_custom_fonts',
		'https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c:wght@400;700&display=swap'
	);
}
add_action( 'wp_enqueue_scripts', 'euinsects_custom_fonts' );
