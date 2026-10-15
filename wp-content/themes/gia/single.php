<?php
get_header();
?>
<?php 
$template = 'news';
foreach((get_the_category()) as $category){
    $single_template = get_field('single_template', $category);
    if($single_template){
        $template = $single_template;
    }
}

get_template_part( 'template-parts/single_'.$template, 'post'); 
?>
<?php get_footer(); ?>
