$(document).ready(function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    });

    $('.addFavorito').click(function(e) {
        e.preventDefault();
        var dado = 'add_prod_id=' + $(this).attr('id');

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/favoritar',
            success: function(json) {
                if(json['error']) {
                    Toast.fire({
                        type: 'error',
                        title: json["error"]
                    });
                } else {
                    Toast.fire({
                        type: 'success',
                        title: 'Produto adicionado aos favoritos'
                    });
                }
            }
        });
        btnFavorito();
    });

    $('.remFavorito').click(function(e) {
        e.preventDefault();
        var dado = 'rem_prod_id=' + $(this).attr('id');

        $.ajax({
            dataType: 'json',
            type: 'post',
            data: dado,
            url: BASE_URL + 'functions/favoritar',
            success: function(json) {
                if(json['error']) {
                    Toast.fire({
                        type: 'error',
                        title: json["error"]
                    });
                } else {
                    Toast.fire({
                        type: 'success',
                        title: 'Produto removido dos favoritos'
                    });
                }
            }
        });
        btnFavorito();
    });
});