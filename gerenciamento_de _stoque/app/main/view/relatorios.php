<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Relatórios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#005A24',
                        secondary: '#FFA500',
                        accent: '#E6F4EA',
                        dark: '#1A3C34',
                        light: '#F8FAF9',
                        white: '#FFFFFF'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif']
                    },
                    boxShadow: {
                        card: '0 10px 15px -3px rgba(0, 90, 36, 0.1), 0 4px 6px -2px rgba(0, 90, 36, 0.05)',
                        'card-hover': '0 20px 25px -5px rgba(0, 90, 36, 0.2), 0 10px 10px -5px rgba(0, 90, 36, 0.1)'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
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
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #FFA500;
            border-radius: 3px;
        }

        .card-item {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            will-change: transform;
        }

        .card-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 90, 36, 0.2), 0 10px 10px -5px rgba(0, 90, 36, 0.1);
            border-color: #FFA500;
        }
        
        .card-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 165, 0, 0.1) 0%, rgba(0, 90, 36, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .card-item:hover::before {
            opacity: 1;
        }

        .card-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0) 100%);
            transform: skewX(-25deg);
            transition: all 0.75s ease;
            z-index: 2;
        }

        .card-item:hover .card-shine {
            left: 150%;
        }

        .social-icon {
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-3px);
            filter: drop-shadow(0 4px 3px rgba(255, 165, 0, 0.3));
        }
        
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
            content: '';
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
    </style>
</head>

<body class="min-h-screen flex flex-col font-sans bg-light">
    <!-- Header -->
    <header class="sticky top-0 bg-gradient-to-r from-primary to-dark text-white py-4 shadow-lg z-50">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="../index.php" class="flex items-center">
                    <img src="../assets/imagens/logostgm.png" alt="Logo S" class="h-12 mr-3 transition-transform hover:scale-105">
                    <span class="text-white font-heading text-xl font-semibold hidden md:inline">STGM Estoque</span>
                </a>
            </div>
            
            <button class="mobile-menu-button focus:outline-none" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <nav class="header-nav md:flex items-center space-x-1">
<<<<<<< Updated upstream
                <a href="paginainicial.php" class="header-nav-link flex items-center">
=======
                <a href="./paginainicial.php" class="header-nav-link flex items-center">
>>>>>>> Stashed changes
                    <i class="fas fa-home mr-2"></i>
                    <span>Início</span>
                </a>
                <a href="estoque.php" class="header-nav-link flex items-center">
                    <i class="fas fa-boxes mr-2"></i>
                    <span>Estoque</span>
                </a>
                <a href="adicionarproduto.php" class="header-nav-link flex items-center">
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
                        <a href="solicitar.php" class="block px-4 py-2 text-primary hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-clipboard-check mr-2"></i>Solicitar Produto
                        </a>
                        <a href="solicitarnovproduto.php" class="block px-4 py-2 text-primary hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-plus-square mr-2"></i>Solicitar Novo Produto
                        </a>
                    </div>
                </div>
                <a href="relatorios.php" class="header-nav-link active flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    <span>Relatórios</span>
                </a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8 md:py-12 flex-1">
        <h1 class="text-primary text-3xl md:text-4xl font-bold mb-8 md:mb-12 text-center page-title tracking-tight font-heading">GERAR RELATÓRIOS</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <!-- Relatório de Estoque -->
            <div class="card-item bg-white border-2 border-primary rounded-xl shadow-lg p-6 flex flex-col items-center animate-fade-in">
                <div class="card-shine"></div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-primary mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h2 class="text-xl font-bold text-primary mb-2">Relatório de Estoque</h2>
                <p class="text-gray-600 text-center mb-4">Gerar relatório completo do estoque atual</p>
                <button class="bg-secondary text-white py-2 px-4 rounded-lg hover:bg-opacity-90 transition-colors font-semibold">
                    Gerar Relatório
                </button>
            </div>

            <!-- Relatório de Estoque por Data -->
            <div class="card-item bg-white border-2 border-primary rounded-xl shadow-lg p-6 flex flex-col items-center animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-shine"></div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-primary mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h2 class="text-xl font-bold text-primary mb-2">Estoque por Data</h2>
                <p class="text-gray-600 text-center mb-4">Relatório de estoque por período</p>
                <button class="bg-secondary text-white py-2 px-4 rounded-lg hover:bg-opacity-90 transition-colors font-semibold">
                    Gerar Relatório
                </button>
            </div>

            <!-- Relatório de Estoque por Categoria -->
            <div class="card-item bg-white border-2 border-primary rounded-xl shadow-lg p-6 flex flex-col items-center animate-fade-in" style="animation-delay: 0.2s">
                <div class="card-shine"></div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-primary mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <h2 class="text-xl font-bold text-primary mb-2">Estoque por Categoria</h2>
                <p class="text-gray-600 text-center mb-4">Relatório por categoria de produtos</p>
                <button class="bg-secondary text-white py-2 px-4 rounded-lg hover:bg-opacity-90 transition-colors font-semibold">
                    Gerar Relatório
                </button>
            </div>

            <!-- Relatório de Solicitações -->
            <div class="card-item bg-white border-2 border-primary rounded-xl shadow-lg p-6 flex flex-col items-center animate-fade-in" style="animation-delay: 0.3s">
                <div class="card-shine"></div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-primary mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h2 class="text-xl font-bold text-primary mb-2">Relatório de Solicitações</h2>
                <p class="text-gray-600 text-center mb-4">Histórico de solicitações realizadas</p>
                <button class="bg-secondary text-white py-2 px-4 rounded-lg hover:bg-opacity-90 transition-colors font-semibold">
                    Gerar Relatório
                </button>
            </div>

            <!-- Relatório Personalizado -->
            <div class="card-item bg-white border-2 border-primary rounded-xl shadow-lg p-6 flex flex-col items-center animate-fade-in" style="animation-delay: 0.4s">
                <div class="card-shine"></div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-primary mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <h2 class="text-xl font-bold text-primary mb-2">Relatório Personalizado</h2>
                <p class="text-gray-600 text-center mb-4">Combina estoque, data e categoria</p>
                <button class="bg-secondary text-white py-2 px-4 rounded-lg hover:bg-opacity-90 transition-colors font-semibold">
                    Criar Relatório
                </button>
            </div>
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
</body>

</html>


