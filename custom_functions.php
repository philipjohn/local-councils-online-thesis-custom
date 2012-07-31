<?php
/* By taking advantage of hooks, filters, and the Custom Loop API, you can make Thesis
 * do ANYTHING you want. For more information, please see the following articles from
 * the Thesis User’s Guide or visit the members-only Thesis Support Forums:
 * 
 * Hooks: http://diythemes.com/thesis/rtfm/customizing-with-hooks/
 * Filters: http://diythemes.com/thesis/rtfm/customizing-with-filters/
 * Custom Loop API: http://diythemes.com/thesis/rtfm/custom-loop-api/

---:[ place your custom code below this line ]:---*/

// Initial sanity check
if (! defined('ABSPATH'))
	die('Please do not directly access this file');


/*
 * Make it responsive
 */
class thesis_skin_example extends thesis_custom_loop {
		
	public function __construct() {
		// run the parent constructor so we can access the thesis custom loop api
		parent::__construct();
		
		// run the main init
		add_action('init', array($this, 'init'));
	}
	
	public function init() {
		// actions and filters that will run on init. you could put other things here if you need.
		$this->actions();
		$this->filters();
		$this->switch_skin();
	}
	
	public function actions() {
		// add and remove actions here
		
		// needed to scale the site down on mobile
		add_action('wp_head', array($this, 'meta_tags'), 1);
		// modify the nav menu to exclude the div wrapper that WP defaults to
		remove_action('thesis_hook_before_header', 'thesis_nav_menu');
		add_action('thesis_hook_before_header', array($this, 'nav'));
	}
	
	public function filters() {
		// add and remove filters here
		
		/* 
		*	Filter out the standard thesis style.css. 
		*	Run this with a priority of 11 if you want to make sure the gravity forms css gets added.
		*/
		add_filter('thesis_css', array($this, 'css'), 11, 4);
	}
	
	public function css($contents, $thesis_css, $style, $multisite) {
		
		// filter the Thesis generated css.
		$generated_css = $this->filter_css($thesis_css->css);
		
		/* 
		*	You can access the thesis_css object, which contains a variety of settings. 
		*/
		$responsive_css = "\n/*---:[ responsive resets ]:---*/\n"
			. "@media screen and (max-width: " . round((($thesis_css->widths['container'] + ($thesis_css->base['page_padding'] * 2)) / $thesis_css->base['num']) * 10, 1) . "px) {\n"
			. "\t.full_width > .page, #container, #page, #column_wrap, #content, #sidebars, #sidebar_1, #sidebar_2 { width: " . round((($thesis_css->widths['container']) / $thesis_css->base['num']) / 1.6, 1) . "em; }\n"
			. "\t#content_box, #column_wrap { background: none; }\n"
			. "\t#sidebar_1 { border: 0; }\n"
			. "\t#column_wrap, #content, #sidebars, #sidebar_1, #sidebar_2, .teaser { float: none; }\n"
			. "\t#comments { margin-right: 0; }\n"
			. "\t#multimedia_box #image_box img { height: auto; width: " . round(($thesis_css->widths['container'] - ($thesis_css->widths['mm_box_padding'] * 2)) / $thesis_css->base['num'] / 1.6, 1) . "em; }\n"
			. "\t.teasers_box { margin: 0 " .  round(($thesis_css->widths['post_box_margin_right'] / $thesis_css->base['num']) / 2, 1) . "em; padding-bottom: 0; }\n"
			. "\t.teasers_box, .teaser { width: auto; }\n"
			. "\t.teaser { padding-bottom: " . round(($thesis_css->line_heights['content'] / $thesis_css->base['num']), 1) . "em; }\n"
			. "\t.custom .wp-caption { width: auto!important; } /* overrides inline style */\n"
			. "}\n"
			. "\n@media screen and (max-width: " . round((($thesis_css->widths['container'] + ($thesis_css->base['page_padding'] * 2)) / $thesis_css->base['num']) * 10 / 1.6, 1) . "px) {\n"
			. "\t.full_width > .page, #page { padding: 0; }\n"
			. "\t.full_width > .page, #container, #page, #column_wrap, #content, #multimedia_box #image_box img, #sidebars, #sidebar_1, #sidebar_2 { width: 100%; }\n"
			. "\t.custom img.alignleft, .custom img.left, .custom img.alignright, .custom img.right, .custom img[align=\"left\"], .custom img[align=\"right\"] { display: block; margin-left: auto; margin-right: auto; float: none; clear: both; }\n"
			. "\t.footer-widgets { width: 100% !important; }"
			. "}\n"
		;
		
		// put in everything except the main thesis style.css. also add an initial css reset
		$css = $thesis_css->fonts_to_import . $style . $this->css_reset . $generated_css . $responsive_css;
		
		// return it
		return $css;
	}
	
	public function filter_css($css) {
		if (! empty($css)) {
			// you could add filtering here
		}
		return $css;
	}

	public function meta_tags()
	{
		// scales site for mobile devices
		echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">' . "\n";
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
	}

	public function nav() {
		// we're doing this so we can remove the default container div output by WordPress
		global $thesis_site;
		if (function_exists('wp_nav_menu') && $thesis_site->nav['type'] == 'wp') { #wp
			$args = array(
				'theme_location' => 'primary',
				'container' => '',
				'fallback_cb' => 'thesis_nav_default'
			);
			wp_nav_menu($args); #wp
			echo "\n";
		}
		else
			thesis_nav_default();
	}
	
	public function switch_skin() {
		//	Since after_switch_theme won't run, let's make sure that we generate the CSS
		if (is_admin() && ! get_option(__CLASS__ . '_generate')) {
			thesis_generate_css();
			update_option(__CLASS__ . '_generate', 1);
			wp_cache_flush();
		}
		else return null;
	}
}
new thesis_skin_example;

/*
 * Returns text-indent to title when necessary
 */
function lco_title_indent(){
	global $thesis_design;
	if ($thesis_design->display['header']['title']){ //only if title is set to be shown
		echo '<style type="text/css"> #logo a { text-indent: 0; } </style>';
	}
}
add_action('wp_head', 'lco_title_indent');

/*
 * Add 3-column widgetised footer
 */
if ( function_exists('register_sidebar') )
	register_sidebar(array(
	'name' => 'Footer Widgets Left',
	'before_widget' => '<li class="widget %2$s" id="%1$s">',
	'after_widget' => '</li>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
	'name' => 'Footer Widgets Middle',
	'before_widget' => '<li class="widget %2$s" id="%1$s">',
	'after_widget' => '</li>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
	'name' => 'Footer Widgets Right',
	'before_widget' => '<li class="widget %2$s" id="%1$s">',
	'after_widget' => '</li>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));

function my_widgetized_footer() { ?>
<div id="footer-widget-block">
	<div class="my-footer-one footer-widgets sidebar">
		<ul class="sidebar_list">
			<?php thesis_default_widget(3); ?>
		</ul>
	</div>

	<div class="my-footer-two footer-widgets sidebar">
		<ul class="sidebar_list">
			<?php thesis_default_widget(4); ?>
		</ul>
	</div>

	<div class="my-footer-three footer-widgets sidebar">
		<ul class="sidebar_list">
			<?php thesis_default_widget(5); ?>
		</ul>
	</div>
</div>
			<?php
	}
add_action('thesis_hook_footer','my_widgetized_footer','1');

