# Static Content Blocks

A plugin for WordPress to create reusable content to insert in posts, 
pages and other areas of the site.
The plugin appears in the WordPress admin under the 'Content Blocks' 
menu. The content created in this area can be inserted using a shortcode, `[cms-block id="1"]`, or with the widget.

The shortcode accepts parameters for:

<dl>
  <dt><strong>id</strong> (int | string)</dt>
  <dd>The static block ID or "slug" in WordPress</dd>

  <dt><strong>showtitle</strong> (bool)</dt>
  <dd>If the static block title should be included in the output. Default: false</dd>
</dl>

This can be integrated within a template to allow users to select content source for headers, footers and other design specific output.

Using PHP: `the_cms_block(1);`
