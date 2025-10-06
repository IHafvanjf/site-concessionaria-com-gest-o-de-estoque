<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concessionária</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div id="loader">
      <svg version="1.1" xmlns="http://www.w3.org/2000/svg" 
           xmlns:xlink="http://www.w3.org/1999/xlink"
           viewBox="0 0 800 600">
        <defs>
          <clipPath id="bonnetMask">
            <rect x="290" y="282.333" fill="#691BE2" width="239.833" height="74.914" />
          </clipPath>
          <clipPath id="tyreMask">
            <rect x="290" y="383.333" fill="#691BE2" width="239.833" height="74.914" />
          </clipPath>
        </defs>
        <ellipse id="shadow" fill="#000" cx="410.779" cy="411.598" rx="142.983" ry="7.438" />
        <g id="wholeCar">
          <ellipse id="shadowFollow" fill="#000" cx="410.779" cy="411.598" rx="142.983" ry="7.438" opacity="0" />
          <g clip-path="url(#tyreMask)">
            <path id="tyreL" fill="#000000"
                  d="M345.763,410.936h-29.098c-2.2,0-4-1.8-4-4v-40.935c0-2.2,1.8-4,4-4h29.098
                     c2.2,0,4,1.8,4,4v40.935C349.763,409.136,347.963,410.936,345.763,410.936z" />
            <path id="tyreR" fill="#000000"
                  d="M502.303,410.936h-29.098c-2.2,0-4-1.8-4-4v-40.935c0-2.2,1.8-4,4-4h29.098
                     c2.2,0,4,1.8,4,4v40.935C506.303,409.136,504.503,410.936,502.303,410.936z" />
          </g>
          <g id="chassis">
            <line id="bumper" fill="none" stroke="#FFF" stroke-width="26" stroke-linecap="round"
                  stroke-miterlimit="10" x1="290" y1="370" x2="528" y2="370" />
            <g clip-path="url(#bonnetMask)">
              <path id="bonnetEnd" fill="#FFF"
                    d="M290,361.167v-47.833c0-17.05,13.95-31,31-31h177.833c17.05,0,31,13.95,31,31v47.833" />
              <path id="bonnetStart" fill="#FFF"
                    d="M378,361.167v-47.833c0-17.05,13.95-31,31-31h1.833c17.05,0,31,13.95,31,31v47.833" />
            </g>
            <polygon id="frame" fill="#1B4C3D" fill-opacity="0.2" stroke="#FFF" stroke-width="16"
                     stroke-miterlimit="10"
                     points="496.429,282.333 323.467,282.333 340.467,202.194 483.429,202.194 " />
            <circle id="headlightL" fill="#000000" cx="331.714" cy="326.858" r="17.5" />
            <circle id="headlightR" fill="#000000" cx="487.754" cy="326.858" r="17.5" />
            <rect id="mirrorR" x="514.21" y="262.76" width="28.59" height="20.16" rx="6" ry="6" fill="#fff" />
            <rect id="mirrorL" x="276.94" y="262.76" width="28.59" height="20.16" rx="6" ry="6" fill="#fff" />
          </g>
        </g>
      </svg>
    </div>

    <!-- Navbar -->
    <nav id="navbar">
      <div class="nav-container">
        <a href="#" class="logo">
          <img src="img/logo.png" alt="Logo Concessionária">
        </a>

        <div class="menu-toggle" id="menu-toggle">
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
        </div>

        <!-- Links de Navegação -->
        <ul class="nav-links" id="nav-links">
          <li><a href="#">Comprar</a></li>
          <li><a href="vender/index.html">Vender</a></li>
          <li><a href="consorcio/index.html">Consórcio</a></li>
          <li><a href="contato/index.html">Contato</a></li>
        </ul>

        <!-- Busca Desktop -->
        <div class="desktop-search">
          <div class="search-container">
            <input type="text" class="search-input" placeholder="Pesquisar carros ou motos">
            <button class="search-button">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="3" stroke-linecap="round"
                  stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
            </button>
            <ul class="sugestoes-lista" style="display: none;"></ul>
          </div>
        </div>
      </div>
  </nav>
      <!-- Campo de Busca para Mobile -->
      <div class="mobile-search-container">
        <div class="mobile-search">
          <input type="text" class="search-input" placeholder="Pesquisar carros ou motos">
          <button class="search-button">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" 
                stroke="currentColor" stroke-width="3" stroke-linecap="round" 
                stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle> 
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </button>
          <ul class="sugestoes-lista"></ul>
        </div>
      </div>



    <!-- Slider Simplificado -->
    <section class="simple-slider-section">
      <div class="simple-slider-container">
        <button class="simple-nav-btn simple-prev">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 18l-6-6 6-6"/>
          </svg>
        </button>
        <div class="simple-slider">
          <div class="simple-slide active">
            <img src="img/slide1.jpg" alt="Venda de Moto" class="simple-image">
            <div class="simple-overlay">
              <h3 class="simple-title">FECHAMENTO DE NEGÓCIO</h3>
              <p class="simple-subtitle">Condições excluisivas</p>
            </div>
          </div>
          <div class="simple-slide">
            <img src="img/slide2.jpg" alt="Venda de Carro" class="simple-image">
            <div class="simple-overlay">
              <h3 class="simple-title">VENDA DE CARRO</h3>
              <p class="simple-subtitle">Financiamento facilitado</p>
            </div>
          </div>
          <div class="simple-slide">
            <img src="img/slide3.jpg" alt="Fechamento de Negócio" class="simple-image">
            <div class="simple-overlay">
              <h3 class="simple-title">VENDA DE MOTO</h3>
              <p class="simple-subtitle">As melhores condições do mercado</p>
            </div>
          </div>
        </div>
        <button class="simple-nav-btn simple-next">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 18l6-6-6-6"/>
          </svg>
        </button>
        <div class="simple-dots">
          <span class="simple-dot active"></span>
          <span class="simple-dot"></span>
          <span class="simple-dot"></span>
        </div>
      </div>
    </section>

    <section class="filtro-section">
  <button class="filtro-btn active" data-filtro="todos">TODOS</button>
  <button class="filtro-btn" data-filtro="novos">NOVOS</button>
  <button class="filtro-btn" data-filtro="usados">USADOS</button>
</section>



  <?php include 'carregar_veiculos.php'; ?>

    <!-- Estabelecimento Slider -->
    <section class="design-slider-section">
      <div class="design-slider-container">
        <ul class="slider">
          <li class="item" style="background-image: url('img/slideEstabelecimento1.jpg')">
            <div class="content">
              <h2 class="title">Estabelecimento</h2>
              <p class="description">Conheça nosso showroom moderno e acolhedor, projetado para inspirar confiança e elegância. Cada detalhe foi pensado para oferecer conforto e segurança aos nossos clientes.</p>
              <button>Localização</button>
            </div>
          </li>
          <li class="item" style="background-image: url('img/slideEstabelecimento2.jpg')">
            <div class="content">
              <h2 class="title">Equipe</h2>
              <p class="description">Nossa equipe é formada por profissionais apaixonados e altamente capacitados. Atendimento personalizado e conhecimento técnico para ajudar você a encontrar o veículo ideal.</p>
              <button>Whatsapp</button>
            </div>
          </li>
          <li class="item" style="background-image: url('img/slideEstabelecimento3.jpg')">
            <div class="content">
              <h2 class="title">Motos</h2>
              <p class="description">Explore nossa linha de motos, pensada para unir potência, agilidade e estilo. Ideal para quem busca liberdade e aventura com segurança e performance.</p>
            </div>
          </li>
          <li class="item" style="background-image: url('img/slideEstabelecimento4.jpg')">
            <div class="content">
              <h2 class="title">Carros</h2>
              <p class="description">Descubra nossa seleção diversificada de carros que unem design inovador, desempenho e conforto. Opções para todos os estilos e orçamentos, com garantia de qualidade.</p>
            </div>
          </li>
          <li class="item" style="background-image: url('img/slideEstabelecimento5.jpg')">
            <div class="content">
              <h2 class="title">Atendimento</h2>
              <p class="description">Oferecemos um serviço ágil e cordial, sempre focado em superar suas expectativas. Seu bem-estar é nossa prioridade, com soluções sob medida para cada necessidade.</p>
            </div>
          </li>
        </ul>
        <nav class="design-nav">
          <ion-icon class="btn prev" name="arrow-back-outline"></ion-icon>
          <ion-icon class="btn next" name="arrow-forward-outline"></ion-icon>
        </nav>
      </div>
    </section>

<!-- FORMULÁRIO DE FINANCIAMENTO -->
<div id="financiamento-form" class="form-financiamento" style="display: none;">
  <div class="form-container">
    <button class="btn-voltar-financiamento">
      <i class="fa fa-arrow-left"></i> Voltar
    </button>

    <h1>Simule seu Financiamento</h1>
    <p>Preencha seus dados. É rápido e sem compromisso.</p>

    <div class="step step-1">
      <label for="nome">Nome completo</label>
      <input type="text" id="nome" placeholder="Digite seu nome completo" required>
      <span class="erro" id="erro-nome">Campo obrigatório</span>

      <label for="celular">Celular</label>
      <input type="tel" id="celular" placeholder="(00) 9 0000-0000" required>

      <label for="email">Email</label>
      <input type="email" id="email" placeholder="Digite seu email">

      <label for="cpf">CPF</label>
      <input type="text" id="cpf" placeholder="000.000.000-00" required>

      <div class="troca-container">
        <label>
          <input type="checkbox" id="temTroca"> Tenho um veículo para troca
        </label>
      </div>

      <button class="btn-proxima-etapa">Confirmar</button>
    </div>
  </div>
</div>

<!-- dentro de #oferta-detalhes, antes de popular via JS -->
<div id="oferta-detalhes"  style="display: none;">
  <div class="conteudo-oferta">
    <div class="col-esquerda">
      <div class="foto-principal">
        <img id="imagem-carro-principal" src="" alt="Imagem principal do veículo">
        <button id="btn-360" class="btn-360">360°</button>
      </div>
      <div class="galeria-container"></div>
    </div>
    <div class="info-principal">
      <h2>—</h2>
      <p class="info-carro">—</p>
      <div class="descricao"><p>—</p></div>
      <div class="acoes">
        <button class="btn-financiamento">Financiamento</button>
        <button class="btn-contato">Contato</button>
      </div>
    </div>
  </div>
</div>


<footer class="site-footer">
  <div class="site-footer__inner">

    <!-- Sobre -->
    <div class="site-footer__col">
      <a href="#" class="footer-logo">
        <img src="img/logoAlltech.png" alt="Logo AllTech">
      </a>
      <p class="footer-about">
        A AllTech é a revolução digital que sua empresa precisa! Desenvolvimento de sites e aplicações web sob medida, que transformam ideias em resultados.
      </p>
    </div>

    <!-- Serviços -->
    <div class="site-footer__col">
      <h4>Serviços</h4>
      <ul class="footer-service-list">
        <li><a href="#">Desenvolvimento de Sites</a></li>
        <li><a href="#">Aplicações Web</a></li>
        <li><a href="#">E-commerce</a></li>
        <li><a href="#">Otimização de SEO</a></li>
        <li><a href="#">Manutenção e Suporte</a></li>
        <li><a href="#">Integração de APIs</a></li>
        <li><a href="#">Sistemas Personalizados</a></li>
        <li><a href="#">UX/UI Design</a></li>
      </ul>
    </div>

    <!-- Fale Conosco -->
    <div class="site-footer__col">
      <h4>Fale Conosco</h4>
      <p class="footer-contact">
        Precisa de um orçamento ou tem alguma dúvida?<br>
        Fale conosco agora pelo
        <a href="https://wa.me/31997941735" target="_blank" class="whatsapp-link">
          WhatsApp!
        </a>
      </p>
    </div>

    <!-- Contato & Redes -->
    <div class="site-footer__col">
      <h4>Contato</h4>
      <p><i class="fa fa-map-marker"></i> Belo Horizonte – MG</p>
      <p><i class="fa fa-phone"></i> (31) 99794-1735</p>
      <p><i class="fa fa-envelope"></i> techinnova01@gmail.com</p>
      <div class="footer-social">
        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-instagram"></i></a>
        <a href="https://wa.me/31997941735" target="_blank"><i class="fa fa-whatsapp"></i></a>
      </div>
    </div>

  </div>

  <div class="site-footer__bottom">
    <p>© 2025 AllTech. Todos os direitos reservados.</p>
  </div>
</footer>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js"></script>

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="script.js"></script>

  </body>
</html>
