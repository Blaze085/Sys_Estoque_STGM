<?php
require("../model/model.functions.php");

if(isset($_POST['btn'])){    
$nome = $_POST['nome'];
$barcode = $_POST['barcode'];
$quantidade = $_POST['quantidade'];
$natureza = $_POST['natureza'];

$x =new gerenciamento();
$x->adicionarestoque($nome, $barcode, $quantidade, $natureza);
    
}




?>