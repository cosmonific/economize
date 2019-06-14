<?php 
	require_once 'connection/conn.php';

    if(isXmlHttpRequest()) {
        if(isset($_POST["usu_cep"])) {
            $json = array();
            $json["status"] = 1;
            $json["error_list"] = array();
            $json['armazem'] = 1;
            
            if(empty($_POST["usu_cep"])) {
                $json["error_list"]["#usu_cep"] = "<p class='msgErrorCad'>Por favor, insira o CEP do seu logradouro ou da sua cidade neste campo</p>";
            } else {
                if(strlen($_POST['usu_cep']) < 9) {
                    $json["error_list"]["#usu_cep"] = "<p class='msgErrorCad'>Por favor, insira seu CEP corretamente neste campo</p>";
                } else {
                    if(empty($_POST["usu_uf"])) {
                        $json["error_list"]["#usu_uf"] = "<p class='msgErrorCad'>Por favor, insira um <b>CEP</b> válido para que o endereço seja preenchido automaticamente</p>";
                    } else {
                        if(empty($_POST["usu_end"])) {
                            $json["error_list"]["#usu_end"] = "<p class='msgErrorCad'>Por favor, insira seu logradouro neste campo</p>";
                        } else {
                            if(empty($_POST["usu_bairro"])) {
                                $json["error_list"]["#usu_bairro"] = "<p class='msgErrorCad'>Por favor, insira seu bairro neste campo</p>";
                            } else {
                                if(empty($_POST["usu_num"])) {
                                    $json["error_list"]["#usu_num"] = "<p class='msgErrorCad'>Por favor, insira o <b>número</b> de sua casa neste campo</p>";
                                } else {
                                    if(!is_numeric($_POST["usu_num"])) {
                                        $json["error_list"]["#usu_num"] = "<p class='msgErrorCad'>Somente números neste campo</p>";
                                    } else {
                                        $cid = $_POST["usu_cidade"] . " - " . $_POST['usu_uf'];
                                        $verifica = $conn->prepare("SELECT * FROM cidade AS c JOIN estado AS e ON c.est_id=e.est_id JOIN armazem AS a ON a.cidade_id=c.cid_id WHERE a.armazem_id={$_SESSION['arm_id']}");
                                        $verifica->execute();
                                        if($verifica->rowCount() > 0) {
                                            $permition = array();
                                            $res = $verifica->fetchAll();
                                            foreach($res as $v) {
                                                $v['juncao_cid_uf'] = $v['cid_nome'] . " - " . $v['est_uf'];
                                                if($cid != $v['juncao_cid_uf']) {
                                                    $sel = $conn->prepare("SELECT * FROM cidade AS c JOIN estado AS e ON c.est_id=e.est_id JOIN subcidade AS s ON s.cid_id=c.cid_id JOIN armazem AS a ON c.cid_id=a.cidade_id WHERE a.armazem_id={$_SESSION['arm_id']}");
                                                    $sel->execute();
                                                    $res = $sel->fetchAll();
                                                    foreach($res as $val) {
                                                        $val['juncao_cid_uf'] = $val['subcid_nome'] . " - " . $val['est_uf'];
                                                        if($cid == $val['juncao_cid_uf']) {
                                                            $permition[] = 1;
                                                            $_SESSION['subcid_id'] = $val['subcid_id'];
                                                        }
                                                    }
                                                } else {
                                                    $permition[] = 1;
                                                }
                                            }
                
                                            if(empty($permition)) {
                                                $json['status'] = 0;
                                                $json['armazem'] = 0;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if((!empty($json['error_list'])) && ($json['armazem'])) {
                $json['status'] = 0;
            } else {
                $_SESSION['end_agend'][0] = $_POST['usu_cep'];
                $_SESSION['end_agend'][1] = $_POST['usu_end'];
                $_SESSION['end_agend'][2] = $_POST['usu_num'];
                $_SESSION['end_agend'][3] = $_POST['usu_complemento'];
                $_SESSION['end_agend'][4] = $_POST['usu_bairro'];
                $_SESSION['end_agend'][5] = $_POST['usu_cidade'];
                $_SESSION['end_agend'][6] = $_POST['usu_uf'];
                
                if(strlen(trim($_POST['usu_complemento'])) > 0) {
                    $json['agend_end'] = $_POST['usu_cep'] . ", " . $_POST['usu_end'] . " nº " . $_POST['usu_num'] . " - " . $_POST['usu_complemento'] . ", " . $_POST['usu_bairro'] . ", ". $_POST['usu_cidade'] . " - " . $_POST['usu_uf'];
                } else {
                    $json['agend_end'] = $_POST['usu_cep'] . ", " . $_POST['usu_end'] . " nº " . $_POST['usu_num'] . ", " . $_POST['usu_bairro'] . ", ". $_POST['usu_cidade'] . " - " . $_POST['usu_uf'];
                }
            }
            
            echo json_encode($json);
        } elseif(isset($_POST['entrega_horario'])) {
            $json["status"] = 1;
            $hora = substr($_POST['entrega_horario'],-8);
            $dia = substr($_POST['entrega_horario'],0,10);
            $json['hora_dia'] = $hora . " | " . $dia;
            if(Date("Y-m-d") == $dia) {
                if(Date("H:i:s") >= $hora) {
                    $json['status'] = 0;
                    $json['error_list'][0] = "O horário que escolheu já expirou. Escolha outro, por favor";
                } else {
                    $json['recebeu'] = $_POST['entrega_horario'];
                    $_SESSION['agend_horario'] = $_POST['entrega_horario'];
                }
            } else {
                $json['recebeu'] = $_POST['entrega_horario'];
                $_SESSION['agend_horario'] = $_POST['entrega_horario'];
            }
            
            echo json_encode($json);
        } else {
            $hora_disp_amanha = array();
            $hora_disp_hoje = array();

            $today = Date('N');
            if($today == 7) {
                $next_day = 1;
            } else {
                $next_day = $today + 1;
            }

            $cid_entrega = $_SESSION['end_agend'][5] . " - " . $_SESSION['end_agend'][6];
            if($_SESSION['arm'] == $cid_entrega) {
                $sel = $conn->prepare("SELECT * FROM dados_horario_entrega AS d JOIN horarios_entrega AS h ON d.dados_horario=h.hora_id JOIN armazem AS a ON d.dados_armazem=a.armazem_id WHERE a.armazem_id={$_SESSION['arm_id']} AND (h.dia=$today OR h.dia=$next_day) ORDER BY h.hora");
            } else {
                $sel = $conn->prepare("SELECT * FROM subcidade AS s JOIN dados_horario_subcidade AS d ON d.dados_subcidade=s.subcid_id JOIN horarios_entrega AS h ON d.dados_horario=h.hora_id WHERE s.subcid_id={$_SESSION['subcid_id']} AND (h.dia=$today OR h.dia=$next_day) ORDER BY h.hora");
            }
            
            $sel->execute();
            $res = $sel->fetchAll();
            $i = 1;
            foreach($res as $k => $v) {
                if($v['dia'] != $next_day) {
                    $hora = $v['hora'];
                    $hoje = Date('Y-m-d');
                    if(Date("H:i:s") < $hora) {
                        $hora = substr($v['hora'],0,2) . "h" . substr($v['hora'],3,2);
                        $hora_disp_hoje[] = '<input type="radio" name="entrega_horario" id="horaAmanha' . $v['hora_id'] . '" value="' . $hoje . " " . $v['hora'] . '"/> <label for="horaAmanha' . $v['hora_id'] . '">' . $hora . "</label><br/>";
                    }
                } else {
                    $hora = substr($v['hora'],0,2) . "h" . substr($v['hora'],3,2);
                    $dia_seguinte = Date('Y-m-d', mktime(0, 0, 0, Date("m"), Date("d")+1, Date("Y")));
                    $hora_disp_amanha[] = '<input type="radio" name="entrega_horario" id="hora' . $v['hora_id'] . '" value="' . $dia_seguinte . " " . $v['hora'] . '"/> <label for="hora' . $v['hora_id'] . '">' . $hora . "</label><br/>";
                }
            }
            if(!empty($hora_disp_hoje) || !empty($hora_disp_amanha)) {
                echo '<form id="hora_agend" class="formAgendEnd">';
                if(!empty($hora_disp_hoje)) {
                    $hoje = substr($hoje,-2) . "/" . substr($hoje,5,2);
                    echo '
                        <table class="tableSectionConfigArm" width="80%" align="center">
                            <tr class="">
                                <th class="" style="text-align:center;color:#9C45EB;font-size:14px;">HOJE (' . $hoje . ')</th>
                            </tr>
                    ';
                    foreach($hora_disp_hoje as $v) {
                        if($i==1) {
                            $v = str_replace("input", "input checked", $v);
                        }
                        echo '
                            <tr class="">
                                <td class="" style="text-align:center;font-size:13px;"><b>' . $v . '</b></td>
                            </tr>
                        ';
                        $i++;
                    }
                    echo '
                        </table>
                        <br>
                    ';
                }
                if(!empty($hora_disp_amanha)) {
                    if(count($hora_disp_hoje) <= 1) {
                        $dia_seguinte = substr($dia_seguinte,-2) . "/" . substr($dia_seguinte,5,2);
                        echo '
                            <table class="tableSectionConfigArm" width="80%" align="center">
                                <tr class="">
                                    <th class="" style="text-align:center;color:#9C45EB;font-size:14px;">AMANHÃ (' . $dia_seguinte . ')</th>
                                </tr>
                        ';
                        foreach($hora_disp_amanha as $v) {
                            if($i==1) {
                                $v = str_replace("input", "input checked", $v);
                            }
                            echo '
                                <tr class="">
                                    <td class="" style="text-align:center;font-size:13px;">' . $v . '</td>
                                </tr>
                            ';
                            $i++;
                        }
                        echo '
                            </table>
                        ';
                    }
                }
                echo '
                        <button class="btnAgendEnd" type="submit"><i class="far fa-clock"></i> AGENDAR</button>
                    </form>
                ';
            } else {
                echo '<p class="semHorario">Não há horários disponíveis para entrega hoje ou amanhã!! <a href="' . base_url_php() . 'ajuda/horario_armazem">Veja os horários</a></p>';
            }
        }
    } else {
        header('Location: ../');
    }
?>