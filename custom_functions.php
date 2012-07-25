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