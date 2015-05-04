jQuery(function($){
    $('[data-toggle="tooltip"]').not('#authorizationSection *').tooltip({
        placement: "top",
        trigger: "hover focus"
    });
    
    $("#feedbackForm .detach-file").click(function(){
        $("#feedbackForm [name='file']").prop("value", "");
    });
    
    $("#feedbackForm").submit(function(){
        $("#sendLetter").prop("disabled", true).addClass("ajax-loader-sm");
        $("#feedbackIframe").contents().empty();
        $(this).find(".form-errors").empty();
    });
    
    $(document).delegate("#triggerLogin", "click", function(){
        eventManager.addTrigger(
            "feedback.sendLetter",
            $("#sendLetter"),
            "click"
        );
        $("#authorizationLinks .login").trigger("click");
    });
});

function feedbackCallback(params) {
    $("#sendLetter").removeClass("ajax-loader-sm").prop("disabled", false);
    
    switch (params.error_code) {
        case 0:
            var title = $("#feedback h3").eq(0).text(),
                content = params.modal_view;
            displayModal(title, content);
            $("#feedbackForm").find("input, textarea").val("");
            break;
        case 1:
            var title = $("#feedback h3").eq(0).text(),
                content = params.modal_view;
            displayModal(title, content);
            break;
        case 2:
            for (var item in params.message) {
                var msg = "";
                for (var rule in params.message[item]) {
                    msg += params.message[item][rule];
                }
                $("#feedbackForm").find(".form-errors[ref='" + item + "']").text(msg);
            }
            break;
        case 3:
            $("#feedbackForm").find("[ref='file']").text(params.message);
            break;   
    }
}

function displayModal(title, content) {
    $("#modal .modal-title, #modal .modal-body, #modal .modal-footer").empty();
    $("#modal .modal-title").text(title);
    $("#modal .modal-body").append(content);
    $("#modal").modal("show");
}