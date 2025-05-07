<?php
require ("../model/model.functions.php");

if(isset($_POST ['btn'])){
$barcode = $_POST['barcode'];
$valor_retirada = $_POST['quantidade'];

$x = new gerenciamento();
$x-> solicitarproduto($valor_retirada,$barcode);
}
?>