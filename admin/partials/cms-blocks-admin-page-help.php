<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/adsric
 * @since      1.0.0
 *
 * @package    CMS_Blocks
 * @subpackage CMS_Blocks/admin/partials
 */

?>
<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

<h2><?php esc_html_e( 'Shortcode' ); ?></h2>

<p><?php esc_html_e( 'The simplest version of the shortcode is:' ); ?></p>
<pre><code>[cms-block id="ID"]</code></pre>
<p><?php esc_html_e( 'Enter that in the Editor on any page, post or widget to display a cms block. (Requires a $post-ID to replace ID)' ); ?></p>

<p><?php esc_html_e( 'This is an example with allowing a title to be shown:' ); ?></p>
<pre><code>[cms-block id="ID" showtitle="true"]</code></pre>

<p><?php esc_html_e( 'This is an example with all the attributes used:' ); ?></p>
<pre><code>[cms-block id="ID" title="A Title" titletag="h2"]</code></pre>

<h3><?php esc_html_e( 'Shortcode Attributes' ); ?></h3>
<p><?php esc_html_e( 'There are currently five attributes that can be added to the shortcode to filter:' ); ?></p>
<ol>
  <li><?php esc_html_e( 'id' ); ?></li>
  <li><?php esc_html_e( 'post_type' ); ?></li>
  <li><?php esc_html_e( 'title' ); ?></li>
  <li><?php esc_html_e( 'showtitle' ); ?></li>
  <li><?php esc_html_e( 'titletag' ); ?></li>
</ol>

<h4><?php esc_html_e( 'id' ); ?></h4>
<p><?php esc_html_e( 'The $post-ID or $slug of the block, page or post.' ); ?></p>
<p><?php esc_html_e( 'Examples of the post_type attribute:' ); ?></p>
<ul>
  <li><?php esc_html_e( 'id="1" (query by $post-ID)' ); ?></li>
  <li><?php esc_html_e( 'id="example" (query by slug)' ); ?></li>
</ul>

<h4><?php esc_html_e( 'post_type' ); ?></h4>
<p><?php esc_html_e( 'Setting this to "page" or "post" would query those post types instead of the cms blocks default type.' ); ?></p>
<p><?php esc_html_e( 'Examples of the post_type attribute:' ); ?></p>
<ul>
  <li><?php esc_html_e( 'post_type="page" (query a page type)' ); ?></li>
  <li><?php esc_html_e( 'post_type="post" (query a post type)' ); ?></li>
</ul>

<h4><?php esc_html_e( 'title' ); ?></h4>
<p><?php esc_html_e( 'Optional tite text to be used, overrides title given to the block, page or post.' ); ?></p>
<p><?php esc_html_e( 'Examples of the title attribute:' ); ?></p>
<p><?php esc_html_e( 'title="A block title" (a title string)' ); ?></p>

<h4><?php esc_html_e( 'showtitle' ); ?></h4>
<p><?php esc_html_e( 'If set to true the title from the block will be added. By default this attribute is set to false.' ); ?></p>

<h4><?php esc_html_e( 'titletag' ); ?></h4>
<p>By default is <code>h3</code>. This will specify the title element <code><span>&lt;h3&gt;</span>$title<span>&lt;/h3&gt;</span></code>.</p>
<p><?php esc_html_e( 'Examples of the titletag attribute:' ); ?></p>
<ul>
  <li><?php esc_html_e( 'titletag="h2" (wrap the title in a h2 heading tag)' ); ?></li>
  <li><?php esc_html_e( 'titletag="span" (wrap the title in a span tag)' ); ?></li>
</ul>

<h3><?php esc_html_e( 'Templating' ); ?></h3>
<p><?php esc_html_e( 'This can be integrated within a template to allow users to select content source for headers, footers and other design specific output.' ); ?></p>
<p><?php esc_html_e( 'Accepts ID, args, and echo (true or false)' ); ?></p>
<p><?php esc_html_e( 'A simple PHP Example:' ); ?></p>
<pre><code>the_cms_block(1)</code></pre>
<p><?php esc_html_e( 'A PHP Example with all the attributes used:' ); ?></p>
<pre><code>the_cms_block(1, array('post_type' => 'post', 'title' => 'hello', 'titletag' => 'h2'), false)</code></pre>
