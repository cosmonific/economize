const BASE_URL = "http://localhost/BackupGit/economize/TCCSystem/";
const BASE_URL2 = "http://localhost/BackupGit/economize/TCCSystem/__system__/";
const BASE_URL3 = "http://localhost/BackupGit/economize/TCCSystem/__system__/admin_area/imagens_produtos/";

function loadingRes(message="") {
    return "<p><i class='fa fa-circle-notch fa-spin'></i> &nbsp;"+message+"</p>";
}

function clearErrors() {
    $(".has-error").removeClass("has-error");
    $(".help-block").html("");
    $(".help-block-login").html("");
}

function showErrors(error_list) {
    clearErrors();
    $.each(error_list, function(id, message) {
        $(id).parent().siblings(".help-block").html(message);
    })
}

function messages() {
    $.ajax({
        dataType: 'json',
        url: 'functions/messages.php',
        success: function(json) {
            if(json["message"]) {
                Swal.fire({title: json["title"],
                    text: json["text"],
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#d9534f",
                    confirmButtonText: "Ok",
                });
            }
        }
    });
}