<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Novo Produto</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/stylesoliproduto.css">
</head>

<body>

    <div class="container">
        <h1><span>SOLICITAR</span> <span>PRODUTO</span></h1>
        <form action="../control/controllersolicitar.php" method="POST">
            <div class="input-group">
                <input type="text" placeholder="BARCODE" id=barcode" name="barcode" required>
            </div>
            <div class="input-group">
                    <input type="number" placeholder="QUANTIDADE" min="1" id="quantidade" name="quantidade" required>   
            </div>
            <div class="input-group">
                <div class="custom-select-container">
                    <input type="text" placeholder="RETIRANTE" id="retirante" readonly onclick="toggleSelect()">
                    <div class="custom-select" id="retiranteSelect">
                        <div onclick="selectRetirante('Marcelo Cabral')">
                            <img src="images/marcelo-cabral-icon.png" alt="Marcelo Cabral Icon">
                            Marcelo Cabral
                        </div>
                        <div onclick="selectRetirante('Otávio Filho')">
                            <img src="images/otavio-filho-icon.png" alt="Otávio Filho Icon">
                            Otávio Filho
                        </div>
                        <div onclick="selectRetirante('Lindiane')">
                            <img src="images/lindiane-icon.png" alt="Lindiane Icon">
                            Lindiane
                        </div>
                        <div onclick="selectRetirante('Jarderson Soares')">
                            <img src="images/jarderson-soares-icon.png" alt="Jarderson Soares Icon">
                            Jarderson Soares
                        </div>
                        <div onclick="selectRetirante('Silene')">
                            <img src="images/silene-icon.png" alt="Silene Icon">
                            Silene
                        </div>
                        <div onclick="selectRetirante('Rosemeire Russo')">
                            <img src="images/rosemeire-russo-icon.png" alt="Rosemeire Russo Icon">
                            Rosemeire Russo
                        </div>
                        <div onclick="selectRetirante('Jackson')">
                            <img src="images/jackson-icon.png" alt="Jackson Icon">
                            Jackson
                        </div>
                    </div>
                </div>
            </div>
            <button onclick="confirmar()">CONFIRMAR</button>
    </div>
    </form>
    <div class="footer-bar"></div>

    <script>
        function toggleSelect() {
            const select = document.getElementById('retiranteSelect');
            select.style.display = select.style.display === 'block' ? 'none' : 'block';
        }

        function selectRetirante(name) {
            const input = document.getElementById('retirante');
            input.value = name;
            toggleSelect();
        }
    </script>
</body>

</html>