<?php
require("../../../fpdf186/fpdf.php");

// Classe personalizada de PDF com métodos adicionais
class PDF extends FPDF {
    // Método para criar retângulos com cantos arredondados
    function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        if (strpos($angle, '2')===false)
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        if (strpos($angle, '3')===false)
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        if (strpos($angle, '4')===false)
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        if (strpos($angle, '1')===false)
        {
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
            $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    // Método auxiliar para desenhar arcos
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
}

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
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Estoque</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#005A24",
                        secondary: "#FFA500",
                        accent: "#E6F4EA",
                        dark: "#1A3C34",
                        light: "#F8FAF9",
                        white: "#FFFFFF"
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                        heading: ["Poppins", "sans-serif"]
                    },
                    boxShadow: {
                        card: "0 10px 15px -3px rgba(0, 90, 36, 0.1), 0 4px 6px -2px rgba(0, 90, 36, 0.05)",
                        "card-hover": "0 20px 25px -5px rgba(0, 90, 36, 0.2), 0 10px 10px -5px rgba(0, 90, 36, 0.1)"
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: "Inter", sans-serif;
            scroll-behavior: smooth;
            background-color: #F8FAF9;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #005A24 0%, #1A3C34 100%);
        }

        .page-title {
            position: relative;
            display: inline-block;
        }

        .page-title::after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #FFA500;
            border-radius: 3px;
        }
        
        /* Centralização e alinhamento */
        .container {
            max-width: 1280px;
            margin: 0 auto;
        }
        
        main.container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .social-icon {
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-3px);
            filter: drop-shadow(0 4px 3px rgba(255, 165, 0, 0.3));
        }
        
        /* Estilos para o layout responsivo */
        @media screen and (min-width: 769px) {
            .desktop-table {
                display: block;
                width: 100%;
            }
            .mobile-cards {
                display: none !important;
            }
        }
        
        @media screen and (max-width: 768px) {
            .desktop-table {
                display: none !important;
            }
            .mobile-cards {
                display: block !important;
                margin-top: 1rem;
                padding: 0 0.5rem;
                width: 100%;
            }
            .card-item {
                margin-bottom: 0.75rem;
            }
            .categoria-header {
                margin-top: 1.5rem !important;
                margin-bottom: 0.75rem !important;
            }
        }
        
        /* Estilos para os cards */
        .card-item {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .card-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Estilo para quantidade crítica */
        .quantidade-critica {
            color: #FF0000;
            font-weight: bold;
        }
        
        /* Melhoria nos alinhamentos */
        .max-w-5xl {
            max-width: 64rem;
            width: 100%;
        }
        
        .flex-1.w-full {
            max-width: 100%;
        }
        
        #exportarBtn {
            margin-top: 1.5rem;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col font-sans bg-light">
    <!-- Improved Header -->
    <header class="sticky top-0 bg-gradient-to-r from-primary to-dark text-white py-4 shadow-md z-50">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="../index.php" class="flex items-center">
                    <img src="../assets/imagens/logostgm.png" alt="Logo S" class="h-12 mr-3 transition-transform hover:scale-105">
                    <span class="text-white font-heading text-xl font-semibold hidden md:inline">STGM Estoque</span>
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8 md:py-12 flex-1">
        <div class="text-center mb-10">
            <h1 class="text-primary text-3xl md:text-4xl font-bold mb-8 md:mb-6 text-center page-title tracking-tight font-heading inline-block mx-auto">VISUALIZAR ESTOQUE</h1>
        </div>

        <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4 max-w-5xl mx-auto">
            <div class="flex-1 w-full">
                <input type="text" id="pesquisar" placeholder="Pesquisar produto..." 
                    class="w-full px-4 py-3 border-2 border-primary rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent">
            </div>
            <div class="flex gap-2 flex-wrap justify-center">
                <select id="filtroCategoria" class="px-4 py-3 border-2 border-primary rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent">
                    <option value="">Todas as categorias</option>
                    <option value="limpeza">Limpeza</option>
                    <option value="expedientes">Expedientes</option>
                    <option value="manutencao">Manutenção</option>
                    <option value="eletrico">Elétrico</option>
                    <option value="hidraulico">Hidráulico</option>
                    <option value="educacao_fisica">Educação Física</option>
                    <option value="epi">EPI</option>
                    <option value="copa_e_cozinha">Copa e Cozinha</option>
                    <option value="informatica">Informática</option>
                    <option value="ferramentas">Ferramentas</option>
                </select>
                <button id="filtrarBtn" class="bg-secondary text-white font-bold py-3 px-6 rounded-lg hover:bg-opacity-90 transition-colors">
                    Filtrar
                </button>
            </div>
        </div>

        <!-- Tabela para desktop -->
        <div class="desktop-table bg-white rounded-xl shadow-lg overflow-hidden border-2 border-primary max-w-5xl mx-auto">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">Barcode</th>
                            <th class="py-3 px-4 text-left">Nome</th>
                            <th class="py-3 px-4 text-left">Quantidade</th>
                            <th class="py-3 px-4 text-left">Categoria</th>
                            <th class="py-3 px-4 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>';

        if ($result > 0) {
            foreach ($query as $value) {
                $quantidadeClass = $value['quantidade'] <= 5 ? 'text-red-600 font-bold' : 'text-gray-700';
                
                echo '<tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4">' . $value['barcode'] . '</td>
                            <td class="py-3 px-4">' . $value['nome_produto'] . '</td>
                            <td class="py-3 px-4 ' . $quantidadeClass . '">' . $value['quantidade'] . '</td>
                            <td class="py-3 px-4">' . $value['natureza'] . '</td>
                            <td class="py-3 px-4 flex space-x-2">
                                <button class="text-primary hover:text-secondary" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700" title="Excluir">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>';
            }
        } else {
            echo '<tr><td colspan="5" class="py-4 px-4 text-center text-gray-500">Nenhum produto encontrado</td></tr>';
        }

        echo '</tbody>
                </table>
            </div>
        </div>
        
        <!-- Cards para mobile -->
        <div class="mobile-cards mt-6 space-y-4 max-w-5xl mx-auto">';
        
        if ($result > 0) {
            $categoriaAtual = '';
            
            foreach ($query as $value) {
                // Adicionar cabeçalho da categoria quando mudar
                if ($categoriaAtual != $value['natureza']) {
                    $categoriaAtual = $value['natureza'];
                    echo '<div class="bg-primary text-white font-bold py-2 px-4 rounded-lg mt-6 mb-3 categoria-header">
                            <h3 class="text-sm uppercase tracking-wider">' . ucfirst($value['natureza']) . '</h3>
                          </div>';
                }
                
                $quantidadeClass = $value['quantidade'] <= 5 ? 'quantidade-critica' : '';
                
                echo '<div class="card-item bg-white shadow rounded-lg border-l-4 border-primary p-4 mb-3">
                        <div class="flex justify-between items-start w-full">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-primary mb-1">' . $value['nome_produto'] . '</h3>
                                <div class="flex flex-col space-y-1">
                                    <p class="text-sm text-gray-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        <span>' . $value['barcode'] . '</span>
                                    </p>
                                    <p class="text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span class="' . $quantidadeClass . '">Quantidade: ' . $value['quantidade'] . '</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <button class="text-primary hover:text-secondary p-1 rounded-full bg-gray-50" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 p-1 rounded-full bg-gray-50" title="Excluir">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>';
            }
        } else {
            echo '<div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2"></i>
                    <p>Nenhum produto encontrado</p>
                  </div>';
        }
        
        echo '</div>
        
        <div class="mt-8 flex justify-center w-full">
            <button id="exportarBtn" class="bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-opacity-90 transition-colors flex items-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exportar para PDF
            </button>
        </div>
    </main>

    <footer class="bg-gradient-to-r from-primary to-dark text-white py-6 mt-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Sobre a Escola -->
                <div>
                    <h3 class="font-heading text-lg font-semibold mb-3 flex items-center">
                        <i class="fas fa-school mr-2 text-sm"></i>
                        EEEP STGM
                    </h3>
                    <p class="text-xs leading-relaxed">
                        <i class="fas fa-map-marker-alt mr-1 text-xs"></i> 
                        AV. Marta Maria Carvalho Nojoza, SN<br>
                        Maranguape - CE
                    </p>
                </div>

                <!-- Contato -->
                <div>
                    <h3 class="font-heading text-lg font-semibold mb-3 flex items-center">
                        <i class="fas fa-address-book mr-2 text-sm"></i>
                        Contato
                    </h3>
                    <div class="text-xs leading-relaxed space-y-1">
                        <p class="flex items-start">
                            <i class="fas fa-phone-alt mr-1 mt-0.5 text-xs"></i>
                            (85) 3341-3990
                        </p>
                        <p class="flex items-start">
                            <i class="fas fa-envelope mr-1 mt-0.5 text-xs"></i>
                            eeepsantariamata@gmail.com
                        </p>
                    </div>
                </div>

                <!-- Desenvolvedores em Grid -->
                <div>
                    <h3 class="font-heading text-lg font-semibold mb-3 flex items-center">
                        <i class="fas fa-code mr-2 text-sm"></i>
                        Dev Team
                    </h3>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="https://www.instagram.com/dudu.limasx/" target="_blank" 
                           class="text-xs flex items-center hover:text-secondary transition-colors">
                            <i class="fab fa-instagram mr-1 text-xs"></i>
                            Carlos E.
                        </a>
                        <a href="https://www.instagram.com/millenafreires_/" target="_blank" 
                           class="text-xs flex items-center hover:text-secondary transition-colors">
                            <i class="fab fa-instagram mr-1 text-xs"></i>
                            Millena F.
                        </a>
                        <a href="https://www.instagram.com/matheusz.mf/" target="_blank" 
                           class="text-xs flex items-center hover:text-secondary transition-colors">
                            <i class="fab fa-instagram mr-1 text-xs"></i>
                            Matheus M.
                        </a>
                        <a href="https://www.instagram.com/yanlucas10__/" target="_blank" 
                           class="text-xs flex items-center hover:text-secondary transition-colors">
                            <i class="fab fa-instagram mr-1 text-xs"></i>
                            Ian Lucas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Rodapé inferior compacto -->
            <div class="border-t border-white/20 pt-4 mt-4 text-center">
                <p class="text-xs">
                    © 2024 STGM v1.2.0 | Desenvolvido por alunos EEEP STGM
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pesquisarInput = document.getElementById("pesquisar");
            const filtroCategoria = document.getElementById("filtroCategoria");
            const filtrarBtn = document.getElementById("filtrarBtn");
            const exportarBtn = document.getElementById("exportarBtn");
            
            // Detectar tipo de dispositivo para otimizar interações
            const isMobile = window.innerWidth <= 768;
            
            // Função para mostrar os cabeçalhos de categorias relevantes
            function mostrarCategoriaHeaders() {
                const categoriasVisiveis = new Set();
                const cards = document.querySelectorAll(".mobile-cards .card-item");
                
                // Primeiro escondemos todos os cabeçalhos
                const headers = document.querySelectorAll(".mobile-cards .categoria-header");
                headers.forEach(h => h.style.display = "none");
                
                // Verificamos quais cards estão visíveis e quais categorias precisam ser mostradas
                cards.forEach(card => {
                    if (card.style.display !== "none") {
                        let header = card.previousElementSibling;
                        
                        // Procurar para trás até encontrar um header
                        while (header && !header.classList.contains("categoria-header")) {
                            header = header.previousElementSibling;
                        }
                        
                        if (header) {
                            categoriasVisiveis.add(header);
                        }
                    }
                });
                
                // Mostramos apenas os cabeçalhos das categorias que têm cards visíveis
                categoriasVisiveis.forEach(header => {
                    header.style.display = "block";
                });
            }
            
            // Função para filtrar produtos em ambas as visualizações
            function filtrarProdutos() {
                const termoPesquisa = pesquisarInput.value.toLowerCase();
                const categoria = filtroCategoria.value.toLowerCase();
                
                // Filtrar tabela (desktop)
                const linhasTabela = document.querySelectorAll(".desktop-table tbody tr");
                linhasTabela.forEach(linha => {
                    const barcode = linha.cells[0].textContent.toLowerCase();
                    const nome = linha.cells[1].textContent.toLowerCase();
                    const natureza = linha.cells[3].textContent.toLowerCase();
                    
                    const matchTermo = barcode.includes(termoPesquisa) || 
                                     nome.includes(termoPesquisa);
                    const matchCategoria = categoria === "" || natureza === categoria;
                    
                    if (matchTermo && matchCategoria) {
                        linha.style.display = "";
                    } else {
                        linha.style.display = "none";
                    }
                });
                
                // Filtrar cards (mobile)
                const cards = document.querySelectorAll(".mobile-cards .card-item");
                
                cards.forEach(card => {
                    const nome = card.querySelector("h3").textContent.toLowerCase();
                    const barcode = card.querySelectorAll("p span")[0].textContent.toLowerCase();
                    
                    // Encontrar a categoria deste card procurando o cabeçalho anterior
                    let currentHeader = card.previousElementSibling;
                    while (currentHeader && !currentHeader.classList.contains("categoria-header")) {
                        currentHeader = currentHeader.previousElementSibling;
                    }
                    
                    const natureza = currentHeader ? 
                        currentHeader.querySelector("h3").textContent.toLowerCase() : "";
                    
                    const matchTermo = barcode.includes(termoPesquisa) || 
                                     nome.includes(termoPesquisa);
                    const matchCategoria = categoria === "" || natureza === categoria;
                    
                    if (matchTermo && matchCategoria) {
                        card.style.display = "";
                    } else {
                        card.style.display = "none";
                    }
                });
                
                // Mostrar apenas os cabeçalhos relevantes
                mostrarCategoriaHeaders();
            }
            
            // Event listeners
            filtrarBtn.addEventListener("click", filtrarProdutos);
            
            pesquisarInput.addEventListener("input", function() {
                filtrarProdutos();
            });
            
            filtroCategoria.addEventListener("change", function() {
                filtrarProdutos();
            });
            
            // Exportar para PDF
            exportarBtn.addEventListener("click", function() {
                window.location.href = "../control/gerar_relatorio.php";
            });
        });
    </script>
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
        $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
        $consulta = "SELECT * FROM produtos ORDER BY natureza, nome_produto";
        $query = $pdo->prepare($consulta);
        $query->execute();
        $result = $query->rowCount();
    
        // Criar PDF personalizado
        $pdf = new PDF("L", "pt", "A4");
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 60);
    
        // Paleta de cores consistente com o sistema
        $corPrimary = array(0, 90, 36);       // #005A24 - Verde principal
        $corDark = array(26, 60, 52);         // #1A3C34 - Verde escuro
        $corSecondary = array(255, 165, 0);   // #FFA500 - Laranja para destaques
        $corCinzaClaro = array(248, 250, 249); // #F8FAF9 - Fundo alternado
        $corBranco = array(255, 255, 255);    // #FFFFFF - Branco
        $corPreto = array(40, 40, 40);        // #282828 - Quase preto para texto
        $corAlerta = array(220, 53, 69);      // #DC3545 - Vermelho para alertas
        $corTextoSubtil = array(100, 100, 100); // #646464 - Cinza para textos secundários
    
        // ===== CABEÇALHO COM FUNDO VERDE SÓLIDO =====
        // Fundo verde sólido
        $pdf->SetFillColor($corPrimary[0], $corPrimary[1], $corPrimary[2]);
        $pdf->Rect(0, 0, $pdf->GetPageWidth(), 95, 'F');
    
        // Logo
        $logoPath = "../assets/imagens/logostgm.png";
        $logoWidth = 60;
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 40, 20, $logoWidth);
            $pdf->SetXY(40 + $logoWidth + 15, 30);
        } else {
            $pdf->SetXY(40, 30);
        }
    
        // Título e subtítulo
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetTextColor($corBranco[0], $corBranco[1], $corBranco[2]);
        $pdf->Cell(0, 24, utf8_decode("RELATÓRIO DE ESTOQUE"), 0, 1, 'L');
    
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(40 + $logoWidth + 15, $pdf->GetY());
        $pdf->Cell(0, 15, utf8_decode("EEEP Salaberga Torquato Gomes de Matos"), 0, 1, 'L');
    
        // Data de geração
        $pdf->SetXY($pdf->GetPageWidth() - 200, 30);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(160, 15, utf8_decode(date("d/m/Y")), 0, 1, 'R');
        $pdf->SetXY($pdf->GetPageWidth() - 200, 45);
        $pdf->Cell(160, 15, utf8_decode(date("H:i:s")), 0, 1, 'R');
    
        // ===== RESUMO DE DADOS EM CARDS =====
        $consultaResumo = "SELECT 
            COUNT(*) as total_produtos,
            SUM(CASE WHEN quantidade <= 5 THEN 1 ELSE 0 END) as produtos_criticos,
            COUNT(DISTINCT natureza) as total_categorias
            FROM produtos";
        $queryResumo = $pdo->prepare($consultaResumo);
        $queryResumo->execute();
        $resumo = $queryResumo->fetch(PDO::FETCH_ASSOC);
    
        // Criar cards para os resumos
        $cardWidth = 200;
        $cardHeight = 80;
        $cardMargin = 20;
        $startX = ($pdf->GetPageWidth() - (3 * $cardWidth + 2 * $cardMargin)) / 2;
        $startY = 110;
    
        // Card 1 - Total Produtos
        $pdf->SetFillColor($corBranco[0], $corBranco[1], $corBranco[2]);
        $pdf->RoundedRect($startX, $startY, $cardWidth, $cardHeight, 8, 'F');
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor($corPreto[0], $corPreto[1], $corPreto[2]);
        $pdf->SetXY($startX + 15, $startY + 15);
        $pdf->Cell($cardWidth - 30, 20, utf8_decode("TOTAL DE PRODUTOS"), 0, 1, 'L');
        
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetTextColor($corPrimary[0], $corPrimary[1], $corPrimary[2]);
        $pdf->SetXY($startX + 15, $startY + 40);
        $pdf->Cell($cardWidth - 30, 25, $resumo['total_produtos'], 0, 1, 'L');
    
        // Card 2 - Estoque Crítico
        $pdf->SetFillColor($corBranco[0], $corBranco[1], $corBranco[2]);
        $pdf->RoundedRect($startX + $cardWidth + $cardMargin, $startY, $cardWidth, $cardHeight, 8, 'F');
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor($corPreto[0], $corPreto[1], $corPreto[2]);
        $pdf->SetXY($startX + $cardWidth + $cardMargin + 15, $startY + 15);
        $pdf->Cell($cardWidth - 30, 20, utf8_decode("ESTOQUE CRÍTICO"), 0, 1, 'L');
        
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetTextColor($corAlerta[0], $corAlerta[1], $corAlerta[2]);
        $pdf->SetXY($startX + $cardWidth + $cardMargin + 15, $startY + 40);
        $pdf->Cell($cardWidth - 30, 25, $resumo['produtos_criticos'], 0, 1, 'L');
    
        // Card 3 - Categorias
        $pdf->SetFillColor($corBranco[0], $corBranco[1], $corBranco[2]);
        $pdf->RoundedRect($startX + 2 * ($cardWidth + $cardMargin), $startY, $cardWidth, $cardHeight, 8, 'F');
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor($corPreto[0], $corPreto[1], $corPreto[2]);
        $pdf->SetXY($startX + 2 * ($cardWidth + $cardMargin) + 15, $startY + 15);
        $pdf->Cell($cardWidth - 30, 20, utf8_decode("CATEGORIAS"), 0, 1, 'L');
        
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetTextColor($corSecondary[0], $corSecondary[1], $corSecondary[2]);
        $pdf->SetXY($startX + 2 * ($cardWidth + $cardMargin) + 15, $startY + 40);
        $pdf->Cell($cardWidth - 30, 25, $resumo['total_categorias'], 0, 1, 'L');
    
        // ===== TÍTULO DA TABELA =====
        $pdf->SetXY(40, $startY + $cardHeight + 30);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor($corPrimary[0], $corPrimary[1], $corPrimary[2]);
      
    
        // Linha decorativa
      
    
        // ===== TABELA DE PRODUTOS COM MELHOR DESIGN =====
        $margemTabela = 40;
        $larguraDisponivel = $pdf->GetPageWidth() - (2 * $margemTabela);
        
        // Definindo colunas e larguras proporcionais
        $colunas = array('ID', 'Código', 'Produto', 'Quant.', 'Categoria');
        $larguras = array(
            round($larguraDisponivel * 0.06), // 6% para ID
            round($larguraDisponivel * 0.18), // 18% para Código
            round($larguraDisponivel * 0.44), // 44% para Produto
            round($larguraDisponivel * 0.12), // 12% para Quantidade
            round($larguraDisponivel * 0.20)  // 20% para Categoria
        );
        
        $pdf->SetXY($margemTabela, $pdf->GetY() + 10);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor($corPrimary[0], $corPrimary[1], $corPrimary[2]);
        $pdf->SetTextColor($corBranco[0], $corBranco[1], $corBranco[2]);
        $pdf->SetDrawColor(220, 220, 220);
    
        // Cabeçalho da tabela com arredondamento personalizado
        $alturaLinha = 30;
        $posX = $margemTabela;
        
        // Célula de cabeçalho com primeiro canto arredondado (esquerda superior)
        $pdf->RoundedRect($posX, $pdf->GetY(), $larguras[0], $alturaLinha, 5, 'FD', '1');
        $pdf->SetXY($posX, $pdf->GetY());
        $pdf->Cell($larguras[0], $alturaLinha, utf8_decode($colunas[0]), 0, 0, 'C');
        $posX += $larguras[0];
        
        // Células de cabeçalho intermediárias
        for ($i = 1; $i < count($colunas) - 1; $i++) {
            $pdf->Rect($posX, $pdf->GetY(), $larguras[$i], $alturaLinha, 'FD');
            $pdf->SetXY($posX, $pdf->GetY());
            $pdf->Cell($larguras[$i], $alturaLinha, utf8_decode($colunas[$i]), 0, 0, 'C');
            $posX += $larguras[$i];
        }
        
        // Última célula com canto arredondado (direita superior)
        $pdf->RoundedRect($posX, $pdf->GetY(), $larguras[count($colunas) - 1], $alturaLinha, 5, 'FD', '2');
        $pdf->SetXY($posX, $pdf->GetY());
        $pdf->Cell($larguras[count($colunas) - 1], $alturaLinha, utf8_decode($colunas[count($colunas) - 1]), 0, 0, 'C');
        
        $pdf->Ln($alturaLinha);
    
        // Dados da tabela
        $y = $pdf->GetY();
        $categoriaAtual = '';
        $linhaAlternada = false;
        $alturaLinhaDados = 24;
    
        if ($result > 0) {
            foreach ($query as $idx => $row) {
                // Cabeçalho de categoria
                if ($categoriaAtual != $row['natureza']) {
                    $categoriaAtual = $row['natureza'];
                    
                    // Verificar se é necessário adicionar nova página
                    if ($y + 40 > $pdf->GetPageHeight() - 60) {
                        $pdf->AddPage();
                        $pdf->SetDrawColor($corSecondary[0], $corSecondary[1], $corSecondary[2]);
                        $pdf->SetLineWidth(2);
                        $pdf->Line(40, 40, 240, 40);
                        $pdf->SetLineWidth(0.5);
                        $y = 50;
                    } else {
                        $y += 10;
                    }
                    
                    $pdf->SetXY($margemTabela, $y);
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->SetTextColor($corBranco[0], $corBranco[1], $corBranco[2]);
                    $pdf->SetFillColor($corSecondary[0], $corSecondary[1], $corSecondary[2]);
                    
                    // Cabeçalho de categoria com cantos arredondados
                    $pdf->RoundedRect($margemTabela, $y, array_sum($larguras), 26, 5, 'FD');
                    $pdf->SetXY($margemTabela + 10, $y);
                    $pdf->Cell(array_sum($larguras) - 20, 26, utf8_decode(strtoupper($categoriaAtual)), 0, 1, 'L');
                    
                    $y = $pdf->GetY();
                    $linhaAlternada = false;
                }
    
                // Cor de fundo alternada para linhas
                if ($linhaAlternada) {
                    $pdf->SetFillColor($corCinzaClaro[0], $corCinzaClaro[1], $corCinzaClaro[2]);
                } else {
                    $pdf->SetFillColor($corBranco[0], $corBranco[1], $corBranco[2]);
                }
                
                // Verificar se é necessário adicionar nova página
                if ($y + $alturaLinhaDados > $pdf->GetPageHeight() - 60) {
                    $pdf->AddPage();
                    
                    // Redesenhar cabeçalho da tabela na nova página
                    $y = 40;
                    $posX = $margemTabela;
                    $pdf->SetFillColor($corPrimary[0], $corPrimary[1], $corPrimary[2]);
                    $pdf->SetTextColor($corBranco[0], $corBranco[1], $corBranco[2]);
                    
                    // Cabeçalho da tabela
                    $pdf->RoundedRect($posX, $y, $larguras[0], $alturaLinha, 5, 'FD', '1');
                    $pdf->SetXY($posX, $y);
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->Cell($larguras[0], $alturaLinha, utf8_decode($colunas[0]), 0, 0, 'C');
                    $posX += $larguras[0];
                    
                    for ($i = 1; $i < count($colunas) - 1; $i++) {
                        $pdf->Rect($posX, $y, $larguras[$i], $alturaLinha, 'FD');
                        $pdf->SetXY($posX, $y);
                        $pdf->Cell($larguras[$i], $alturaLinha, utf8_decode($colunas[$i]), 0, 0, 'C');
                        $posX += $larguras[$i];
                    }
                    
                    $pdf->RoundedRect($posX, $y, $larguras[count($colunas) - 1], $alturaLinha, 5, 'FD', '2');
                    $pdf->SetXY($posX, $y);
                    $pdf->Cell($larguras[count($colunas) - 1], $alturaLinha, utf8_decode($colunas[count($colunas) - 1]), 0, 0, 'C');
                    
                    $pdf->Ln($alturaLinha);
                    $y = $pdf->GetY();
                    
                    // Redesenhar cabeçalho de categoria
                    $pdf->SetXY($margemTabela, $y);
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->SetTextColor($corBranco[0], $corBranco[1], $corBranco[2]);
                    $pdf->SetFillColor($corSecondary[0], $corSecondary[1], $corSecondary[2]);
                    
                    $pdf->RoundedRect($margemTabela, $y, array_sum($larguras), 26, 5, 'FD');
                    $pdf->SetXY($margemTabela + 10, $y);
                    $pdf->Cell(array_sum($larguras) - 20, 26, utf8_decode(strtoupper($categoriaAtual)), 0, 1, 'L');
                    
                    $y = $pdf->GetY();
                    
                    // Restaurar cor de fundo para a linha
                    if ($linhaAlternada) {
                        $pdf->SetFillColor($corCinzaClaro[0], $corCinzaClaro[1], $corCinzaClaro[2]);
                    } else {
                        $pdf->SetFillColor($corBranco[0], $corBranco[1], $corBranco[2]);
                    }
                }
    
                // Configurar texto
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor($corPreto[0], $corPreto[1], $corPreto[2]);
    
                // Desenhar linha de dados
                $posX = $margemTabela;
                $estoqueCritico = $row['quantidade'] <= 5;
    
                // ID
                $pdf->Rect($posX, $y, $larguras[0], $alturaLinhaDados, 'FD');
                $pdf->SetXY($posX, $y);
                $pdf->Cell($larguras[0], $alturaLinhaDados, $row['id'], 0, 0, 'C');
                $posX += $larguras[0];
    
                // Barcode
                $pdf->Rect($posX, $y, $larguras[1], $alturaLinhaDados, 'FD');
                $pdf->SetXY($posX + 5, $y);
                $pdf->Cell($larguras[1] - 10, $alturaLinhaDados, $row['barcode'], 0, 0, 'L');
                $posX += $larguras[1];
    
                // Nome do produto
                $pdf->Rect($posX, $y, $larguras[2], $alturaLinhaDados, 'FD');
                $pdf->SetXY($posX + 5, $y);
                $pdf->Cell($larguras[2] - 10, $alturaLinhaDados, utf8_decode($row['nome_produto']), 0, 0, 'L');
                $posX += $larguras[2];
    
                // Quantidade
                $pdf->Rect($posX, $y, $larguras[3], $alturaLinhaDados, 'FD');
                $pdf->SetXY($posX, $y);
                if ($estoqueCritico) {
                    $pdf->SetTextColor($corAlerta[0], $corAlerta[1], $corAlerta[2]);
                    $pdf->SetFont('Arial', 'B', 10);
                }
                $pdf->Cell($larguras[3], $alturaLinhaDados, $row['quantidade'], 0, 0, 'C');
                $pdf->SetTextColor($corPreto[0], $corPreto[1], $corPreto[2]);
                $pdf->SetFont('Arial', '', 10);
                $posX += $larguras[3];
    
                // Categoria
                $pdf->Rect($posX, $y, $larguras[4], $alturaLinhaDados, 'FD');
                $pdf->SetXY($posX + 5, $y);
                $pdf->Cell($larguras[4] - 10, $alturaLinhaDados, utf8_decode($row['natureza']), 0, 0, 'L');
    
                $y += $alturaLinhaDados;
                $linhaAlternada = !$linhaAlternada;
    
                // Verificar se é o último item
                if ($idx == $result - 1) {
                    // Adicionar cantos arredondados na última linha da tabela
                    $pdf->SetDrawColor(220, 220, 220);
                    $pdf->RoundedRect($margemTabela, $y - $alturaLinhaDados, $larguras[0], $alturaLinhaDados, 5, 'D', '4');
                    $pdf->RoundedRect($posX, $y - $alturaLinhaDados, $larguras[4], $alturaLinhaDados, 5, 'D', '3');
                }
            }
        } else {
            $pdf->SetXY($margemTabela, $y);
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->SetTextColor($corTextoSubtil[0], $corTextoSubtil[1], $corTextoSubtil[2]);
            $pdf->SetFillColor(250, 250, 250);
            $pdf->RoundedRect($margemTabela, $y, array_sum($larguras), 40, 5, 'FD');
            $pdf->SetXY($margemTabela, $y + 12);
            $pdf->Cell(array_sum($larguras), 16, utf8_decode("Não existem produtos cadastrados no sistema"), 0, 1, 'C');
        }
    
        // ===== RODAPÉ PROFISSIONAL =====
        $pdf->SetY(-60);
        $y = $pdf->GetY();
        
       
    
        $pdf->SetTextColor($corBranco[0], $corBranco[1], $corBranco[2]);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(40, $y + 15);
        $pdf->Cell(0, 10, utf8_decode("Sistema de Gerenciamento de Estoque - STGM v1.2.0"), 0, 0, 'L');
        
        $pdf->SetXY(40, $y + 25);
        $pdf->Cell(0, 10, utf8_decode("© " . date('Y') . " - Desenvolvido por alunos EEEP STGM"), 0, 0, 'L');
        
        $pdf->SetX(-60);
        $pdf->Cell(30, 10, utf8_decode('Página ' . $pdf->PageNo()), 0, 0, 'R');
    
        // Saída do PDF
        $pdf->Output("relatorio_estoque.pdf", "I");
    }
    
}