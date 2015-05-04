jQuery(function($){
    $("#authorizationLinks a").tooltip({
        placement: "bottom",
        trigger: "hover focus"
    });
    // Show register or login form
    $(document).delegate(".register, .login", "click", function(){
        var label = $(this).attr("alt"),
            targetUrl = $(this).attr("href");
        setModal(label, targetUrl);
    });
    // Login
    $(document).delegate("#loginForm button[type='submit']", "click", function(){
        var targetUrl = $("#loginForm").attr("action"),
            data = {
                email: $.trim($("#loginForm input[name='email']").val()),
                password: $("#loginForm input[name='password']").val()
            };  
        doLogin(targetUrl, data);
    });
    // Register
    $(document).delegate("#registerForm button[type='submit']", "click", function(){
        var targetUrl = $("#registerForm").attr("action"),
            data = {
                username: $.trim($("#registerForm input[name='username']").val()),
                email: $.trim($("#registerForm input[name='email']").val()),
                password: $("#registerForm input[name='password']").val(),
                confirm_password: $("#registerForm input[name='confirm_password']").val()
            };
        doRegister(targetUrl, data);
    });
    // Register confirm
    $(document).delegate("#registerConfirmBox button[name='confirm']", "click", function(){
        var code = $.trim($("#registerConfirmBox input[name='code']").val()),
            targetUrl = $.trim($(this).val()) + "/" + code;
        registerConfirm(targetUrl);
    });
});

function doLogin(targetUrl, data) {
    $.ajax({
        type: "POST",
        url: targetUrl,
        dataType: "json",
        data: data,
        beforeSend: function(){
            $("#loginForm .form-errors").empty();
            $("#loginForm button[type='submit']").prop("disabled", true).addClass("ajax-loader-sm");
        },
        complete: function(){
            $("#loginForm button[type='submit']").removeClass("ajax-loader-sm").prop("disabled", false);
        },
        success: function(data){
            if (data.logged === true) {
                $("#modal").modal("hide");
                $(".logged-yes").removeClass("hide");
                $(".logged-no").addClass("hide");
                eventManager.execTrigger("feedback.sendLetter");
            } else {
                $("#modal .modal-body").html(data.view);
            }
        },
        error: function(ajqXHR,textStatus,errorThrown){
            console.log("ajqXHR:");console.log(ajqXHR);
            console.log("errorStatus:");console.log(textStatus);
            console.log("errorThrown:");console.log(errorThrown);
        }
    });
}

function doRegister(targetUrl, data) {
    $.ajax({
        type: "POST",
        url: targetUrl,
        dataType: "html",
        data: data,
        beforeSend: function(){
            $("#registerForm button[type='submit']").prop("disabled", true).addClass("ajax-loader-sm");
        },
        complete: function(){
            $("#registerForm button[type='submit']").removeClass("ajax-loader-sm").prop("disabled", false);
        },
        success: function(html){
            $("#modal .modal-body").empty().append(html);
        },
        error: function(ajqXHR,textStatus,errorThrown){
            console.log("ajqXHR:");console.log(ajqXHR);
            console.log("errorStatus:");console.log(textStatus);
            console.log("errorThrown:");console.log(errorThrown);
        }
    });
}

function registerConfirm(targetUrl) {
    $.ajax({
        type: "POST",
        url: targetUrl,
        dataType: "json",
        data: {},
        beforeSend: function(){
            $("#registerConfirmBox .form-errors").empty();
            $("#registerConfirmBox button[name='confirm']").prop("disabled", true).addClass("ajax-loader-sm");
        },
        complete: function(){
            $("#registerConfirmBox button[name='confirm']").removeClass("ajax-loader-sm").prop("disabled", false);
        },
        success: function(data){
            if (data.logged === true) {
                $("#modal").modal("hide");
                $(".logged-yes").removeClass("hide");
                $(".logged-no").addClass("hide");
            } else {
                $("#registerConfirmBox .form-errors").text(data.error);
            }
        },
        error: function(ajqXHR,textStatus,errorThrown){
            console.log("ajqXHR:");console.log(ajqXHR);
            console.log("errorStatus:");console.log(textStatus);
            console.log("errorThrown:");console.log(errorThrown);
        }
    });
}

function setModal(label, targetUrl) {
    $("#modal .modal-title, #modal .modal-body, #modal .modal-footer").empty();
    $("#modal .modal-title").text(label);
    $("#modal").modal("show");
    $.ajax({
        type: "GET",
        url: targetUrl,
        dataType: "html",
        data: {},
        beforeSend: function(){
            $("#modal .modal-body").addClass("ajax-loader");
        },
        complete: function(){
            $("#modal .modal-body").removeClass("ajax-loader");
        },
        success: function(html){
            $("#modal .modal-body").append(html);
        },
        error: function(ajqXHR,textStatus,errorThrown){
            console.log("ajqXHR:");console.log(ajqXHR);
            console.log("errorStatus:");console.log(textStatus);
            console.log("errorThrown:");console.log(errorThrown);
        }
    });
}
