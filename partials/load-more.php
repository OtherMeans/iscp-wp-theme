<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$max_pages = $wp_query->max_num_pages;
	echo '<div class="clear load-more">';
	echo '<a href="#">Load More</a>';
	echo '<div class="loader"><div></div><div></div><div></div></div>';
	echo '</div>';
?>