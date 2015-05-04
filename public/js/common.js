jQuery(function($){
    var menuWidth = $("#menu").outerWidth(true);
    $("body").css({"min-width": 310 + parseInt(getMenuItemMaxWidth()) + "px"});
    setHeaderStyle(menuWidth);
    $(window).resize(function(){
        setHeaderStyle(menuWidth);
    });
    
    $("#menu .anchor").click(function(e){
        e.preventDefault();
        var target = $(this).attr("href");
        $("html, body").animate({scrollTop: $(target).offset().top}, 400);
    });
    
    /*
     * set language
     */
    $("#langWrap img").click(function(){
        var lang = $(this).attr("lang");
        setCookie("lang", lang, {expires: 24*60*60});
        location.reload(true);
    });
});

function setHeaderStyle(menuWidth) {
    if (($("body").innerWidth() - 310) > menuWidth) {
        $(".menu-item").css({
            "float":"left",
            "font-size":"22px"
        });
        $("#langWrap").css({"width":"auto"});
        $("#authorizationLinks").css({"right":"124px"});
    } else {
        $(".menu-item").css({
            "float":"none",
            "font-size":"20px"
        });
        $("#langWrap").css({"width":"28px"});
        $("#authorizationLinks").css({"right":"60px"});
    }
}

function getMenuItemMaxWidth() {
    var maxWidth = 0, currentWidth;
    $(".menu-item").each(function(){
        currentWidth = $(this).innerWidth();
        if (currentWidth > maxWidth) {
            maxWidth = currentWidth;
        }
    });
    return maxWidth;
}

