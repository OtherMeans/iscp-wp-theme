<?php 
global $post;
setup_postdata( $post );
$page_id = $the_ID;
$title = get_the_title( $page_id );
$permalink = get_permalink();
echo '<div class="sponsor item shelf-item border-bottom no-image">';
echo '<div class="inner">';
echo '<a class="value name" href="' . $permalink . '">';
echo '<h2 class="link title">' . $title . '</h2>';
echo '</a>';
echo '<div class="value country"><a href="' . $country_permalink . '">' . $country . '</a></div>';
if($website):
	echo '<div class="value website">';
	echo '<a href="' . $website . '" target="_blank">' . $pretty_website . '</a>';
	echo '</div>';
endif;
echo '</div>';
echo '</div>';
wp_reset_postdata();
?>