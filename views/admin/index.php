<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGE - Painel Administrativo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient( #eeeeeeff 100%, #e4e4e4ff 50%, #ddddddff 100%);
            min-height: 100vh;
            color: #fff;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Enhanced Sidebar for Admin */
        .sidebar {
            width: 300px;
            background: rgba(38, 66, 131, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.3);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(147, 51, 234, 0.1));
        }

        .admin-logo {
            font-size: 28px;
            font-weight: 900;
            background: linear-gradient(135deg, #ef4444, #9333ea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-logo::before {
            content: "⚡";
            font-size: 32px;
            -webkit-text-fill-color: #ef4444;
        }

        .admin-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .admin-info {
            margin-top: 20px;
            padding: 20px;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 16px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .admin-name {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .admin-status {
            font-size: 12px;
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 10px #10b981;
        }

        .nav-menu {
            padding: 25px 0;
        }

        .nav-item {
            padding: 18px 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            display: flex;
            align-items: center;
            gap: 15px;
            color: rgba(255, 255, 255, 0.7);
            position: relative;
            font-size: 15px;
        }

        .nav-item:hover {
            background: rgba(239, 68, 68, 0.1);
            border-left-color: #ef4444;
            color: #fff;
            transform: translateX(8px);
        }

        .nav-item.active {
            background: rgba(239, 68, 68, 0.2);
            border-left-color: #ef4444;
            color: #fff;
            font-weight: 600;
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            right: 20px;
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            box-shadow: 0 0 10px #ef4444;
        }

        .nav-icon {
            font-size: 22px;
            width: 24px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.02);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .header {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            padding: 25px 30px;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-title {
            font-size: 32px;
            font-weight: 900;
            background: linear-gradient(135deg, #ef4444, #9333ea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .system-status {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(16, 185, 129, 0.1);
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid rgba(16, 185, 129, 0.2);
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 12px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .admin-card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .admin-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #ef4444, #9333ea);
        }

        .admin-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-icon {
            font-size: 28px;
            padding: 15px;
            border-radius: 16px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .card-value {
            font-size: 42px;
            font-weight: 900;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #fff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 20px;
        }

        .progress-bar {
            background: rgba(255, 255, 255, 0.1);
            height: 8px;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #ef4444, #9333ea);
            border-radius: 20px;
            transition: width 2s ease;
        }

        .progress-text {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: space-between;
        }

        /* Users Management */
        .users-section {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 900;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            background: linear-gradient(135deg, #ef4444, #9333ea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            color: #fff;
            font-size: 14px;
            width: 300px;
            min-width: 250px;
        }

        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow: hidden;
            border-radius: 16px;
        }

        .data-table th,
        .data-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .data-table th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .data-table td {
            color: rgba(255, 255, 255, 0.8);
        }

        .data-table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444, #9333ea);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 14px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-inactive {
            background: rgba(107, 114, 128, 0.2);
            color: #9ca3af;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }

        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* Profile Form */
        .profile-form {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #ef4444;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.2);
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444, #9333ea);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            font-size: 48px;
            margin: 0 auto 30px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        /* Activity Feed */
        .activity-feed {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px 15px;
            margin: 0 -15px;
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
            font-size: 15px;
        }

        .activity-time {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }

        .activity-value {
            font-weight: 700;
            color: #ef4444;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .sidebar-header {
                padding: 20px 15px;
            }

            .admin-logo {
                font-size: 0;
            }

            .admin-info,
            .admin-badge {
                display: none;
            }

            .nav-item {
                padding: 15px;
                justify-content: center;
            }

            .nav-item span:not(.nav-icon) {
                display: none;
            }

            .main-content {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .search-box {
                width: 100%;
                min-width: auto;
            }

            .table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }
        }

        /* Loading Animation */
        .loading {
            opacity: 0;
            animation: slideIn 0.8s ease forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Pulse Animation */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }

        .card-value {
            animation: pulse 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Enhanced Admin Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="admin-logo">Admin</div>
                <div class="admin-badge">Area de Administrador</div>
                <div class="admin-info">
                    <div class="admin-name">Nome Usuario</div>
                    <div class="admin-status">
                        <span class="status-indicator"></span>
                        Online - Sistema Ativo
                    </div>
                </div>
            </div>
            
            <nav class="nav-menu">
                <div class="nav-item active" onclick="switchSection('dashboard')">
                    <span class="nav-icon">📊</span>
                    <span>Dashboard</span>
                </div>
                <div class="nav-item" onclick="switchSection('users')">
                    <span class="nav-icon">👥</span>
                    <span>Gestão de Usuários</span>
                </div>
                <div class="nav-item" onclick="switchSection('profile')">
                    <span class="nav-icon">👤</span>
                    <span>Editar Perfil</span>
                </div>
                <div class="nav-item" onclick="logout()">
                    <span class="nav-icon">🚪</span>
                    <span>Sair do Admin</span>
                </div>
            </nav>
        </div>

        <!-- Main Admin Content -->
        <div class="main-content">
            <!-- Dashboard Section -->
            <div id="dashboard" class="content-section active">
                <div class="header">
                    <div class="header-left">
                        <h1 class="header-title">Dashboard Administrativo</h1>
                        <div class="system-status">
                            <span class="status-indicator"></span>
                            <!-- Sistema Operacional -->
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn-warning" onclick="generateReport()">
                            📊 Relatório
                        </button>
                        <button class="btn btn-primary" onclick="refreshData()">
                            🔄 Atualizar Dados
                        </button>
                    </div>
                </div>

                <!-- Admin Dashboard Cards -->
                <div class="dashboard-grid loading">
                    <div class="admin-card">
                        <div class="card-header">
                            <div class="card-title">Usuários Totais</div>
                            <div class="card-icon">👥</div>
                        </div>
                        <div class="card-value">1,247</div>
                        <div class="card-subtitle">Usuários registrados no sistema</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 73%"></div>
                        </div>
                        <div class="progress-text">
                            <span>Meta mensal</span>
                            <span>73%</span>
                        </div>
                    </div>

                    <div class="admin-card">
                        <div class="card-header">
                            <div class="card-title">Usuários Ativos</div>
                            <div class="card-icon">⚡</div>
                        </div>
                        <div class="card-value">847</div>
                        <div class="card-subtitle">Ativos nos últimos 30 dias</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 68%"></div>
                        </div>
                        <div class="progress-text">
                            <span>Taxa de atividade</span>
                            <span>68%</span>
                        </div>
                    </div>

                    <div class="admin-card">
                        <div class="card-header">
                            <div class="card-title">Produtos Cadastrados</div>
                            <div class="card-icon">📦</div>
                        </div>
                        <div class="card-value">3,892</div>
                        <div class="card-subtitle">Produtos no estoque</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 85%"></div>
                        </div>
                        <div class="progress-text">
                            <span>Catálogo</span>
                            <span>85%</span>
                        </div>
                    </div>

                    <div class="admin-card">
                        <div class="card-header">
                            <div class="card-title">Transações Hoje</div>
                            <div class="card-icon">💳</div>
                        </div>
                        <div class="card-value">156</div>
                        <div class="card-subtitle">Movimentações realizadas</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 92%"></div>
                        </div>
                        <div class="progress-text">
                            <span>Meta diária</span>
                            <span>92%</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="activity-feed loading">
                    <h2 class="section-title">
                        🕒 Atividades Recentes do Sistema
                    </h2>
                    
                    <div class="activity-item">
                        <div class="activity-icon">👥</div>
                        <div class="activity-content">
                            <div class="activity-title">Novo usuário cadastrado - <span class="activity-value">Maria Silva</span></div>
                            <div class="activity-time">Há 5 minutos</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">📦</div>
                        <div class="activity-content">
                            <div class="activity-title">Produto adicionado ao catálogo - <span class="activity-value">Notebook HP</span></div>
                            <div class="activity-time">Há 12 minutos</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">⚠️</div>
                        <div class="activity-content">
                            <div class="activity-title">Alerta de sistema - <span class="activity-value">Backup automático concluído</span></div>
                            <div class="activity-time">Há 1 hora</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">🔧</div>
                        <div class="activity-content">
                            <div class="activity-title">Configuração alterada - <span class="activity-value">Permissões de usuário</span></div>
                            <div class="activity-time">Há 2 horas</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Management Section -->
            <div id="users" class="content-section">
                <div class="header">
                    <div class="header-left">
                        <h1 class="header-title">Gestão de Usuários</h1>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn-primary" onclick="exportUsers()">
                            📤 Exportar Lista
                        </button>
                        <button class="btn btn-danger" onclick="addUser()">
                            ➕ Novo Usuário
                        </button>
                    </div>
                </div>

                <div class="users-section loading">
                    <div class="table-header">
                        <h2 class="section-title">
                            👥 Lista de Usuários
                        </h2>
                        <input type="text" class="search-box" placeholder="Buscar usuários..." onkeyup="searchUsers(this.value)">
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Email</th>
                                <th>Nível</th>
                                <th>Status</th>
                                <th>Último Acesso</th>