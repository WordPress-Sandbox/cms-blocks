<?php

/**
 * Globally-accessible functions.
 *
 * @link       https://github.com/adsric
 * @since      1.0.0
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/includes
 */

/**
 * Globally-accessible functions.
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/includes
 */

// Easy access to static block output
function the_cms_block( $id = false, $args = array(), $echo = true ) {
  if ($id) {
    $args["id"] = $id;
    $content = CMS_Blocks_Public::get_cms_content($args);
    if ($echo) {
      echo $content;
    } else {
      return $content;
    }
  }
}

// HELPER: Get content ID by slug
if ( ! function_exists( 'get_ID_by_slug' ) ) :

  function get_ID_by_slug( $slug, $post_type = 'page' ) {

    // Find the page object (works for any post type)
    $page = get_page_by_path( $slug, 'OBJECT', $post_type );

    if ($page) {
      return $page->ID;
    } else {
      return null;
    }
  }
endif;

// HELPER: Translate filter
function func_wpml_translate($type, $source) {
  return defined('ICL_LANGUAGE_CODE')? icl_object_id($source, $type, true, ICL_LANGUAGE_CODE) : $source;
}
add_filter( 'wpml_translate', 'func_wpml_translate', 10, 2 );

// HELPER: Shortcode filter
if ( ! function_exists( 'func_cms_content_vc' ) ) :

  function func_cms_content_vc($content, $id) {

    if (strstr($content, 'data-vc-grid-settings') === false )
      return $content;

    $pos_start = strpos($content, '&quot;page_id&quot;:');
    $part = substr($content, $pos_start + 20);
    $pos_end = strpos($part, ',');
    $id_in_content = substr($part, 0, $pos_end);
    $id_old = '&quot;page_id&quot;:'.$id_in_content;
    $id_new = '&quot;page_id&quot;:'.$id;
    $content = str_replace($id_old, $id_new, $content);

    return $content;
  }
  add_filter( 'cms_content_vc', 'func_cms_content_vc', 10, 2 );
endif;

// HELPER: Shortcode filter
if ( ! function_exists( 'func_get_vc_row_css' ) ) :

  function func_get_vc_row_css( $content ) {
    $pattern = get_shortcode_regex();
    preg_match_all( "/$pattern/", $content, $matches );
    $style = '';
    if( ! empty( $matches[3][0] ) ) {
      $pos1 = strpos( $matches[3][0], 'css=' );
      if( $pos1 !== false ) {
        $pos2 = strpos( $matches[3][0], '"', $pos1+5 );
        $css = substr( $matches[3][0], $pos1+5, $pos2 - $pos1 - 5 );
        $pos1 = strpos( $css, '{' );
        $pos2 = strpos( $css, '}', $pos1+1 );
        $css = substr( $css, $pos1+1, $pos2 - $pos1 - 1 );
        $style = ! empty( $css ) ? 'style="'.$css.'"' : '';
      }
    }

    return $style;
  }
  add_filter( 'get_vc_row_css', 'func_get_vc_row_css', 10, 2 );
endif;
