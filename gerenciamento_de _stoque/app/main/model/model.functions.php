<?php
require("C:/xampp/htdocs/gerenciamento de estoque/gerenciamento_de _stoque/fpdf186/fpdf.php");
class gerenciamento
{
    public function estoque()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
        $consulta = "select * from produtos;";
        $query = $pdo->prepare($consulta);
        $query->execute();
        $result = $query->rowCount();


        echo '<!DOCTYPE html>
      <html lang="pt-br">
      <head>
          <meta charset="UTF-8">
          <title>Estoque</title>
      
          <link rel="stylesheet" href="../assets/css/styuleestoque.css">
      </head>
      <body>
           <div class="header">
            <div class="header-left">
                   <a href="../index.php"><button class="back-button" href="../index.php">Voltar</button></a>
                <select id="filterSelect" onchange="filterProducts()">
                    <option value="" disabled selected>Filtrar</option>
                    <option value="maiorQuantidade">Maior Quantidade</option>
                    <option value="menorQuantidade">Menor Quantidade</option>
                    <option value="ordemNumerica">Ordem Numérica</option>
                    <option value="ordemAlfabetica">Ordem Alfabética</option>
                </select>
            </div>
                   <div class="search-bar">
    
            <input type="text" placeholder="Pesquisar produto..." oninput="searchProducts()">
        </div>
                <select id="colorSelect" onchange="changeColor()">
                    <option value="" disabled selected>Configuração</option>
                    <option value="preto">Preto</option>
                    <option value="azul">Azul</option>
                    <option value="vermelho">Vermelho</option>
                    <option value="amarelo">Amarelo</option>
                    <option value="branco">Branco</option>
        </select>
        </div>
          <div class="container-fluid">
              <div class="row">
                  <div class="col-md-12">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th>
                                      Barcode
                                  </th>
                                  <th>
                                      Nome do produto
                                  </th>
                                  <th>
                                      Quantidade
                                  </th>
                                  <th>
                                      Natureza
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              ';



        if ($result > 0) {
            foreach ($query as $value) {

                echo '             <tr class="table-danger">
                            <td>
                                ' . $value['barcode'] . '
                            </td>
                            <td>
                                ' . $value['nome_produto'] . '
                            </td>
                            <td>
                                ' . $value['quantidade'] . '
                            </td>
                            <td>
                                ' . $value['natureza'] . '
                            </td>
                          </tr>';
            }
        }

        echo '      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script>
</body>
</html>';
    }

    public function adicionarestoque($nome, $barcode, $quantidade, $natureza)
    {

        $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
        $consulta = "INSERT INTO produtos VALUES (null, :barcode, :nome, :quantidade, :natureza)";
        $query = $pdo->prepare($consulta);
        $query->bindValue(":nome", $nome);
        $query->bindValue(":barcode", $barcode);
        $query->bindValue(":quantidade", $quantidade);
        $query->bindValue(":natureza", $natureza);
        $query->execute();
    }
    public function solicitarrproduto($valor_retirada, $barcode)
    {
        $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
        $consulta = "update produtos set quantidade=quantidade-:valor_retirada where barcode=:barcode;";
        $query = $pdo->prepare($consulta);
        $query->bindValue(":valor_retirada", $valor_retirada);
        $query->bindValue(":barcode", $barcode);
        $query->execute();
    }
    public function GerarRelatorios()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=sys_demanda", "root", "");
        $consulta = "select * from usuario;";
        $query = $pdo->prepare($consulta);
        $query->execute();
        $result = $query->rowCount();

        if ($result > 0) {
            foreach ($query as $value) {

                $pdf = new FPDF("L", "pt", "A4");
                $pdf->AddPage();

                $pdf->SetFont("Arial", "B", 20);

                $pdf->Ln(10);

                $pdf->SetFillColor(200, 200, 200);
                $pdf->Cell(0, 30, utf8_decode("RELÁTORIO DE ESTOQUE"), 1, 1, 'C', 1);

                $pdf->Ln(20);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->SetTextColor(255, 255, 255);

                $pdf->SetFont("Arial", "B", 14);
                $pdf->Cell(90, 20, utf8_decode('ID'), 1, 0, 'C', 1);
                $pdf->Cell(225, 20, utf8_decode('BARCODE'), 1, 0, 'C', 1);
                $pdf->Cell(225, 20, utf8_decode('NOME DO PRODUTO'), 1, 0, 'C', 1);
                $pdf->Cell(100, 20, utf8_decode('QUANTIDADE'), 1, 0, 'C', 1);
                $pdf->Cell(145, 20, utf8_decode('NATUREZA'), 1, 1, 'C', 1);

                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetTextColor(0, 0, 0);

                $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
                $consulta = "select * from produtos;";
                $query = $pdo->prepare($consulta);
                $query->execute();

                foreach ($query as $value) {
                    $pdf->Cell(90, 20, $value['id'], 1, 0, "C", 1);
                    $pdf->Cell(225, 20, $value['barcode'], 1, 0, "C", 1);
                    $pdf->Cell(225, 20, utf8_decode($value['nome_produto']), 1, 0, "C", 1);
                    $pdf->Cell(100, 20, $value['quantidade'], 1, 0, "C", 1);
                    $pdf->Cell(145, 20, $value['natureza'], 1, 1, "C", 1);
                }

                $pdf->Output("arquivoteste.pdf", "I");
            }
        }
    }
}
