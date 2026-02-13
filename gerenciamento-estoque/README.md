# Sistema de Gerenciamento de Estoque Farmacêutico

Sistema completo para gerenciamento de estoque de farmácia com controle de produtos, lotes e movimentações.

## 🚀 Funcionalidades

### 📦 Produtos
- ✅ CRUD completo de produtos
- ✅ Cadastro com código, nome, tipo, fabricante
- ✅ Criação de lote inicial junto com produto
- ✅ Busca por código, nome e fabricante (case-insensitive)
- ✅ Validação de dados

### 📋 Lotes
- ✅ CRUD completo de lotes
- ✅ Controle de validade e quantidade
- ✅ Registro de entradas e saídas
- ✅ Alertas de vencimento automáticos
- ✅ Busca por número do lote e nome do produto
- ✅ Status visual (vencido, próximo ao vencer, OK)

### 📊 Movimentações
- ✅ Registro de entradas e saídas
- ✅ Filtros por tipo, produto e período
- ✅ Controle automático de quantidade
- ✅ Validação de saídas (não permite estoque negativo)
- ✅ Histórico completo

### 📈 Dashboard
- ✅ Visão geral do sistema
- ✅ Produtos com estoque baixo
- ✅ Alertas de validade
- ✅ Estatísticas em tempo real
- ✅ Dias para vencer formatados corretamente

### 🔍 Sistema de Busca
- ✅ Busca case-insensitive
- ✅ Busca parcial (contém)
- ✅ Paginação mantida com filtros
- ✅ Interface limpa e intuitiva

### ⌨️ Navegação por Teclado
- ✅ Sistema "pass true" para selects
- ✅ Navegação com setas ↑/↓
- ✅ Enter para selecionar
- ✅ Escape para cancelar
- ✅ Compatibilidade total com mouse

### 📥 Importação
- ✅ Importação em lote via Excel
- ✅ Validação de dados
- ✅ Tratamento de erros

## 🛠️ Tecnologias

- **Backend**: Laravel 10
- **Frontend**: Blade + Tailwind CSS
- **Banco**: SQLite
- **JavaScript**: Vanilla JS
- **Validação**: Laravel Validation

## 📋 Pré-requisitos

- PHP 8.1+
- Composer
- SQLite 3

## 🚀 Instalação

1. Clone o repositório
2. Copie `.env.example` para `.env`
3. Configure o banco de dados
4. Execute `composer install`
5. Execute `php artisan migrate`
6. Execute `php artisan db:seed --class=ProdutoSeeder`
7. Execute `php artisan serve`

## 📁 Estrutura do Projeto

```
gerenciamento-estoque/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── produtos/
│       ├── lotes/
│       ├── movimentacoes/
│       └── importacao/
├── routes/
└── storage/
```

## 🎯 Destaques

- **Interface responsiva** e moderna
- **Validação robusta** de dados
- **Sistema de busca** eficiente
- **Navegação por teclado** profissional
- **Alertas automáticos** de vencimento
- **Controle completo** de estoque
- **Relatórios** e estatísticas

## 📝 Licença

MIT License

---

**Desenvolvido com ❤️ para gestão farmacêutica eficiente**
