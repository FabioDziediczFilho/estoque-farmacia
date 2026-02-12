<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Estoque')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-8">
                    <h1 class="text-xl font-bold">Sistema de Estoque</h1>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('dashboard') }}" class="hover:bg-blue-700 px-3 py-2 rounded">Dashboard</a>
                        <a href="{{ route('produtos.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded">Produtos</a>
                        <a href="{{ route('lotes.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded">Lotes</a>
                        <a href="{{ route('movimentacoes.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded">Movimentações</a>
                        <a href="{{ route('importacao.index') }}" class="hover:bg-blue-700 px-3 py-2 rounded">Importar</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Melhorar navegação por teclado para todos os selects
        const selects = document.querySelectorAll('select');
        
        selects.forEach(function(select) {
            let isOpen = false;
            let currentIndex = -1;
            let originalValue = select.value;
            
            // Função para abrir select
            function openSelect() {
                isOpen = true;
                select.focus();
                currentIndex = select.selectedIndex;
                highlightOption();
            }
            
            // Função para fechar select
            function closeSelect() {
                isOpen = false;
                removeHighlight();
            }
            
            // Função para destacar opção atual
            function highlightOption() {
                removeHighlight();
                if (currentIndex >= 0 && currentIndex < select.options.length) {
                    select.options[currentIndex].style.backgroundColor = '#3b82f6';
                    select.options[currentIndex].style.color = 'white';
                }
            }
            
            // Função para remover destaque
            function removeHighlight() {
                for (let i = 0; i < select.options.length; i++) {
                    select.options[i].style.backgroundColor = '';
                    select.options[i].style.color = '';
                }
            }
            
            // Event listeners
            select.addEventListener('focus', openSelect);
            select.addEventListener('blur', closeSelect);
            select.addEventListener('change', closeSelect);
            
            // Navegação por teclado
            select.addEventListener('keydown', function(e) {
                if (!isOpen && (e.key === 'ArrowDown' || e.key === 'ArrowUp' || e.key === 'Enter')) {
                    e.preventDefault();
                    openSelect();
                    return;
                }
                
                if (!isOpen) return;
                
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        currentIndex = Math.min(currentIndex + 1, select.options.length - 1);
                        highlightOption();
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        currentIndex = Math.max(currentIndex - 1, 0);
                        highlightOption();
                        break;
                        
                    case 'Enter':
                        e.preventDefault();
                        if (currentIndex >= 0) {
                            select.selectedIndex = currentIndex;
                            select.value = select.options[currentIndex].value;
                        }
                        closeSelect();
                        select.dispatchEvent(new Event('change'));
                        break;
                        
                    case 'Escape':
                        e.preventDefault();
                        select.selectedIndex = -1;
                        select.value = '';
                        closeSelect();
                        break;
                }
            });
            
            // Limpar destaque quando mouse passa sobre opções
            select.addEventListener('mouseover', function() {
                removeHighlight();
            });
        });
    });
    </script>
</body>
</html>
