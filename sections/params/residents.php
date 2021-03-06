<?php
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
$today = new DateTime();
$today = $today->format( 'Ymd' );
$paged = 1;
$post_type = $post->post_type;
$delay = $post->delay;
$page_query = array(
	'key' => 'residency_dates_0_end_date',
	'type' => 'DATE',
	'value' => $today,
);
$filter_query = array();
if( $query_vars ):
	$slug = $query_vars['pagename'];
	$filter_param = $query_vars['filter'];
	$page_param = $filter_param . '-residents';
	$page_type = $query_vars['pagetype'];
	if( $page_param == $slug || $page_type == 'sponsor' || $page_param == 'all-'.$slug ):
		$paged = $query_vars['paged'];
		$post = get_page_by_path( $slug, OBJECT, 'page' );
		$page_param = $slug;
		$country_param = $query_vars['from'];
		$year_param = $query_vars['date'];
		$program_param = $query_vars['program'];
		$type_param = $query_vars['type'];
	endif;
else:
	$filter_param = get_query_var( 'filter' );
	$page_param = $filter_param . '-residents';
	if( $page_param == $slug || $post_type == 'sponsor' || $page_param == 'all-'.$slug ):
		$country_param = get_query_var( 'from' );
		$year_param = get_query_var( 'date' );
		$program_param = get_query_var( 'program' );
		$type_param = get_query_var( 'type' );
	endif;
endif;
if( $slug == 'current-residents' ):
	$page_query = array_merge(
		$page_query, array(
			'compare' => '>=',
		)
	);
	$orderby_array = array(
		'meta_key' => 'studio_number',
		'orderby' => 'meta_value_num post_title',
		'order' => 'ASC'
	);
	$resident_status = 'current';
	$alt_slug = 'past-residents';
elseif( $slug == 'past-residents' ):
	$page_query = array_merge(
		$page_query, array(
			'compare' => '<'
		)
	);
	$orderby_array = array(
		'meta_key' => 'residency_dates_0_end_date',
		'orderby' => array(
			'meta_value_num' => 'DESC',
			'post_title' => 'ASC'
		)
	);
	$resident_status = 'past';
	$alt_slug = 'current-residents';
elseif( $post_type == 'sponsor' || $page_type == 'sponsor' || $post_type == 'residents' ||  $slug == 'residents' ):
	$page_query = null;
	$orderby_array = array(
		'meta_key' => 'residency_dates_0_end_date',
		'orderby' => array(
			'meta_value_num' => 'DESC',
			'post_title' => 'ASC'
		)
	);
endif;

if( $country_param ):
	$country_param_obj = get_page_by_path( $country_param, OBJECT, 'country' );
	$country_param_title = $country_param_obj->post_title;
	$country_param_id = $country_param_obj->ID;
	$country_query = array(
		'key' => 'country',
		'value' => '"' . $country_param_id . '"',
		'compare' => 'LIKE'
	);
endif;

if( $year_param ):
	$year_begin = $year_param . '0101';
	$year_end = $year_param . '1231';
	$year_range = array( $year_begin, $year_end );
	$year_query = array(
		'key' => 'residency_dates_0_end_date',
		'type' => 'DATE',
		'value' => $year_range,
		'compare' => 'BETWEEN'
	);
endif;

if( $program_param ):
	$program_query = array(
		'key' => 'residency_program',
		'type' => 'CHAR',
		'value' => $program_param,
		'compare' => 'LIKE'
	);
endif;

if( $sponsor_param ):
	$sponsor_query = array(
		'relation' => 'OR'
	);
	for($i = 0; $i < 10; $i++) {
		$sponsor_query[] = array(
			'key' => 'residency_dates_' . $i . '_sponsors',
			'value' => '"' . $sponsor_id . '"',
			'compare' => 'LIKE'
		);
	}
endif;

if( $type_param ):
	$type_query = array(
		'key' => 'resident_type',
		'type' => 'CHAR',
		'value' => $type_param,
		'compare' => 'LIKE'
	);
endif;

if( $post_type == 'sponsor' || $page_type == 'sponsor' ):
	$sponsor_id = get_page_by_path( $slug, OBJECT, 'sponsor' )->ID;
	$sponsor_query = array(
		'relation' => 'OR'
	);
	for($i = 0; $i < 10; $i++) {
		$sponsor_query[] = array(
			'key' => 'residency_dates_' . $i . '_sponsors',
			'value' => '"' . $sponsor_id . '"',
			'compare' => 'LIKE'
		);
	}
endif;
$query = array(
	'post_type' => 'resident',
	'posts_per_page' => 12,
	'paged' => $paged,
	'post_status' => 'publish',
	'meta_query' => array(
		'relation' => 'AND',
		$page_query,
		$filter_query,
		$country_query,
		$year_query,
		$program_query,
		$sponsor_query,
		$type_query
	)
);
$query += $orderby_array;
$count_query = array(
	'post_type' => 'resident',
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'meta_query' => array(
		'relation' => 'AND',
		$page_query,
		$sponsor_query
	)
);
?>