<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Estoque</title>
    <link href="../assets/css/styleadcproduto.css" rel="stylesheet">

</head>

<body>

    <div class="container">
        <h1>ADICIONAR PRODUTO</h1>
        <form action="../control/controllerAdicionarProduto.php" method="POST">
            <div class="input-group">
                <input type="text" class="input-field" placeholder="BARCODE" id="barcode" name="barcode" required>
            </div>
            <div class="input-group">
                <input type="text" class="input-field" placeholder="NOME DO PRODUTO" id="nome" name="nome" required>
            </div>
            <div class="input-group">
                <input type="number" class="input-field" placeholder="QUANTIDADE" min="1" id="quantidade" name="quantidade" required>
            </div>

            <div class="input-group radio-container">
                <div class="radio-columns">
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="limpeza" name="natureza" value="limpeza" aria-label="Limpeza">
                        <span>Limpeza</span>
                    </div>
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="expedientes" name="natureza" value="expedientes" aria-label="Expedientes">
                        <span>Expedientes</span>
                    </div>
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="manutencao" name="natureza" value="manutencao" aria-label="Manutenção">
                        <span>Manutenção</span>
                    </div>
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="eletrico" name="natureza" value="eletrico" aria-label="Elétrico">
                        <span>Elétrico</span>
                    </div>
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="hidraulico" name="natureza" value="hidraulico" aria-label="Hidráulico">
                        <span>Hidráulico</span>
                    </div>
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="educacao_fisica" name="natureza" value="educacao_fisica" aria-label="Educação Física">
                        <span>Educação Física</span>
                    </div>
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="epi" name="natureza" value="epi" aria-label="EPI">
                        <span>EPI</span>
                    </div>
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="copa_e_cozinha" name="natureza" value="copa_e_cozinha" aria-label="Copa e Cozinha">
                        <span>Copa e Cozinha</span>
                    </div>
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="informatica" name="natureza" value="informatica" aria-label="Informática">
                        <span>Informática</span>
                    </div>
                    <div class="custom-radio-group mb-3">
                        <input type="radio" id="ferramentas" name="natureza" value="ferramentas" aria-label="Ferramentas">
                        <span>Ferramentas</span>
                    </div>
                </div>
            </div>
            <input type="submit" class="confirmar-button" name="btn" value="Adicionar">
        </form>
    </div>
</body>

</html>