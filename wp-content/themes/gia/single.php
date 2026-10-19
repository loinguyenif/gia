<?php
get_header();
?>
<?php 
$template = 'default';
foreach((get_the_category()) as $category){
    $template = $category->slug;
}

get_template_part( 'template-parts/single_'.$template, 'post'); 
?>
<?php get_footer(); ?>
