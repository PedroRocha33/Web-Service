<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Armazém Conectado</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Web-Service/assets/faqs.css"">
</head>

<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="index.html" class="logo">
                    <i class="fas fa-box-open"></i>
                    <h1>Armazém Conectado</h1>
                </a>

                <nav>
                    <ul>
                        <li><a href="<?= url("/index#features") ?>">Funcionalidades</a></li>
                        <li><a href="<?= url("/index#benefits") ?>">Benefícios</a></li>
                        <li><a href="<?= url("/sobre") ?>">Sobre Nós</a></li>
                        <li><a href="<?= url("/contato") ?>">Contato</a></li>
                    </ul>
                </nav>

                <div class="auth-buttons">
                    <a href="login.html" class="btn btn-outline">Entrar</a>
                    <a href="cadastro.html" class="btn btn-primary">Cadastre-se</a>
                </div>

                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <section class="page-header">
        <div class="container">
            <h2>Perguntas Frequentes</h2>
            <div class="breadcrumb">
                <a href="index.html">Home</a>
                <span>></span>
                <span>FAQ</span>
            </div>
        </div>
    </section>

    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">Como podemos ajudar você?</h2>
            <p class="section-subtitle">Encontre respostas para as dúvidas mais comuns sobre o Armazém Conectado</p>

            <div class="faq-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar perguntas..." id="faq-search-input">
            </div>

            <div class="faq-categories">
                <div class="faq-category active" data-category="all">Todas</div>
                <div class="faq-category" data-category="basics">Básico</div>
                <div class="faq-category" data-category="account">Conta</div>
                <div class="faq-category" data-category="pricing">Planos e Preços</div>
                <div class="faq-category" data-category="features">Funcionalidades</div>
                <div class="faq-category" data-category="support">Suporte</div>
            </div>

            <div class="faq-list">
                <!-- Básico -->
                <div class="faq-item" data-category="basics">
                    <div class="faq-question">
                        O que é o Armazém Conectado?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>O Armazém Conectado é um sistema de gestão de estoque desenvolvido especialmente para
                            pequenas e médias empresas. Nossa plataforma combina tecnologia de ponta com facilidade de
                            uso, permitindo que empresas de todos os tamanhos possam otimizar seus processos de gestão
                            de estoque sem a necessidade de grandes investimentos.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="basics">
                    <div class="faq-question">
                        Como o Armazém Conectado pode ajudar minha empresa?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>O Armazém Conectado ajuda sua empresa a:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Controlar seu estoque em tempo real</li>
                            <li>Reduzir erros de inventário</li>
                            <li>Automatizar pedidos de reposição</li>
                            <li>Analisar dados de desempenho com relatórios intuitivos</li>
                            <li>Integrar facilmente com e-commerce e plataformas de vendas</li>
                            <li>Economizar tempo com processos mais eficientes</li>
                        </ul>
                    </div>
                </div>

                <div class="faq-item" data-category="basics">
                    <div class="faq-question">
                        Preciso instalar algum software para usar o Armazém Conectado?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Não! O Armazém Conectado é uma solução 100% baseada em nuvem (cloud), o que significa que
                            você não precisa instalar nenhum software no seu computador. Basta acessar nossa plataforma
                            através de um navegador web em qualquer dispositivo com conexão à internet. Também
                            oferecemos aplicativos para dispositivos móveis (iOS e Android) para facilitar o
                            gerenciamento em movimento.</p>
                    </div>
                </div>

                <!-- Conta -->
                <div class="faq-item" data-category="account">
                    <div class="faq-question">
                        Como criar uma conta no Armazém Conectado?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Criar uma conta no Armazém Conectado é simples e rápido. Basta clicar no botão "Cadastre-se"
                            no canto superior direito do site, preencher o formulário com seus dados básicos e escolher
                            um plano. Após confirmar seu e-mail, você já poderá acessar a plataforma e começar a
                            configurar seu sistema de gestão de estoque.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <div class="faq-question">
                        Quantos usuários posso adicionar à minha conta?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>O número de usuários que você pode adicionar à sua conta depende do plano escolhido:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li><strong>Plano Básico:</strong> Até 3 usuários</li>
                            <li><strong>Plano Profissional:</strong> Até 10 usuários</li>
                            <li><strong>Plano Empresarial:</strong> Usuários ilimitados</li>
                        </ul>
                        <p style="margin-top: 10px;">É possível adicionar usuários extras em qualquer plano por uma taxa
                            adicional mensal de R$29,90 por usuário.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <div class="faq-question">
                        Como posso definir permissões para diferentes usuários?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>O Armazém Conectado oferece um sistema robusto de controle de acesso baseado em funções. No
                            painel administrativo, acesse a seção "Usuários" e clique em "Gerenciar Permissões". Lá você
                            poderá definir exatamente quais funcionalidades cada usuário ou grupo de usuários pode
                            acessar, como:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Visualizar estoque</li>
                            <li>Adicionar/remover produtos</li>
                            <li>Realizar ajustes de inventário</li>
                            <li>Acessar relatórios financeiros</li>
                            <li>Configurar integrações</li>
                            <li>Gerenciar outros usuários</li>
                        </ul>
                    </div>
                </div>

                <!-- Planos e Preços -->
                <div class="faq-item" data-category="pricing">
                    <div class="faq-question">
                        Quais são os planos disponíveis?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Oferecemos três planos principais para atender às necessidades de diferentes tamanhos de
                            empresas:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li><strong>Plano Básico (R$99/mês):</strong> Ideal para microempresas com até 500 produtos.
                            </li>
                            <li><strong>Plano Profissional (R$199/mês):</strong> Perfeito para pequenas empresas com até
                                3.000 produtos e necessidades mais avançadas de gestão.</li>
                            <li><strong>Plano Empresarial (R$399/mês):</strong> Desenvolvido para médias empresas com
                                catálogo extenso e múltiplos pontos de venda.</li>
                        </ul>
                        <p style="margin-top: 10px;">Todos os planos incluem suporte técnico e atualizações regulares.
                        </p>
                    </div>
                </div>

                <div class="faq-item" data-category="pricing">
                    <div class="faq-question">
                        Existe um período de teste gratuito?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sim! Oferecemos um período de teste gratuito de 14 dias em qualquer plano. Durante esse
                            período, você terá acesso completo a todas as funcionalidades do plano escolhido para
                            avaliar se o Armazém Conectado atende às necessidades da sua empresa. Não é necessário
                            inserir dados de cartão de crédito para iniciar o período de teste.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="pricing">
                    <div class="faq-question">
                        Posso mudar de plano depois?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Absolutamente! Você pode fazer upgrade ou downgrade do seu plano a qualquer momento através
                            do painel administrativo, na seção "Minha Conta" > "Planos e Pagamentos". Ao fazer upgrade,
                            você será cobrado apenas pela diferença proporcional ao período restante do ciclo de
                            faturamento atual. Ao fazer downgrade, o novo valor será aplicado no próximo ciclo de
                            faturamento.</p>
                    </div>
                </div>

                <!-- Funcionalidades -->
                <div class="faq-item" data-category="features">
                    <div class="faq-question">
                        O sistema permite controle de múltiplos depósitos ou filiais?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sim, nos planos Profissional e Empresarial, o Armazém Conectado permite o gerenciamento de
                            múltiplos locais de estoque. Você pode:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Cadastrar diferentes depósitos, filiais ou pontos de venda</li>
                            <li>Visualizar o estoque disponível em cada local</li>
                            <li>Realizar transferências entre locais</li>
                            <li>Configurar regras de reposição específicas para cada local</li>
                            <li>Gerar relatórios consolidados ou segmentados por local</li>
                        </ul>
                    </div>
                </div>

                <div class="faq-item" data-category="features">
                    <div class="faq-question">
                        O Armazém Conectado se integra com outras plataformas?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sim, oferecemos uma ampla gama de integrações nativas com as principais plataformas de
                            e-commerce e sistemas empresariais do mercado, incluindo:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li><strong>E-commerce:</strong> Shopify, WooCommerce, Magento, Vtex, Nuvemshop</li>
                            <li><strong>Marketplaces:</strong> Mercado Livre, Amazon, B2W, Magazine Luiza</li>
                            <li><strong>ERP:</strong> SAP, Totvs, Sage</li>
                            <li><strong>Sistemas fiscais:</strong> Contabilizei, ContaAzul</li>
                            <li><strong>Transportadoras:</strong> Correios, Jadlog, Loggi, Transportadora Própria</li>
                        </ul>
                        <p style="margin-top: 10px;">Além disso, disponibilizamos uma API completa e documentada para
                            integrações personalizadas.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="features">
                    <div class="faq-question">
                        É possível gerar código de barras e QR codes no sistema?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sim, o Armazém Conectado permite gerar e imprimir etiquetas com códigos de barras e QR codes
                            para seus produtos e embalagens. O sistema suporta diversos formatos de códigos, como
                            EAN-13, CODE 128, UPC, entre outros. As etiquetas são totalmente personalizáveis, permitindo
                            incluir informações como:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Nome e descrição do produto</li>
                            <li>Preço de venda</li>
                            <li>Lote e data de validade</li>
                            <li>Informações de rastreabilidade</li>
                            <li>Logo da sua empresa</li>
                        </ul>
                        <p style="margin-top: 10px;">Também oferecemos suporte à leitura desses códigos através do
                            aplicativo móvel ou usando leitores de código de barras compatíveis.</p>
                    </div>
                </div>

                <!-- Suporte -->
                <div class="faq-item" data-category="support">
                    <div class="faq-question">
                        Como posso entrar em contato com o suporte?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Oferecemos diversas opções de suporte para nossos clientes:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li><strong>Chat ao vivo:</strong> Disponível de segunda a sexta, das 8h às 20h</li>
                            <li><strong>E-mail:</strong> suporte@armazemconectado.com.br (prazo de resposta de até 24h)
                            </li>
                            <li><strong>Telefone:</strong> (51) 9999-9999 (em horário comercial)</li>
                            <li><strong>Base de conhecimento:</strong> Artigos detalhados e tutoriais em vídeo
                                disponíveis 24/7</li>
                        </ul>
                        <p style="margin-top: 10px;">Clientes do plano Empresarial contam com um gerente de conta
                            dedicado.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="support">
                    <div class="faq-question">
                        Vocês oferecem treinamento para novos usuários?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sim, todos os planos incluem acesso ao nosso programa de onboarding que consiste em:</p>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>Webinar de boas-vindas e introdução ao sistema</li>
                            <li>Biblioteca completa de vídeos tutoriais</li>
                            <li>Documentação detalhada de todas as funcionalidades</li>
                        </ul>
                        <p style="margin-top: 10px;">Nos planos Profissional e Empresarial, oferecemos sessões de
                            treinamento personalizadas via videoconferência. O plano Empresarial também inclui a opção
                            de treinamento presencial (sujeito a disponibilidade na sua região).</p>
                    </div>
                </div>

                <div class="faq-item" data-category="support">
                    <div class="faq-question">
                        Com que frequência o sistema é atualizado?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>O Armazém Conectado está em constante evolução. Lançamos atualizações menores (correções de
                            bugs e melhorias de desempenho) a cada 2-3 semanas, e atualizações maiores (com novas
                            funcionalidades) a cada trimestre.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>