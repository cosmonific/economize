<?php
    require_once 'connection/conn.php';

    if(isset($_POST["produto_preco"])) {
        if(!isset($_SESSION['url4'])) {
            $sel = $conn->prepare("SELECT * FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id JOIN subcateg AS s ON s.subcateg_id=c.subcateg_id WHERE s.depart_id={$_SESSION['depart_id']} ORDER BY p.produto_preco {$_POST["produto_preco"]}");
            $sel->execute();
            $result = $sel->fetchAll();
            foreach($result as $v) {
                $v["produto_preco"] = number_format($v["produto_preco"], 2, ',', '.');
                $json[] = $v;
            }
        } else {
            if(!isset($_SESSION['url5'])) {
                $sel = $conn->prepare("SELECT * FROM produto AS p JOIN categ AS c ON p.produto_categ=c.categ_id WHERE c.subcateg_id={$_SESSION['subcateg_id']} ORDER BY p.produto_preco {$_POST["produto_preco"]}");
                $sel->execute();
                $result = $sel->fetchAll();
                foreach($result as $v) {
                    $v["produto_preco"] = number_format($v["produto_preco"], 2, ',', '.');
                    $json[] = $v;
                }
            } else {
                $sel = $conn->prepare("SELECT * FROM produto WHERE produto_categ={$_SESSION['categ_id']} ORDER BY produto_preco {$_POST["produto_preco"]}");
                $sel->execute();
                $result = $sel->fetchAll();
                foreach($result as $v) {
                    $v["produto_preco"] = number_format($v["produto_preco"], 2, ',', '.');
                    $json[] = $v;
                }
            }
        }

        echo json_encode($json);
    }
?>