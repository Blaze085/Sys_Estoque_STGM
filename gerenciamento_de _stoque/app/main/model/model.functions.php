<?php
require("../../../fpdf186/fpdf.php");

// Classe personalizada de PDF com métodos adicionais
class PDF extends FPDF
{
    // Método para criar retângulos com cantos arredondados
    function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
    {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        if (strpos($angle, '2') === false)
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $y) * $k));
        else
            $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        if (strpos($angle, '3') === false)
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
        else
            $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        if (strpos($angle, '4') === false)
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - ($y + $h)) * $k));
        else
            $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);

        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
        if (strpos($angle, '1') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $y) * $k));
            $this->_out(sprintf('%.2F %.2F l', ($x + $r) * $k, ($hp - $y) * $k));
        } else
            $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    // Método auxiliar para desenhar arcos
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c ',
            $x1 * $this->k,
            ($h - $y1) * $this->k,
            $x2 * $this->k,
            ($h - $y2) * $this->k,
            $x3 * $this->k,
            ($h - $y3) * $this->k
        ));
    }
}

class gerenciamento
{
    public function removerAcentos($texto)
    {
        $texto = str_replace(
            ['á', 'à', 'ã', 'â', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï', 'ó', 'ò', 'õ', 'ô', 'ö', 'ú', 'ù', 'û', 'ü', 'ç', 'Á', 'À', 'Ã', 'Â', 'Ä', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï', 'Ó', 'Ò', 'Õ', 'Ô', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç'],
            ['a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'c', 'A', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'C'],
            $texto
        );
        return $texto;
    }

    public function estoque()
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $consulta = "SELECT * FROM produtos";
            $query = $pdo->prepare($consulta);
            $query->execute();
            $produtos = $query->fetchAll(PDO::FETCH_ASSOC);
            $result = count($produtos);
            $debug_message = "Produtos carregados: " . $result;
        } catch (PDOException $e) {
            $debug_message = "Erro ao conectar com o banco de dados: " . $e->getMessage();
            $produtos = [];
            $result = 0;
        }

        // Log para depuração
        error_log($debug_message);

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

            /* Estilos para o header melhorado */
            .header-nav-link {
                position: relative;
                transition: all 0.3s ease;
                font-weight: 500;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
            }
            
            .header-nav-link:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }
            
            .header-nav-link::after {
                content: "";
                position: absolute;
                bottom: -2px;
                left: 50%;
                width: 0;
                height: 2px;
                background-color: #FFA500;
                transition: all 0.3s ease;
                transform: translateX(-50%);
            }
            
            .header-nav-link:hover::after {
                width: 80%;
            }
            
            .header-nav-link.active {
                background-color: rgba(255, 255, 255, 0.15);
            }
            
            .header-nav-link.active::after {
                width: 80%;
            }
            
            .mobile-menu-button {
                display: none;
            }
            
            @media (max-width: 768px) {
                .header-nav {
                    display: none;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: linear-gradient(135deg, #005A24 0%, #1A3C34 100%);
                    padding: 1rem;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                    z-index: 40;
                }
                
                .header-nav.show {
                    display: flex;
                    flex-direction: column;
                }
                
                .header-nav-link {
                    padding: 0.75rem 1rem;
                    text-align: center;
                    margin: 0.25rem 0;
                }
                
                .mobile-menu-button {
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    width: 30px;
                    height: 21px;
                    background: transparent;
                    border: none;
                    cursor: pointer;
                    padding: 0;
                    z-index: 10;
                }
                
                .mobile-menu-button span {
                    width: 100%;
                    height: 3px;
                    background-color: white;
                    border-radius: 10px;
                    transition: all 0.3s linear;
                    position: relative;
                    transform-origin: 1px;
                }
                
                .mobile-menu-button span:first-child.active {
                    transform: rotate(45deg);
                    top: 0px;
                }
                
                .mobile-menu-button span:nth-child(2).active {
                    opacity: 0;
                }
                
                .mobile-menu-button span:nth-child(3).active {
                    transform: rotate(-45deg);
                    top: -1px;
                }
            }
    
            /* Estilos para o layout responsivo */
            @media screen and (min-width: 769px) {
                .desktop-table {
                    display: block;
                    width: 100%;
                }
                .mobile-cards {
                    display: none;
                }
            }
    
            @media screen and (max-width: 768px) {
                .desktop-table {
                    display: none;
                }
                .mobile-cards {
                    display: flex;
                    flex-direction: column;
                    gap: 0.75rem;
                    margin-top: 1rem;
                    padding: 0 0.5rem;
                    width: 100%;
                }
                .card-item {
                    margin-bottom: 0.75rem;
                }
                .categoria-header {
                    margin-top: 1.5rem;
                    margin-bottom: 0.75rem;
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

            /* Estilo para mensagem de depuração */
            .debug-message {
                display: none;
            }
        </style>
    </head>
    
    <body class="min-h-screen flex flex-col font-sans bg-light">
        <!-- Improved Header -->
        <header class="sticky top-0 bg-gradient-to-r from-primary to-dark text-white py-4 shadow-lg z-50">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <div class="flex items-center">
                    <a href="../index.php" class="flex items-center">
                        <img src="../assets/imagens/logostgm.png" alt="Logo S" class="h-12 mr-3 transition-transform hover:scale-105">
                        <span class="text-white font-heading text-xl font-semibold hidden md:inline">STGM Estoque</span>
                    </a>
                </div>
                
                <button class="mobile-menu-button focus:outline-none" aria-label="Menu" id="menuButton">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <nav class="header-nav md:flex items-center space-x-1" id="headerNav">
                    <a href="../view/paginainicial.php" class="header-nav-link flex items-center">
                        <i class="fas fa-home mr-2"></i>
                        <span>Início</span>
                    </a>
                    <a href="../view/estoque.php" class="header-nav-link active flex items-center">
                        <i class="fas fa-boxes mr-2"></i>
                        <span>Estoque</span>
                    </a>
                    <a href="../view/adicionarproduto.php" class="header-nav-link flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i>
                        <span>Adicionar</span>
                    </a>
                    <div class="relative group">
                        <a class="header-nav-link flex items-center cursor-pointer">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            <span>Solicitar</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </a>
                        <div class="absolute left-0 mt-1 w-48 bg-white rounded-lg shadow-lg overflow-hidden transform scale-0 group-hover:scale-100 transition-transform origin-top z-50">
                            <a href="../view/solicitar.php" class="block px-4 py-2 text-primary hover:bg-primary hover:text-white transition-colors">
                                <i class="fas fa-clipboard-check mr-2"></i>Solicitar Produto
                            </a>
                            <a href="../view/solicitarnovproduto.php" class="block px-4 py-2 text-primary hover:bg-primary hover:text-white transition-colors">
                                <i class="fas fa-plus-square mr-2"></i>Solicitar Novo Produto
                            </a>
                        </div>
                    </div>
                    <a href="../view/relatorios.php" class="header-nav-link flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        <span>Relatórios</span>
                    </a>
                </nav>
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
            foreach ($produtos as $value) {
                $quantidadeClass = $value['quantidade'] <= 5 ? 'text-red-600 font-bold' : 'text-gray-700';

                echo '<tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-4">' . htmlspecialchars($value['barcode']) . '</td>
                        <td class="py-3 px-4">' . htmlspecialchars($value['nome_produto']) . '</td>
                        <td class="py-3 px-4 ' . $quantidadeClass . '">' . htmlspecialchars($value['quantidade']) . '</td>
                        <td class="py-3 px-4">' . htmlspecialchars($value['natureza']) . '</td>
                        <td class="py-3 px-4 flex space-x-2">
                            <button onclick="abrirModalEditar(' . $value['id'] . ')" class="text-primary hover:text-secondary" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <button onclick="abrirModalExcluir(' . $value['id'] . ', \'' . htmlspecialchars(addslashes($value['nome_produto'])) . '\')" class="text-red-500 hover:text-red-700" title="Excluir">
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
            <div class="mobile-cards mt-6 max-w-5xl mx-auto">';

        if ($result > 0) {
            $categoriaAtual = '';

            foreach ($produtos as $value) {
                // Adicionar cabeçalho da categoria quando mudar
                if ($categoriaAtual != $value['natureza']) {
                    $categoriaAtual = $value['natureza'];
                    echo '<div class="bg-primary text-white font-bold py-2 px-4 rounded-lg mt-6 mb-3 categoria-header">
                            <h3 class="text-sm uppercase tracking-wider">' . htmlspecialchars(ucfirst($value['natureza'])) . '</h3>
                          </div>';
                }

                $quantidadeClass = $value['quantidade'] <= 5 ? 'quantidade-critica' : '';

                echo '<div class="card-item bg-white shadow rounded-lg border-l-4 border-primary p-4 mb-3">
                        <div class="flex justify-between items-start w-full">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-primary mb-1">' . htmlspecialchars($value['nome_produto']) . '</h3>
                                <div class="flex flex-col space-y-1">
                                    <p class="text-sm text-gray-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        <span>' . htmlspecialchars($value['barcode']) . '</span>
                                    </p>
                                    <p class="text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span class="' . $quantidadeClass . '">Quantidade: ' . htmlspecialchars($value['quantidade']) . '</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <button onclick="abrirModalEditar(' . $value['id'] . ')" class="text-primary hover:text-secondary p-1 rounded-full bg-gray-50" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button onclick="abrirModalExcluir(' . $value['id'] . ', \'' . htmlspecialchars(addslashes($value['nome_produto'])) . '\')" class="text-red-500 hover:text-red-700 p-1 rounded-full bg-gray-50" title="Excluir">
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
            <a href="../view/relatorios.php">
                <button id="exportarBtn" class="bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-opacity-90 transition-colors flex items-center shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Exportar para PDF
                </button></a>
            </div>
            
            <!-- Modal de Edição -->
            <div id="modalEditar" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                    <button onclick="fecharModalEditar()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <h2 class="text-2xl font-bold text-primary mb-4">Editar Produto</h2>
                    <form id="formEditar" action="../control/controllerEditarProduto.php" method="POST" class="space-y-4">
                        <input type="hidden" id="editar_id" name="editar_id">
                        
                        <div>
                            <label for="editar_barcode" class="block text-sm font-medium text-gray-700 mb-1">Código de Barras</label>
                            <input type="text" id="editar_barcode" name="editar_barcode" required
                                class="w-full px-4 py-2 border-2 border-primary rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="editar_nome" class="block text-sm font-medium text-gray-700 mb-1">Nome do Produto</label>
                            <input type="text" id="editar_nome" name="editar_nome" required
                                class="w-full px-4 py-2 border-2 border-primary rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="editar_quantidade" class="block text-sm font-medium text-gray-700 mb-1">Quantidade</label>
                            <input type="number" id="editar_quantidade" name="editar_quantidade" min="0" required
                                class="w-full px-4 py-2 border-2 border-primary rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="editar_natureza" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                            <select id="editar_natureza" name="editar_natureza" required
                                class="w-full px-4 py-2 border-2 border-primary rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent">
                                <option value="">Selecione a categoria</option>
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
                        </div>
                        
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="fecharModalEditar()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-opacity-90 transition-colors">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal de Exclusão -->
            <div id="modalExcluir" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                    <button onclick="fecharModalExcluir()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Tem certeza?</h2>
                        <p class="text-gray-600 mb-6">Você está prestes a excluir <span id="nomeProdutoExcluir" class="font-semibold"></span>. Esta ação não pode ser desfeita.</p>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button onclick="fecharModalExcluir()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <a id="linkExcluir" href="#" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            Sim, excluir
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Alerta de mensagem -->
            <div id="alertaMensagem" class="fixed bottom-4 right-4 p-4 rounded-lg shadow-lg max-w-md hidden animate-fade-in z-50 bg-green-500 text-white">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span id="mensagemTexto">Operação realizada com sucesso!</span>
                </div>
            </div>
        </main>
    
        <footer class="bg-gradient-to-r from-primary to-dark text-white py-6 mt-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                <div class="border-t border-white/20 pt-4 mt-4 text-center">
                    <p class="text-xs">
                        © 2024 STGM v1.2.0 | Desenvolvido por alunos EEEP STGM
                    </p>
                </div>
            </div>
        </footer>
    
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const menuButton = document.getElementById("menuButton");
                const headerNav = document.getElementById("headerNav");
                
                if (menuButton && headerNav) {
                    menuButton.addEventListener("click", function() {
                        headerNav.classList.toggle("show");
                        
                        // Animação para o botão do menu
                        const spans = menuButton.querySelectorAll("span");
                        spans.forEach(span => {
                            span.classList.toggle("active");
                        });
                    });
                }
                
                // Adicionar suporte para dropdown no mobile
                const dropdownToggle = document.querySelector(".group > a");
                const dropdownMenu = document.querySelector(".group > div");
                
                if (window.innerWidth <= 768 && dropdownToggle && dropdownMenu) {
                    dropdownToggle.addEventListener("click", function(e) {
                        e.preventDefault();
                        dropdownMenu.classList.toggle("scale-0");
                        dropdownMenu.classList.toggle("scale-100");
                    });
                }

                // Funções para o modal de edição
                window.abrirModalEditar = function(id) {
                    // Buscar dados do produto
                    fetch(`../control/controllerEditarProduto.php?id=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.erro) {
                                mostrarAlerta(data.erro, true);
                                return;
                            }
                            
                            // Preencher o formulário
                            document.getElementById("editar_id").value = data.id;
                            document.getElementById("editar_barcode").value = data.barcode;
                            document.getElementById("editar_nome").value = data.nome_produto;
                            document.getElementById("editar_quantidade").value = data.quantidade;
                            document.getElementById("editar_natureza").value = data.natureza;
                            
                            // Mostrar o modal
                            document.getElementById("modalEditar").classList.remove("hidden");
                        })
                        .catch(error => {
                            console.error("Erro ao buscar dados do produto:", error);
                            mostrarAlerta("Ocorreu um erro ao carregar os dados do produto", true);
                        });
                };
                
                window.fecharModalEditar = function() {
                    document.getElementById("modalEditar").classList.add("hidden");
                };
                
                // Funções para o modal de exclusão
                window.abrirModalExcluir = function(id, nome) {
                    document.getElementById("nomeProdutoExcluir").textContent = nome;
                    document.getElementById("linkExcluir").href = `../control/controllerApagarProduto.php?id=${id}`;
                    document.getElementById("modalExcluir").classList.remove("hidden");
                };
                
                window.fecharModalExcluir = function() {
                    document.getElementById("modalExcluir").classList.add("hidden");
                };
                
                // Função para mostrar alertas
                window.mostrarAlerta = function(mensagem, erro = false) {
                    const alerta = document.getElementById("alertaMensagem");
                    const textoAlerta = document.getElementById("mensagemTexto");
                    
                    if (erro) {
                        alerta.classList.remove("bg-green-500");
                        alerta.classList.add("bg-red-500");
                    } else {
                        alerta.classList.remove("bg-red-500");
                        alerta.classList.add("bg-green-500");
                    }
                    
                    textoAlerta.textContent = mensagem;
                    alerta.classList.remove("hidden");
                    
                    setTimeout(() => {
                        alerta.classList.add("opacity-0");
                        setTimeout(() => {
                            alerta.classList.add("hidden");
                            alerta.classList.remove("opacity-0");
                        }, 500);
                    }, 5000);
                };
                
                // Funções originais de filtro e pesquisa
                const pesquisarInput = document.getElementById("pesquisar");
                const filtroCategoria = document.getElementById("filtroCategoria");
                const filtrarBtn = document.getElementById("filtrarBtn");
                const exportarBtn = document.getElementById("exportarBtn");
                
                // Log para depuração
                console.log("Mobile cards container:", document.querySelector(".mobile-cards"));
                console.log("Número de cards:", document.querySelectorAll(".mobile-cards .card-item").length);
    
                // Verificar se há mensagens ou erros na URL
                const urlParams = new URLSearchParams(window.location.search);
                const mensagem = urlParams.get("mensagem");
                const erro = urlParams.get("erro");
                
                if (mensagem) {
                    mostrarAlerta(mensagem, false);
                } else if (erro) {
                    mostrarAlerta(erro, true);
                }
                
                // Função para mostrar os cabeçalhos de categorias relevantes
                function mostrarCategoriaHeaders() {
                    const categoriasVisiveis = new Set();
                    const cards = document.querySelectorAll(".mobile-cards .card-item");
                    
                    // Esconder todos os cabeçalhos inicialmente
                    const headers = document.querySelectorAll(".mobile-cards .categoria-header");
                    headers.forEach(h => h.style.display = "none");
                    
                    // Identificar quais categorias têm cards visíveis
                    cards.forEach(card => {
                        if (card.style.display !== "none") {
                            let header = card.previousElementSibling;
                            while (header && !header.classList.contains("categoria-header")) {
                                header = header.previousElementSibling;
                            }
                            if (header) {
                                categoriasVisiveis.add(header);
                            }
                        }
                    });
                    
                    // Mostrar cabeçalhos das categorias visíveis
                    categoriasVisiveis.forEach(header => {
                        header.style.display = "block";
                    });
                }
                
                // Debounce para melhorar performance da pesquisa
                let timeoutId;
                
                // Função para filtrar produtos
                function filtrarProdutos() {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        const termoPesquisa = pesquisarInput.value.toLowerCase().trim();
                        const categoria = filtroCategoria.value.toLowerCase();
                        
                        // Filtrar tabela (desktop)
                        const linhasTabela = document.querySelectorAll(".desktop-table tbody tr");
                        let temResultadosDesktop = false;
                        
                        linhasTabela.forEach(linha => {
                            const barcode = linha.cells[0].textContent.toLowerCase();
                            const nome = linha.cells[1].textContent.toLowerCase();
                            const natureza = linha.cells[3].textContent.toLowerCase();
                            
                            const matchTermo = termoPesquisa === "" || 
                                               barcode.includes(termoPesquisa) || 
                                               nome.includes(termoPesquisa);
                            const matchCategoria = categoria === "" || natureza === categoria;
                            
                            if (matchTermo && matchCategoria) {
                                linha.style.display = "";
                                temResultadosDesktop = true;
                            } else {
                                linha.style.display = "none";
                            }
                        });
                        
                        // Mensagem de "nenhum resultado" para desktop
                        const mensagemDesktop = document.querySelector(".desktop-table .sem-resultados");
                        if (!temResultadosDesktop) {
                            if (!mensagemDesktop) {
                                const tr = document.createElement("tr");
                                tr.className = "sem-resultados";
                                const td = document.createElement("td");
                                td.colSpan = 5;
                                td.className = "py-4 px-4 text-center text-gray-500";
                                td.textContent = "Nenhum produto encontrado";
                                tr.appendChild(td);
                                document.querySelector(".desktop-table tbody").appendChild(tr);
                            }
                        } else if (mensagemDesktop) {
                            mensagemDesktop.remove();
                        }
                        
                        // Filtrar cards (mobile)
                        const cards = document.querySelectorAll(".mobile-cards .card-item");
                        let temResultadosMobile = false;
                        
                        cards.forEach(card => {
                            const nome = card.querySelector("h3").textContent.toLowerCase();
                            const barcode = card.querySelector("p span").textContent.toLowerCase();
                            
                            // Encontrar a categoria do card
                            let currentHeader = card.previousElementSibling;
                            while (currentHeader && !currentHeader.classList.contains("categoria-header")) {
                                currentHeader = currentHeader.previousElementSibling;
                            }
                            
                            const natureza = currentHeader ? 
                                currentHeader.querySelector("h3").textContent.toLowerCase() : "";
                            
                            const matchTermo = termoPesquisa === "" || 
                                               barcode.includes(termoPesquisa) || 
                                               nome.includes(termoPesquisa);
                            const matchCategoria = categoria === "" || natureza === categoria;
                            
                            if (matchTermo && matchCategoria) {
                                card.style.display = "";
                                temResultadosMobile = true;
                            } else {
                                card.style.display = "none";
                            }
                        });
                        
                        // Mensagem de "nenhum resultado" para mobile
                        const mensagemMobile = document.querySelector(".mobile-cards .sem-resultados");
                        if (!temResultadosMobile) {
                            if (!mensagemMobile) {
                                const div = document.createElement("div");
                                div.className = "sem-resultados text-center py-8 text-gray-500";
                                
                                const icon = document.createElement("i");
                                icon.className = "fas fa-box-open text-4xl mb-2";
                                
                                const p = document.createElement("p");
                                p.textContent = "Nenhum produto encontrado";
                                
                                div.appendChild(icon);
                                div.appendChild(p);
                                document.querySelector(".mobile-cards").appendChild(div);
                            }
                        } else if (mensagemMobile) {
                            mensagemMobile.remove();
                        }
                        
                        // Mostrar cabeçalhos relevantes
                        mostrarCategoriaHeaders();
                    }, 300); // Debounce de 300ms
                }
                
                // Event listeners com debounce
                pesquisarInput.addEventListener("input", filtrarProdutos);
                filtroCategoria.addEventListener("change", filtrarProdutos);
                
                // Exportar para PDF
                exportarBtn.addEventListener("click", function() {
                    window.location.href = "../control/gerar_relatorio.php";
                });
    
                // Inicializar visibilidade dos cards
                mostrarCategoriaHeaders();
            });
        </script>
    </body>
    </html>';
    }

    public function consultarestoque($barcode,)
    {
        $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
        $consulta = "SELECT quantidade FROM produtos WHERE barcode = :barcode";
        $query = $pdo->prepare($consulta);
        $query->bindValue(":barcode", $barcode);
        $query->execute();
        $produto = $query->fetch(PDO::FETCH_ASSOC);

        if ($produto) {
            header("location: ../view/adcprodutoexistente.php?barcode=" . urlencode($barcode));
        } else {
            header("location: ../view/adcnovoproduto.php?barcode=" . urlencode($barcode));
        }
    }

    public function adcaoestoque($barcode, $quantidade)
    {
        $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
        $consulta = "UPDATE produtos SET quantidade = quantidade + :quantidade WHERE barcode = :barcode";
        $query = $pdo->prepare($consulta);
        $query->bindValue(":quantidade", $quantidade);
        $query->bindValue(":barcode", $barcode);
        $query->execute();

        header("location:../view/estoque.php");
    }

    public function adcproduto($barcode, $nome,  $quantidade, $natureza)
    {

        $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
        $consulta = "INSERT INTO produtos VALUES (null, :barcode, :nome, :quantidade, :natureza)";
        $query = $pdo->prepare($consulta);
        $query->bindValue(":nome", $nome);
        $query->bindValue(":barcode", $barcode);
        $query->bindValue(":quantidade", $quantidade);
        $query->bindValue(":natureza", $natureza);
        $query->execute();

        header("location:../view/estoque.php");
    }
    public function solicitarproduto($valor_retirada, $barcode)
    {
        // Conexão com o banco de dados
        $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");

        // Atualiza a quantidade
        $consulta = "UPDATE produtos SET quantidade = quantidade - :valor_retirada WHERE barcode = :barcode";
        $query = $pdo->prepare($consulta);
        $query->bindValue(":valor_retirada", $valor_retirada);
        $query->bindValue(":barcode", $barcode);
        $query->execute();

        // Redireciona para a página de estoque
        header("Location: ../view/estoque.php");
    }

    public function editarProduto($id, $nome, $barcode, $quantidade, $natureza)
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $consulta = "UPDATE produtos SET barcode = :barcode, nome_produto = :nome, quantidade = :quantidade, natureza = :natureza WHERE id = :id";
            $query = $pdo->prepare($consulta);
            $query->bindValue(":id", $id);
            $query->bindValue(":barcode", $barcode);
            $query->bindValue(":nome", $nome);
            $query->bindValue(":quantidade", $quantidade);
            $query->bindValue(":natureza", $natureza);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao editar produto: " . $e->getMessage());
            return false;
        }
    }

    public function apagarProduto($id)
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $consulta = "DELETE FROM produtos WHERE id = :id";
            $query = $pdo->prepare($consulta);
            $query->bindValue(":id", $id);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao apagar produto: " . $e->getMessage());
            return false;
        }
    }

    public function buscarProdutoPorId($id)
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=gerenciamento_estoque", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $consulta = "SELECT * FROM produtos WHERE id = :id";
            $query = $pdo->prepare($consulta);
            $query->bindValue(":id", $id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar produto: " . $e->getMessage());
            return null;
        }
    }
}
