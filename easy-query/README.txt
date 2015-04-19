=== Easy Query ===
Contributors: dcooney
Donate link: https://connekthq.com/donate/
Tags: query, builder, wp_query, easy, simple, save, saving query, generator, paging, paged, query builder, shortcode builder, shortcode, search, tags, category, post types, taxonomy, meta_query, post format, wmpl, archives, date
Requires at least: 3.7
Tested up to: 4.1.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create complex WordPress queries in seconds - it's that easy!

== Description ==

Easy Query is the fastest and simplest way to build WordPress queries without ever touching a single line of code. Create complex queries using our Custom Query Builder then add the generated shortcode to your pages via the content editor or directly into your template files using our WP_Query Generator.


= Features =

* **Query Builder** - Create your own Easy Query shortcode in seconds by adjusting the various query parameters.
* **Query Generator** - Generate a custom WP_Query by adjusting parameters in the Query Builder.
* **Customizable Template** - Easy Query’s customizable template allows you to match the look and feel of your website.
* **Paging** - Easily enable Easy Query paging by setting paging=”true” in your shortcode.
* **Multiple Instances** - Include multiple instances of Easy Query on a page, post or page template.

Check out the **[official website](https://connekthq.com/easy-query/)** for more information!

***

= Shortcode Parameters =

Easy Query accepts a number of parameters that are passed to the WordPress query via shortcode.
 
*   **container** - Select the type of container that will wrap your Easy Query templates (<ul>, <ol>, <div>). Default = ‘<ul>’
*   **classes** - Target your content by adding custom classes to the container. Default = null
*   **template** - Select which template you would like to use. Default = ‘default’
*   **post_type** - Comma separated list of post types. Default = ‘post’
*   **category__in** - A comma separated list of categories to include by ID. Default = null
*   **category__not_in** - A comma separated list of categories to exclude by ID. Default = null
*   **tag__in** - A comma separated list of tags to include by ID. Default = null
*   **tag__not_in** - A comma separated list of tags to exclude by ID. Default = null
*   **taxonomy** - Query by custom taxonomy name. Default = null
*   **taxonomy_terms** - Comma separated list of custom taxonomy terms(slug). Default = null
*   **taxonomy_operator** - Operator to compare Taxonomy Terms against (IN/NOT IN). Default = ‘IN’
*   **day** - Day of the week. Default = null
*   **month** - Month of the year. Default = null
*   **year** - Year of post. Default = null
*   **taxonomy_operator** - Operator to compare Taxonomy Terms against (IN/NOT IN). Default = ‘IN’
*   **meta_key** - Custom field key(name). Default = null
*   **meta_value** - Custom field value. Default = null
*   **meta_compare** - Operator to compare meta_key and meta_value against (IN/NOT IN/=/!=/>/>=/</<= etc.). Default = ‘IN’
*   **author** - Query by author id. Default = null
*   **search** - Query search term (‘s’). Default = null
*   **post__in** - Comma separated list of post ID’s to include in query. Default = null 
*   **post__not_in** - Comma separated list of post ID’s to exclude from query. Default = null 
*   **post_status** - Select status of the post. Default = 'publish'
*   **order** - Display posts in ASC(ascending) or DESC(descending) order. Default = ‘DESC’
*   **orderby** - Order posts by date, title, name, menu order, random, author, post ID or comment count.  Default = ‘date’
*   **offset** - Offset the initial query (number). Default = ’0′
*   **posts_per_page** - Number of posts to load with each Ajax request. Default = ’6′
*   **paging** - Enable Easy Query to page the results. Default = ’true′

***

= Example Shortcode =

    [easy_query type="ul" classes="blog-listing entry-list" template="default" posts_per_page="6" post_type="post, portfolio"]

***

= Demos =
* **[Default](https://connekthq.com/plugins/easy-query/examples/default/)** - Out of the box functionality and styling
* **[Multiple Instances](https://connekthq.com/plugins/easy-query/examples/multiple-instances/)** - Include multiple Easy Query instances on a single page
* **[Owl Carousel](https://connekthq.com/plugins/easy-query/examples/owl-carousel/)** - Creating a responsive jQuery carousel with Owl Carousel


= Tested Browsers =

* Firefox (Mac, PC)
* Chrome (Mac, PC, iOS, Android)
* Safari (Mac, iOS)
* IE8+
* Android (Native)
* BB10 (Native)

***

= Website =
https://connekthq.com/easy-query/


== Frequently Asked Questions ==


= What are the steps to getting Easy Query to display on my website =

1. Create your shortcode
2. Add the shortcode to your page, by adding it through the content editor or placing it directly within one of your template files.
3. Load a page with your shortcode in place and watch Easy Query load your posts. 

= What are my server requirements? =

Your server must be able to read/write/create files. Easy Query creates the default template on plugin activation and in order to modify the output Easy Query is required to write to the file as well. 

= Can I make modifications to the plugin code? =

Sure, but please be aware that if modifications are made it may affect future updates of the plugin.

= Can I modify the repeater template? =

Yes, visit the Repeater Template section in your WordPress admin.

= How are my templates saved? =

Template data is saved into your WordPress database as well as written directly to a template PHP file in the easy-query plugin directory.

= Can I use custom fields in a template? =

Yes, but you will need to define $post at the top of the template before requesting your custom fields. Like so:
global $post;


== Installation ==

How to install Easy Query.

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Easy Query'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `easy-query.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `easy-query.zip`
2. Extract the `easy-query` directory to your computer
3. Upload the `easy-query` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Screenshots ==

1. Settings Screen
2. Templates
3. Query Builder
3. Query Generator


== Changelog ==


= 1.0.0 =
* Initial Easy Query Commit

== Upgrade Notice ==

* None 


