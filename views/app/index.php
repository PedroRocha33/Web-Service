<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGE - Sistema de Gestão de Estoque</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #304abdff 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo::before {
            content: "📦";
            font-size: 28px;
        }

        .user-info {
            margin-top: 15px;
            padding: 15px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 12px;
            color: white;
        }

        .user-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .user-role {
            font-size: 12px;
            opacity: 0.9;
        }

        .nav-menu {
            padding: 20px 0;
        }

        .nav-item {
            padding: 15px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            display: flex;
            align-items: center;
            gap: 15px;
            color: #64748b;
        }

        .nav-item:hover {
            background: rgba(79, 70, 229, 0.1);
            border-left-color: #4f46e5;
            color: #4f46e5;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: rgba(79, 70, 229, 0.15);
            border-left-color: #4f46e5;
            color: #4f46e5;
            font-weight: 600;
        }

        .nav-icon {
            font-size: 20px;
            width: 24px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 25px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(79, 70, 229, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #64748b;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:hover {
            background: white;
            transform: translateY(-1px);
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #64748b;
        }

        .card-icon {
            font-size: 24px;
            padding: 12px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
        }

        .card-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .card-change {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .change-positive {
            color: #059669;
        }

        .change-negative {
            color: #dc2626;
        }

        /* Recent Activity */
        .activity-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: rgba(79, 70, 229, 0.05);
            border-radius: 12px;
            padding: 15px;
            margin: 0 -15px;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 3px;
        }

        .activity-time {
            font-size: 12px;
            color: #64748b;
        }

        .activity-value {
            font-weight: 600;
            color: #4f46e5;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-3px);
            border-color: #4f46e5;
            box-shadow: 0 12px 32px rgba(79, 70, 229, 0.2);
        }

        .action-icon {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
        }

        .action-title {
            font-weight: 600;
            color: #1e293b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar-header {
                padding: 20px 15px;
            }

            .logo {
                font-size: 0;
            }

            .logo::before {
                font-size: 24px;
            }

            .user-info {
                display: none;
            }

            .nav-item {
                padding: 15px;
                justify-content: center;
            }

            .nav-item span {
                display: none;
            }

            .main-content {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading {
            opacity: 0;
            animation: fadeIn 0.6s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        /* Pulse Animation for Cards */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }

        .card-value {
            animation: pulse 2s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo">Area de App</div>
                <div class="user-info">
                    <div class="user-name">Nome Usuario</div>
                    <div class="user-role">Administrador</div>
                </div>
            </div>
            
            <nav class="nav-menu">
                <div class="nav-item active" onclick="switchSection('dashboard')">
                    <span class="nav-icon">📊</span>
                    <span>Dashboard</span>
                </div>
                <div class="nav-item" onclick="switchSection('products')">
                    <span class="nav-icon">📦</span>
                    <span>Produtos</span>
                </div>
                <div class="nav-item" onclick="switchSection('inventory')">
                    <span class="nav-icon">📋</span>
                    <span>Estoque</span>
                </div>
                <div class="nav-item" onclick="switchSection('orders')">
                    <span class="nav-icon">🛒</span>
                    <span>Pedidos</span>
                </div>
                <div class="nav-item" onclick="switchSection('suppliers')">
                    <span class="nav-icon">🏢</span>
                    <span>Fornecedores</span>
                </div>
                <div class="nav-item" onclick="switchSection('reports')">
                    <span class="nav-icon">📈</span>
                    <span>Relatórios</span>
                </div>
                <div class="nav-item" onclick="switchSection('settings')">
                    <span class="nav-icon">⚙️</span>
                    <span>Configurações</span>
                </div>
                <div class="nav-item" onclick="logout()">
                    <span class="nav-icon">🚪</span>
                    <span>Sair</span>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div>
                    <h1 class="header-title">Dashboard</h1>
                </div>
                <div class="header-actions">
                    <button class="btn btn-secondary" onclick="showNotifications()">
                        🔔 Alertas
                    </button>
                    <button class="btn btn-primary" onclick="addProduct()">
                        ➕ Novo Produto
                    </button>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-grid loading">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Total de Produtos</div>
                        <div class="card-icon">📦</div>
                    </div>
                    <div class="card-value">1,247</div>
                    <div class="card-change change-positive">
                        ↗️ +12% este mês
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Valor do Estoque</div>
                        <div class="card-icon">💰</div>
                    </div>
                    <div class="card-value">R$ 89.340</div>
                    <div class="card-change change-positive">
                        ↗️ +8% este mês
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Produtos em Falta</div>
                        <div class="card-icon">⚠️</div>
                    </div>
                    <div class="card-value">23</div>
                    <div class="card-change change-negative">
                        ↘️ Atenção necessária
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Pedidos Hoje</div>
                        <div class="card-icon">📋</div>
                    </div>
                    <div class="card-value">47</div>
                    <div class="card-change change-positive">
                        ↗️ +15% vs ontem
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-section loading">
                <h2 class="section-title">
                    🕒 Atividades Recentes
                </h2>
                
                <div class="activity-item">
                    <div class="activity-icon">📦</div>
                    <div class="activity-content">
                        <div class="activity-title">Entrada de estoque - <span class="activity-value">Notebook Dell</span></div>
                        <div class="activity-time">Há 15 minutos</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">🛒</div>
                    <div class="activity-content">
                        <div class="activity-title">Novo pedido #1247 - <span class="activity-value">R$ 2.340</span></div>
                        <div class="activity-time">Há 32 minutos</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">⚠️</div>
                    <div class="activity-content">
                        <div class="activity-title">Alerta de estoque baixo - <span class="activity-value">Mouse Sem Fio</span></div>
                        <div class="activity-time">Há 1 hora</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">📈</div>
                    <div class="activity-content">
                        <div class="activity-title">Relatório mensal gerado</div>
                        <div class="activity-time">Há 2 horas</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions loading">
                <div class="action-card" onclick="addProduct()">
                    <span class="action-icon">➕</span>
                    <div class="action-title">Adicionar Produto</div>
                </div>

                <div class="action-card" onclick="registerEntry()">
                    <span class="action-icon">📥</span>
                    <div class="action-title">Entrada de Estoque</div>
                </div>

                <div class="action-card" onclick="registerExit()">
                    <span class="action-icon">📤</span>
                    <div class="action-title">Saída de Estoque</div>
                </div>

                <div class="action-card" onclick="generateReport()">
                    <span class="action-icon">📊</span>
                    <div class="action-title">Gerar Relatório</div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>