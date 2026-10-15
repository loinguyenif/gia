<?php get_header(); ?>
<?php
if(isset($_POST['search-type']) && $_POST['search-type']) { 
    load_template(TEMPLATEPATH . '/'.$_POST['search-type'].'.php');
}else{
    load_template(TEMPLATEPATH . '/search-default.php');
}

?>
<?php get_footer(); ?>