    <div class="divCadProduto divCadProdutoArmazem">
        <form class="formInserirProdutoArmazem">
            <div class="divProdutoArmazem">
                <div style="margin-bottom:60px;">
                    <div>
                        <table width="auto" align="center" border="2">
                            <tr>
                                <td align="center"><b>Armazém</b></td>
                                <td>
                                    <select name="armazem[]">
                                        <option value="*000*">--- Selecione o armazém: ---</option>
                                        <?php
                                            $sel = $conn->prepare("SELECT * FROM armazem AS a JOIN cidade AS c ON a.cidade_id=c.cid_id JOIN estado AS e ON c.est_id=e.est_id");
                                            $sel->execute();
                                            if($sel->rowCount() > 0):
                                                $results = $sel->fetchAll();
                                                foreach($results as $k => $v):?>
                                                    <option value="<?= $v['armazem_id'] ?>"><?= $v['armazem_nome'] . "/" . $v['cid_nome'] . " - " . $v['est_uf']; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><b>Produto</b></td>
                                <td>
                                    <select name="produto[]">
                                        <option value="*000*">--- Selecione o produto: ---</option>
                                        <?php
                                            $sel = $conn->prepare("SELECT * FROM produto");
                                            $sel->execute();
                                            if($sel->rowCount() > 0):
                                                $results = $sel->fetchAll();
                                                foreach($results as $k => $v):?>
                                                    <option value="<?= $v['produto_id'] ?>"><?= $v['produto_nome'] . "/" . $v['produto_tamanho']; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><b>Quantidade do produto:</b></td>
                                <td><input type="text" class="qtd_prod" name="produto_qtd[]"></td>
                            </tr>
                            <tr>
                                <td align="center"><b>Preço do produto:</b></td>
                                <td><input type="text" class="money" name="produto_preco[]" size="60"></td>
                            </tr>
                            <tr>
                                <td align="center"><b>Desconto % do produto:</b></td>
                                <td><input type="text" name="produto_desconto_porcent[]" placeholder="(Não obrigatório)" size="60"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="divSubmit" align="center">
                <button type="button" class="addCadProdutoArmazem">Adicionar mais produtos</button>
            </div>
            <div class="divSubmit" align="center">
                <button type="submit" id="btnInsertProdutoArmazem"><i class="fas fa-save"></i> Cadastrar</button>
                <div class="help-block"></div>
            </div>
        </form>
    </div>
    
    <script>
        $('.addCadProdutoArmazem').click(function(e) {
            e.preventDefault();
            $('.divProdutoArmazem').append(`
            <div>
                <table width="auto" align="center" border="2">
                    <tr>
                        <td align="center"><b>Armazém</b></td>
                        <td>
                            <select name="armazem[]">
                                <option value="*000*">--- Selecione o armazém: ---</option>
                                <?php
                                    $sel = $conn->prepare("SELECT * FROM armazem AS a JOIN cidade AS c ON a.cidade_id=c.cid_id JOIN estado AS e ON c.est_id=e.est_id");
                                    $sel->execute();
                                    if($sel->rowCount() > 0):
                                        $results = $sel->fetchAll();
                                        foreach($results as $k => $v):?>
                                            <option value="<?= $v['armazem_id'] ?>"><?= $v['armazem_nome'] . "/" . $v['cid_nome'] . " - " . $v['est_uf']; ?></option>
                                            <?php
                                        endforeach;
                                    endif;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><b>Produto</b></td>
                        <td>
                            <select name="produto[]">
                                <option value="*000*">--- Selecione o produto: ---</option>
                                <?php
                                    $sel = $conn->prepare("SELECT * FROM produto");
                                    $sel->execute();
                                    if($sel->rowCount() > 0):
                                        $results = $sel->fetchAll();
                                        foreach($results as $k => $v):?>
                                            <option value="<?= $v['produto_id'] ?>"><?= $v['produto_nome'] . "/" . $v['produto_tamanho']; ?></option>
                                            <?php
                                        endforeach;
                                    endif;
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><b>Quantidade do produto:</b></td>
                        <td><input type="text" class="qtd_prod" name="produto_qtd[]"></td>
                    </tr>
                    <tr>
                        <td align="center"><b>Preço do produto:</b></td>
                        <td><input type="text" class="money" name="produto_preco[]" size="60"></td>
                    </tr>
                    <tr>
                        <td align="center"><b>Desconto % do produto:</b></td>
                        <td><input type="text" name="produto_desconto_porcent[]" placeholder="(Não obrigatório)" size="60"></td>
                    </tr>
                </table>
                <div class="btnRemove">
                    <a href="#" class="remover_div"><i class="fas fa-times"></i></a>
                </div>
            </div>
            `);
            mask();
        });

        // Remover o div anterior
        $('.divCadProduto').on("click",".remover_div",function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                $(this).parent('div').remove();
        });

        mask();
        insertProdutoArmazem();
    </script>