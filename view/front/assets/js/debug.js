jQuery(function($){
    var informations = new Array();
    if ( ! document.createTreeWalker ) {
        throw( new Error( "Browser does not support createTreeWalker()." ) );
    }

    function filter(node) {
        return(NodeFilter.FILTER_ACCEPT);
    }
    filter.acceptNode = filter;
    var treeWalker = document.createTreeWalker(
        document.body,
        NodeFilter.SHOW_COMMENT,
        filter,
        false
    );

    while ( treeWalker.nextNode() ) {
        if (treeWalker.currentNode.nodeValue.substring(1, 2) != "/") {
            informations.push(treeWalker.currentNode.nodeValue);
        }
    }

    var html = '';
    html += '<div id="debug_pgcms"><ul class="list-group">';
    for (key in informations) {
        var listStyle = "list-group-item-danger";
        if (informations[key].substring(1, 12) == "Render Zone") {
            var listStyle = "list-group-item-info";
        }
        if (informations[key].substring(1, 13) == "Render Block") {
            var listStyle = "list-group-item-success";
        }
        html += '<li class="list-group-item '+listStyle+'">'+informations[key]+'</li>';
    }
    html += '</ul></div>';
    $("body").append(html);
    $("#debug_pgcms").hide();

    $("#debug").click(function(){
        $("#debug_pgcms").toggle();
    });

    var $sidebar   = $("#debug_pgcms"), 
        $window    = $(window);

    $window.scroll(function() {
        $sidebar.stop().animate({
            top: $window.scrollTop() + 60
        });
    });
});