<?php
/* By taking advantage of hooks, filters, and the Custom Loop API, you can make Thesis
 * do ANYTHING you want. For more information, please see the following articles from
 * the Thesis User’s Guide or visit the members-only Thesis Support Forums:
 * 
 * Hooks: http://diythemes.com/thesis/rtfm/customizing-with-hooks/
 * Filters: http://diythemes.com/thesis/rtfm/customizing-with-filters/
 * Custom Loop API: http://diythemes.com/thesis/rtfm/custom-loop-api/

---:[ place your custom code below this line ]:---*/

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

