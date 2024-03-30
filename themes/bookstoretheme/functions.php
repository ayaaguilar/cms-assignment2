<?php
// menu function
function custom_theme_setup() {
  register_nav_menus( array(
    'header' => 'Header menu',
    'footer' => 'Footer menu'
  ) );
}
add_action( 'after_setup_theme', 'custom_theme_setup' );
// featured image 
add_theme_support( 'post-thumbnails' );
// custom footer widgets
function footer_widgets_init() {
  register_sidebar(array(
      'name'          => __( 'Bookstore Info', 'footerwidget' ),
      'id'            => 'bookstore-info',
      'description'   => __( 'Displays bookstore information like history or special announcements.', 'footerwidget' ),
      'before_widget' => '<div class="bookstore-info-widget">',
      'after_widget'  => '</div>',
  ));
  register_sidebar(array(
      'name'          => __( 'Bookstore Navigation', 'footerwidget' ),
      'id'            => 'bookstore-navigation',
      'description'   => __( 'The footer navigation widget area for bookstore categories, popular authors, or genres.', 'footerwidget' ),
      'before_widget' => '<div class="bookstore-navigation-widget">',
      'after_widget'  => '</div>',
      'before_title'  => '<h4 class="widget-title">',
      'after_title'   => '</h4>',
  ));
  register_sidebar(array(
      'name'          => __( 'Contact & Social Media', 'footerwidget' ),
      'id'            => 'contact-social-media',
      'description'   => __( 'Contact details and social media links for the bookstore.', 'footerwidget' ),
      'before_widget' => '<div class="contact-social-widget">',
      'after_widget'  => '</div>',
      'before_title'  => '<h4 class="widget-title">',
      'after_title'   => '</h4>',
  ));
}
add_action( 'widgets_init', 'footer_widgets_init' );

// custom book post type
function book_init(){
  $args = array(
    'label' => 'Books',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'taxonomies'  => array( 'category'),
    'hierarchical' => false,
    'query_var' => true,
    'menu_icon' => 'dashicons-book-alt',
    'supports' => array(
      'title',
      'editor',
      'excerpts',
      'comments',
      'thumbnail',
      'author',
      'post-formats',
      'page-attributes',
    )
  );
  register_post_type('book', $args);
}
add_action('init', 'book_init');

// shortcode for custom book post-type
function book_shortcode(){
  $query = new WP_Query(array('post_type' => 'book', 'posts_per_page' => 8, 'order' => 'asc'));
  while ($query -> have_posts()) : $query-> the_post(); ?>
    <div class="col-sm-12 col-md-6 col-lg-4">
      <div class="image-container">
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
      </div>
      <div class="book-content">
        <h4><?php the_title(); ?></h4>
        <?php the_excerpt(); ?>
        <p><a href="<?php the_permalink(); ?>">Learn More</a></p>
      </div>
    </div>
    <?php wp_reset_postdata(); ?>
  <?php endwhile;
  wp_reset_postdata();
}
// register shortcode
add_shortcode('book', 'book_shortcode');

// No changes below this line are necessary, but you could adjust the excerpt length if you want it different for books.
add_filter( 'excerpt_length', function($length) {
  return 25;
}, PHP_INT_MAX );

// adding WooCommerce support to our theme (if you sell books on your site, otherwise this can be removed)
function customtheme_add_woocommerce_support() {
  add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'customtheme_add_woocommerce_support' );

function enqueue_wc_cart_fragments() { wp_enqueue_script( 'wc-cart-fragments' ); }
add_action( 'wp_enqueue_scripts', 'enqueue_wc_cart_fragments' );
