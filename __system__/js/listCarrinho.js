function listCarrinho() {
    $.ajax({
        url: BASE_URL + 'functions/listCarrinho',
        dataType: 'json',
        beforeSend: function() {
            $('.divShowProdFav').html(loadingRes("Verificando carrinho..."));
        },
        success: function(json) {
            if(!json['empty']) {
                $('.divShowProdFav').html(`<tr class="trNames">
                <th>PRODUTO</th>
                <th>QUANTIDADE</th>
                <th>PREÇO</th>
                <th>SUBTOTAL</th>
                <th></th>
                </tr>`);
                for(var i = 0; json['prods'].length > i; i++) {
                    if(json['prods'][i].produto_desconto_porcent) {
                        $('.divShowProdFav').append(`
                            <tr class="trCart">          
                                <td class="tdCart" width="40%">
                                    <img class="imgCart" src="` + BASE_URL2 + `admin_area/imagens_produtos/` + json['prods'][i].produto_img + `"/>
                                    <h5 class="titleProdCart">` + json['prods'][i].produto_nome + ` - ` + json['prods'][i].produto_tamanho + `</h5>
                                    <h5 class="brandProdCart">` + json['prods'][i].marca_nome + `</h5>
                                </td>
                                <td class="tdCart" width="15%">
                                    <input type='number' min='0' max='20' class="qtdProdCart" id-prod="` + json['prods'][i].produto_id + `" value='` + json['prods'][i].carrinho + `'>
                                </td>
                                <td class="tdCart" width="15%">
                                    <h3 class="descProdCart">R$` + json['prods'][i].produto_preco + `</h3>
                                    <h3 class="priceProdCart">R$` + json['prods'][i].produto_desconto + `</h3>
                                </td>
                                <td class="tdCart" width="20%">
                                    <h3 class="priceProdCart">R$` + json['prods'][i].subtotal + `</h3>
                                </td>
                                <td class="tdCart" width="20%">
                                    <button class="tirarProd btnProdCart" id-prod="` + json['prods'][i].produto_id + `"><i class="far fa-times-circle"></i></button>
                                </td>
                            </tr>
                        `);
                    } else {
                        $('.divShowProdFav').append(`
                        <tr class="trCart">
                            <td class="tdCart" width="40%">
                                <img class="imgCart" src="` + BASE_URL2 + `admin_area/imagens_produtos/` + json['prods'][i].produto_img + `"/>
                                <h5 class="titleProdCart">` + json['prods'][i].produto_nome + ` - ` + json['prods'][i].produto_tamanho + `</h5>
                                <h5 class="brandProdCart">` + json['prods'][i].marca_nome + `</h5>
                            </td>
                            <td class="tdCart" width="20%">
                                <input type='number' min='0' max='20' class="qtdProdCart" id-prod="` + json['prods'][i].produto_id + `" value='` + json['prods'][i].carrinho + `'>
                            </td>
                            <td class="tdCart" width="20%">
                                <h3 class="descProdCart">-</h3>
                                <h3 class="priceProdCart">R$` + json['prods'][i].produto_preco + `</h3>
                            </td>
                            <td class="tdCart" width="20%">
                                <h3 class="priceProdCart">R$` + json['prods'][i].subtotal + `</h3>
                            </td>
                            <td class="tdCart" width="20%">
                            <button class="tirarProd btnProdCart" id-prod="` + json['prods'][i].produto_id + `"><i class="far fa-times-circle"></i></button>
                            </td>
                        </tr>
                        `);
                    }
                }
                $('.divShowTot').html(`
                    <h2 class="summaryTitle">RESUMO</h2>
                    <h2 class="totalDesc">TOTAL DE DESCONTOS: R$` + json['totDesconto'] + `</h2>
                    <h2 class="totalPrice">TOTAL DA COMPRA: R$` + json['totCompra'] + `</h2>
                    `);
                $('.divShowOpt').html(`
                    <button class="limparCart">LIMPAR CARRINHO</button>
                    <button class="finalizaCompra">PRÓXIMA ETAPA</button>
                    <a href="` + BASE_URL + `home">Continuar compra</button>`);
                attCarrinho();
            } else {
                $('.divShowProdFav').html("Sem produtos no carrinho!");
                $('.divShowTot').html("");
                $('.divShowOpt').html("");
            }
        }
    });
}

listCarrinho();