$(document).ready(function() {
    attCarrinho();
    
    $('.pesquisaTxtHeader').keyup(function(e) {
        e.preventDefault();
        if($(this).val().length > 0) {
            var dado = "buscaBarra=" + $(this).val();
            $.ajax({
                dataType: 'json',
                url: BASE_URL + 'functions/listSearch',
                type: 'post',
                data: dado,
                beforeSend: function() {
                    $('.tituloOfertas').html(`Sua pesquisa sobre: ` + $('.pesquisaTxtHeader').val());
                },
                success: function(json) {
                    if(!json['empty']) {
                        var produtos = [];
                        $('.divShowProdFav').html("");
                        for(var i = 0; json['prods'].length > i; i++) {
                            if(json['prods'][i].produto_desconto_porcent) {
                                produtos[i] = `
                                    <div class="prodFilter">
                                        <div class='btnFavoriteFilter btnFavorito` + json['prods'][i].produto_id + `'>
                                            
                                        </div>
                                        <img src='` + BASE_URL2 + `admin_area/imagens_produtos/` + json['prods'][i].produto_img + `'/>
                                        <p class="divProdPromo">-` + json['prods'][i].produto_desconto_porcent + `%</p>
                                        <div class='divisorFilter'></div>
                                        <h5 class='titleProdFilter'>` + json['prods'][i].produto_nome + ` - `  + json['prods'][i].produto_tamanho + `</h5>
                                        <p class='priceProdFilter'><span class="divProdPrice1">R$` + json['prods'][i].produto_preco + `</span> R$` + json['prods'][i].produto_desconto + `</p>
                                        <div>
                                `;
                                if(!json['prods'][i].empty) {
                                    produtos[i] += `
                                                <form class="formBuy">
                                                    <input type="hidden" value="` + json['prods'][i].produto_id + `" name="id_prod"/>
                                                    <input type="number" min="0" max="20" value="` + json['prods'][i].carrinho + `" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                                </form>
                                            </div>
                                        </div>
                                    `;
                                } else {
                                    produtos[i] += `
                                                <span class="esgotQtd">ESGOTADO</span>
                                                <form class="formBuy">
                                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                                </form>
                                            </div>
                                        </div>
                                    `;
                                }
                            } else {
                                produtos[i] = `
                                    <div class="prodFilter">
                                        <div class='btnFavoriteFilter btnFavorito` + json['prods'][i].produto_id + `'>
                                            
                                        </div>
                                        <img src='` + BASE_URL2 + `admin_area/imagens_produtos/` + json['prods'][i].produto_img + `'/>
                                        <div class='divisorFilter'></div>
                                        <h5 class='titleProdFilter'>` + json['prods'][i].produto_nome + ` - `  + json['prods'][i].produto_tamanho + `</h5>
                                        <p class='priceProdFilter'>R$ ` + json['prods'][i].produto_preco + `</p>
                                        <div>
                                `;
                                if(!json['prods'][i].empty) {
                                    produtos[i] += `
                                                <form class="formBuy">
                                                    <input type="hidden" value="` + json['prods'][i].produto_id + `" name="id_prod"/>
                                                    <input type="number" min="0" max="20" value="` + json['prods'][i].carrinho + `" class="inputBuy inputQtdFiltro" name="qtd_prod"/>
                                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                                </form>
                                            </div>
                                        </div>
                                    `;
                                } else {
                                    produtos[i] += `
                                                <span class="esgotQtd">ESGOTADO</span>
                                                <form class="formBuy">
                                                    <button class="btnBuyFilter btnBuy" type="submit">ADICIONAR</button>
                                                </form>
                                            </div>
                                        </div>
                                    `;
                                }
                            }
                        }
                        for(var c = 0; c < produtos.length; c++) {
                            $('.divShowProdFav').append(produtos[c]);
                        }
                        btnFavorito();
                        attCarrinho();
                    } else {
                        $('.divShowProdFav').html(`
                            <p class='msgHelpSearch'>
                                <h4>Não houve resposta para o que pesquisou!</h4>
                                <b>Possíveis soluções:</b><br/>
                                <b>1.</b> Tente ser bem específico ao que está procurando;<br/>
                                <b>2.</b> Tente escrever pelo menos uma palavra inteira, por exemplo 'Refrigerante' ao invés de 'Refri';<br/>
                                <b>3.</b> Não use palavras tão comuns;<br/>
                                <b>4.</b> ...<br/>
                            </p>
                        `);
                    }
                }
            });
        } else {
            $('.tituloOfertas').html(`Pesquise seu produto no campo acima`);
            $('.divShowProdFav').html(``);
        }
    });

    $('.formPesquisaHeader').submit(function(e) {
        e.preventDefault();
        return false;
    })
});