function buildPage(siteRoot,pageID){
    
    console.log('Starting to build page at '+ siteRoot + ' with page ID ' + pageID +'...');
    
    //get page navigation
    $.getJSON('http://localhost:8888/wp-json/menus/v1/menus/main-navigation', function(navitems){
        $(navitems.items).each(function(itemNum){
            $('#main-nav').append("<li>" + (navitems.items[itemNum].title) + "</li>");
        });
    });

    //get page content
    $.getJSON('http://localhost:8888//wp-json/wp/v2/pages/11', function (pagecontent) {
        console.log(pagecontent.content);
        $('#page-content').html(pagecontent.content.rendered);
        $('#page-title').html(pagecontent.title.rendered);
    });

    //get footer navigation
    $.getJSON('http://localhost:8888/wp-json/menus/v1/menus/footer-navigation', function(navitems){
        $(navitems.items).each(function(itemNum){
            $('#footer-nav').append("<li>" + (navitems.items[itemNum].title) + "</li>");
        });
    });
};