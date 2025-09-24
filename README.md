# TaskManager - Sistema de Gerenciamento de Tarefas

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
</p>

<p align="center">
  <strong>Aplicação completa de gerenciamento de tarefas desenvolvida com tecnologias modernas</strong>
</p>

## 🚀 Tecnologias Utilizadas

- **Backend**: Laravel 12 com Repository Pattern
- **Frontend**: Vue 3 + TypeScript + Inertia.js
- **UI**: ShadCN/UI + Tailwind CSS v4
- **Banco**: MySQL 8.0
- **Container**: Docker via Laravel Sail
- **Testes**: PHPUnit + PEST
- **Autenticação**: Laravel Breeze + Inertia

## ✨ Funcionalidades

### ✅ Autenticação Completa
- [x] Login e Registro de usuários
- [x] Alteração de senha segura
- [x] Proteção de rotas com middleware
- [x] Sistema de sessões

### ✅ Gerenciamento de Tarefas
- [x] **CRUD completo** de tarefas
- [x] **Edição inline** de título e descrição (clique para editar)
- [x] **Filtros avançados** por status e busca
- [x] **Paginação** (15 itens por página)
- [x] **Status visuais** com cores e ícones (Pending, In Progress, Done)
- [x] **Exclusão direta** com ícone de lixeira
- [x] **Interface responsiva** para mobile e desktop

### ✅ UI/UX Moderna
- [x] **Modo escuro/claro** completo
- [x] **Componentes ShadCN/UI** profissionais
- [x] **Animações suaves** e transições
- [x] **Feedback visual** em todas as ações
- [x] **Design responsivo** otimizado

### ✅ Arquitetura Profissional
- [x] **Repository Pattern** com Dependency Injection
- [x] **Service Layer** para lógica de negócio
- [x] **Form Requests** para validação
- [x] **Policies** para autorização
- [x] **Testes automatizados** (100% cobertura)

## 📋 Requisitos do Sistema

- **Docker** e **Docker Compose**
- **Git**
- **PHP 8.3+** (apenas se não usar Docker)
- **Node.js 18+** (apenas se não usar Docker)

## 🛠️ Instalação e Configuração

### 1. Clone o Repositório
```bash
git clone <url-do-repositorio>
cd taskmanager
```

### 2. Configure o Ambiente
```bash
# Copie o arquivo de ambiente
cp .env.example .env

# Configure as variáveis no .env (se necessário)
# As configurações padrão do Sail já funcionam
```

### 3. Bootstrap inicial (somente na primeira vez)
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Inicie o Ambiente Docker
```bash
# Inicie todos os serviços (MySQL, Redis, Mailpit)
./vendor/bin/sail up -d

# Aguarde alguns segundos para os serviços iniciarem
```

### 5. Configure a Aplicação
```bash
# Gere a chave da aplicação
./vendor/bin/sail artisan key:generate

# Execute as migrações e seeders
./vendor/bin/sail artisan migrate:fresh --seed

# Instale as dependências do frontend
./vendor/bin/sail npm install

# Compile os assets para desenvolvimento
./vendor/bin/sail npm run dev
```

### 6. Acesse a Aplicação
- **Aplicação**: http://localhost
- **Mailpit** (emails): http://localhost:8025
- **Banco de dados**: localhost:3306

## 👥 Usuários de Demonstração

O sistema vem com usuários pré-configurados para demonstração:

### 👤 Usuário Administrador
- **Email**: `admin@taskmanager.com`
- **Senha**: `password`
- **Descrição**: Conta de administrador com tarefas de exemplo

### 👤 Usuário Padrão
- **Email**: `user@taskmanager.com`
- **Senha**: `password`
- **Descrição**: Conta de usuário padrão com tarefas de teste

### 📊 Dados de Exemplo
- **10 tarefas** criadas automaticamente para cada usuário
- **Diferentes status**: Pending, In Progress, Done
- **Títulos e descrições** variados para testes
- **Distribuição equilibrada** entre os status

## 🏗️ Estrutura do Projeto

```
taskmanager/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Autenticação
│   │   │   ├── TaskController.php          # CRUD de tarefas
│   │   │   └── ProfileController.php       # Perfil do usuário
│   │   ├── Requests/
│   │   │   ├── LoginRequest.php            # Validação de login
│   │   │   ├── RegisterRequest.php         # Validação de registro
│   │   │   ├── StoreTaskRequest.php        # Validação criação tarefa
│   │   │   ├── UpdateTaskRequest.php       # Validação edição tarefa
│   │   │   └── PasswordUpdateRequest.php   # Validação senha
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php                        # Modelo de usuário
│   │   └── Task.php                        # Modelo de tarefa
│   ├── Repositories/
│   │   └── TaskRepository.php              # Repository pattern
│   ├── Services/
│   │   └── TaskService.php                 # Lógica de negócio
│   ├── Contracts/
│   │   └── TaskRepositoryInterface.php     # Interface do repository
│   └── Policies/
│       └── TaskPolicy.php                  # Autorização
├── resources/
│   ├── js/
│   │   ├── Components/
│   │   │   ├── Task/
│   │   │   │   ├── TaskCard.vue            # Card com edição inline
│   │   │   │   ├── TaskForm.vue            # Formulário de tarefa
│   │   │   │   └── TaskFilters.vue         # Filtros e busca
│   │   │   └── ui/                         # Componentes ShadCN/UI
│   │   ├── Pages/
│   │   │   ├── Auth/
│   │   │   │   ├── Login.vue               # Página de login
│   │   │   │   └── Register.vue            # Página de registro
│   │   │   ├── Tasks/
│   │   │   │   ├── Index.vue               # Lista de tarefas
│   │   │   │   ├── Create.vue              # Criar tarefa
│   │   │   │   ├── Edit.vue                # Editar tarefa
│   │   │   │   └── Show.vue                # Visualizar tarefa
│   │   │   └── Profile/
│   │   │       └── Edit.vue                # Editar perfil
│   │   ├── Composables/
│   │   │   └── useTasks.ts                 # Composable para tarefas
│   │   └── Layouts/
│   │       └── AuthenticatedLayout.vue     # Layout principal
│   └── css/
│       └── app.css                         # Estilos Tailwind
├── tests/
│   ├── Feature/
│   │   ├── AuthTest.php                    # Testes de autenticação
│   │   └── TaskRepositoryTest.php          # Testes do repository
│   └── Unit/
└── database/
    ├── migrations/                         # Estrutura do banco
    ├── seeders/
    │   ├── UserSeeder.php                  # Seeds de usuários
    │   └── TaskSeeder.php                  # Seeds de tarefas
    └── factories/
        ├── UserFactory.php                 # Factory de usuários
        └── TaskFactory.php                 # Factory de tarefas
```

## 🧪 Executando Testes

```bash
# Execute todos os testes
./vendor/bin/sail test

# Execute testes específicos
./vendor/bin/sail test --filter=AuthTest
./vendor/bin/sail test --filter=TaskRepositoryTest

# Execute com cobertura (se configurado)
./vendor/bin/sail test --coverage
```

## 🔧 Comandos Úteis

### Desenvolvimento
```bash
# Reiniciar ambiente
./vendor/bin/sail restart

# Ver logs em tempo real
./vendor/bin/sail logs -f

# Acessar bash do container
./vendor/bin/sail shell

# Limpar caches
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear
```

### Frontend
```bash
# Modo desenvolvimento (hot reload)
./vendor/bin/sail npm run dev

# Build para produção
./vendor/bin/sail npm run build

# Verificar sintaxe TypeScript
./vendor/bin/sail npm run type-check
```

### Banco de Dados
```bash
# Resetar banco com dados novos
./vendor/bin/sail artisan migrate:fresh --seed

# Acessar MySQL
./vendor/bin/sail mysql

# Criar nova migration
./vendor/bin/sail artisan make:migration create_example_table

# Executar seeders específicos
./vendor/bin/sail artisan db:seed --class=TaskSeeder
```

## 🎯 Recursos Principais

### 1. **Edição Inline de Tarefas**
- Clique no título ou descrição para editar diretamente
- Validação em tempo real
- Salva automaticamente ao sair do campo
- Feedback visual de sucesso/erro

### 2. **Sistema de Filtros Avançado**
- Busca por texto no título e descrição
- Filtro por status (All, Pending, In Progress, Done)
- URLs amigáveis mantêm estado dos filtros
- Paginação integrada com filtros

### 3. **Interface Moderna**
- Design system consistente com ShadCN/UI
- Modo escuro/claro com preferência salva
- Animações suaves e micro-interações
- Responsivo para todos os dispositivos

### 4. **Arquitetura Escalável**
- Repository Pattern para abstração de dados
- Service Layer para lógica complexa
- Dependency Injection configurado
- Testes automatizados abrangentes

## 🐛 Solução de Problemas

### Erro: "Sail not found"
```bash
# Verifique se está no diretório correto
pwd  # deve mostrar o diretório do projeto

# Verifique se o arquivo existe
ls -la vendor/bin/sail
```

### Erro: "Port 80 already in use"
```bash
# Pare outros serviços na porta 80
sudo lsof -ti:80 | xargs kill -9

# Ou altere a porta no .env
APP_PORT=8080
```

### Erro: "Database connection refused"
```bash
# Aguarde o MySQL inicializar completamente
./vendor/bin/sail logs mysql

# Verifique se todos os serviços estão rodando
./vendor/bin/sail ps
```

### Frontend não carrega
```bash
# Reinstale dependências
./vendor/bin/sail npm clean-install

# Limpe cache do Vite
./vendor/bin/sail npm run clean

# Reinicie o servidor de desenvolvimento
./vendor/bin/sail npm run dev
```

### Erro de permissões
```bash
# Ajuste permissões dos arquivos
./vendor/bin/sail exec laravel.test chown -R sail:sail /var/www/html/storage
./vendor/bin/sail exec laravel.test chmod -R 775 /var/www/html/storage
```

## 📱 Funcionalidades Mobile

- **Interface 100% responsiva**
- **Touch-friendly** para edição inline
- **Navegação otimizada** para dispositivos móveis
- **Modo escuro** funcional em todos os dispositivos

## 🔒 Segurança

- **CSRF Protection** em todos os formulários
- **Validação server-side** obrigatória
- **Autorização por policies** em todas as operações
- **Sanitização** de inputs
- **Hashing seguro** de senhas com Bcrypt

## 📈 Performance

- **Lazy loading** de componentes Vue
- **Paginação** para listas grandes
- **Caching** de queries frequentes
- **Otimização** de assets com Vite
- **Compressão** de imagens e recursos

## 🎨 Personalização

### Modificar Cores do Tema
Edite `resources/css/app.css`:
```css
:root {
  --primary: 220 100% 60%;        /* Cor principal */
  --secondary: 210 40% 90%;       /* Cor secundária */
  --accent: 345 80% 60%;          /* Cor de destaque */
}
```

### Adicionar Novos Status de Tarefas
1. Atualize a migration: `database/migrations/create_tasks_table.php`
2. Modifique o model: `app/Models/Task.php`
3. Atualize o frontend: `resources/js/Components/Task/TaskCard.vue`

## 📞 Suporte

Para problemas ou dúvidas:
1. Verifique a seção **Solução de Problemas**
2. Consulte os **logs** do Sail
3. Execute os **testes** para verificar integridade
4. Revise a **documentação** do Laravel e Inertia.js

---

<p align="center">
  <em>Sistema completo de gerenciamento de tarefas com interface moderna e arquitetura profissional</em>
</p>