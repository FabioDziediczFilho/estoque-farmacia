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
