<?php
require ("../model/model.functions.php");

if(isset($_POST ['btn'])){
$produto = $_POST['produto'];
$retirante = $_POST['retirante'];
$valor_retirada = $_POST['quantidade'];

print_r($produto,$retirante,$valor_retirada);}
?>