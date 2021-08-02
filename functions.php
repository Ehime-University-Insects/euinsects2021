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
function euinsects_custom_fonts()
{
    wp_enqueue_style(
        'my_custom_fonts',
        'https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c:wght@400;700&display=swap'
    );
}
add_action('wp_enqueue_scripts', 'euinsects_custom_fonts');

add_filter('body_class', 'add_page_slug_class_name');
function add_page_slug_class_name($classes)
{
    if (is_page()) {
        $page = get_post(get_the_ID());
        $classes[] = $page->post_name;
    }
    return $classes;
}

/** OGP
 */
function my_meta_ogp()
{
    if (is_front_page() || is_home() || is_singular()) {
        global $post;
        $ogp_title = '';
        $ogp_descr = '';
        $ogp_url = '';
        $ogp_img = '';
        $insert = '';

        if (is_singular()) {
            setup_postdata($post);
            $ogp_title = $post->post_title;
            $ogp_descr = mb_substr(get_the_excerpt(), 0, 100);
            $ogp_url = get_permalink();
            wp_reset_postdata();
        } elseif (is_front_page() || is_home()) {
            $ogp_title = get_bloginfo('name');
            $ogp_descr = get_bloginfo('description');
            $ogp_url = home_url();
        }

         $ogp_type = ( is_front_page() || is_home() ) ? 'website' : 'article';

        if (is_singular() && has_post_thumbnail()) {
            $ps_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            $ogp_img = $ps_thumb[0];
        } else {
            $ogp_img = home_url('/wp-content/uploads/2021/08/mv2021_1200x640.jpg');
        }

        $insert .= '<meta property="og:title" content="'.esc_attr($ogp_title).'" />' . "\n";
        $insert .= '<meta property="og:description" content="'.esc_attr($ogp_descr).'" />' . "\n";
        $insert .= '<meta property="og:type" content="'.$ogp_type.'" />' . "\n";
        $insert .= '<meta property="og:url" content="'.esc_url($ogp_url).'" />' . "\n";
        $insert .= '<meta property="og:image" content="'.esc_url($ogp_img).'" />' . "\n";
        $insert .= '<meta property="og:site_name" content="'.esc_attr(get_bloginfo('name')).'" />' . "\n";
        $insert .= '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        $insert .= '<meta property="og:locale" content="ja_JP" />' . "\n";

        echo $insert;
    }
} //END my_meta_ogp
  
add_action('wp_head', 'my_meta_ogp');
