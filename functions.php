<?php

function register_my_menus() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Main Menu' ),
      'footer-menu' => __( 'Footer Menu' )
    )
  );
}
add_action( 'init', 'register_my_menus' );



//We're going to pre build and publish static pages!
add_action('admin_init','publish_static_pages');
function publish_static_pages(){
    $args = array(
        'sort_order' => 'asc',
        'sort_column' => 'post_title',
        'hierarchical' => 1,
        'exclude' => '',
        'include' => '',
        'meta_key' => '',
        'meta_value' => '',
        'authors' => '',
        'child_of' => 0,
        'parent' => -1,
        'exclude_tree' => '',
        'number' => '',
        'offset' => 0,
        'post_type' => 'page',
        'post_status' => 'publish'
    ); 
    $pages = get_pages($args); // get all pages based on supplied args

    foreach($pages as $page){
        
        $pageID = $page->ID; 
        $homedir = '../../';
        $dirname = get_post_field( 'post_name', $pageID );
        $filedir = ($homedir . "/" . $dirname);
        $newfilepath = ($filedir . "/index.html");

        //if new file directory doesn't exist, then create one
        if( is_dir(filedir) === false )
        {
            mkdir($filedir);
        }
        
        //Render Main Navigation String
        $mainNavItems = wp_get_nav_menu_items('main-navigation');
        $mainNavString = "<ul>";
        foreach ($mainNavItems as $mainNavItem){
            $navItemURL = $mainNavItem->url;
            $newNavItemURL = str_replace("/admin/","/",$navItemURL);
            $mainNavString .= '<li><a href="'. $newNavItemURL .'" title="'.$mainNavItem->title.'">'.$mainNavItem->title.'</a></li>';
        }
        $mainNavString .= "<ul>";
        
        
        //Get Page Content
        $page_content = apply_filters('the_content', get_post_field('post_content', $pageID));
        
        
        //Render Footer Navigation String
        $footerNavItems = wp_get_nav_menu_items('footer-navigation');
        $footerNavString = "<ul>";
        foreach ($footerNavItems as $footerNavItem){
                $footerNavString .= '<li><a href="'.$footerNavItem->url.'" title="'.$footerNavItem->title.'">'.$footerNavItem->title.'</a></li>';
        }
        $footerNavString .= "<ul>";
    
        $html='
<html>
    <head>
    </head>
    <body>
        <header>
            <nav id="main-nav">' . $mainNavString . '</nav>
        </header>
        <section id="content">
            <h1 id="page-title">' . get_the_title($pageID) . '</h1>
            <div id="page-content">' . $page_content . '</div>
        </section>
        <footer>
            <nav id="footer-nav">' .$footerNavString . '</nav>
        </footer>
    </body>
</html>
       ';
        
        //write to new file, or overwrite current one
        $homeID = get_option('page_on_front');
        if($homeID = $pageID){
            $newfile = fopen("../../index.html", "w") or die("Unable to open file!");
            fwrite($newfile, $html);
            fclose($newfile);
        }
        $newfile = fopen($newfilepath, "w") or die("Unable to open file!");
        fwrite($newfile, $html);
        fclose($newfile);
    }
}