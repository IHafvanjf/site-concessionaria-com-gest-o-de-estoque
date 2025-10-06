<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Página de Controle</title>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <main>
    <!-- Abas de navegação -->
    <div class="tabs">
      <div class="tab active">Financiamento</div>
      <div class="tab">Anúncios</div>
      <div class="tab">Consórcio</div>
      <span class="indicator"></span>
    </div>

    <div class="tab-content">

      <!-- FINANCIAMENTO -->
      <div id="financiamento-content" class="content-section">
        <div class="month-navigation">
          <button class="month-nav prev">&#10094;</button>
          <h2 class="month-label">Janeiro</h2>
          <button class="month-nav next">&#10095;</button>
        </div>
        <div class="days-selector">
          <button class="day-nav prev">&#10094;</button>
          <div class="days-wrapper"></div>
          <button class="day-nav next">&#10095;</button>
        </div>

        <div class="slots-header">
          <div class="slot-title">Email</div>
          <div class="slot-title">Veículo</div>
          <div class="slot-title">Detalhe</div>
        </div>
        <!-- linhas (slots-row) são inseridas via JS -->
      </div>

      <!-- ANÚNCIOS -->
      <div id="anuncios-content" class="content-section hidden">
        <div class="anuncios-card">
          <p class="anuncios-title">Qual o tipo do anúncio</p>
          <div class="anuncios-buttons">
            <button id="opt-empresa" class="btn-option active">Empresa</button>
            <button id="opt-cliente" class="btn-option">Cliente</button>
          </div>
        </div>

        <form id="form-empresa" class="anuncio-form" action="../painelControle/salvar_anuncio.php" method="POST" enctype="multipart/form-data">
          <div class="tipo-veiculo">
            <h3>Tipo de Veículo</h3>
            <div class="opcoes-tipo">
              <button type="button" id="btn-carro" class="botao-tipo ativo">Carro</button>
              <button type="button" id="btn-moto" class="botao-tipo">Moto</button>
            </div>
          </div>

          <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" id="marca" name="marca" placeholder="Ex: Volkswagen">
          </div>
          <div class="form-group">
            <label for="modelo">Modelo</label>
            <input type="text" id="modelo" name="modelo" placeholder="Ex: Gol">
          </div>
          <div class="form-group">
            <label for="ano">Ano</label>
            <input type="number" id="ano" name="ano" placeholder="Ex: 2020">
          </div>
          <div class="form-group">
            <label for="quilometragem">Quilometragem</label>
            <input type="text" id="quilometragem" name="quilometragem" placeholder="Ex: 45.000 km">
          </div>
          <div class="form-group" id="grupo-cambio">
            <label for="cambio">Câmbio</label>
            <select id="cambio" name="cambio">
              <option value="manual">Manual</option>
              <option value="automatico">Automático</option>
            </select>
          </div>
          <div class="form-group">
            <label for="combustivel">Tipo de combustível</label>
            <select id="combustivel" name="combustivel">
              <option value="gasolina">Gasolina</option>
              <option value="alcool">Álcool</option>
              <option value="flex">Flex</option>
              <option value="diesel">Diesel</option>
            </select>
          </div>
          <div class="form-group">
            <label for="historico">Histórico de manutenção/Descrição</label>
            <textarea id="historico" name="historico" rows="3" placeholder="Descreva, se houver"></textarea>
          </div>
          <div class="form-group">
            <label for="fotos">Fotos do veículo</label>
            <input type="file" id="fotos" name="fotos[]" multiple>
            <div id="preview-fotos" style="margin-top: 10px;"></div>
          </div>
          <div class="form-group">
            <label for="preco">Preço</label>
            <input type="text" id="preco" name="preco" placeholder="Ex: R$ 45.000,00">
          </div>
          <div class="form-group">
            <label for="panorama360">Imagem Panorâmica 360°</label>
            <input type="file" id="panorama360" name="panorama360" accept="image/*">
          </div>
          <input type="hidden" name="tipo" id="tipo-veiculo" value="carro">
          <button type="submit" class="btn-submit">Enviar Anúncio</button>
        </form>

        <form id="form-cliente" class="anuncio-form">
          <div class="slots-header"></div>
          <div id="anuncios-clientes"></div>
        </form>

        <!-- Modal para detalhes (anúncios de clientes) -->
        <div id="modal-detalhes" class="modal hidden">
          <div class="modal-content">
            <span class="close-modal" onclick="fecharModal()">&times;</span>
            <h2>Detalhes do Anúncio</h2>
            <div id="detalhes-carro"></div>
            <div class="modal-actions">
              <button class="aprovar-btn" onclick="aprovarAnuncio()">✅ Aprovar</button>
              <button class="reprovar-btn" onclick="reprovarAnuncio()">❌ Reprovar</button>
            </div>
          </div>
        </div>

      </div>

      <!-- CONSÓRCIO -->
      <div id="consorcio-content" class="content-section hidden">
        <div class="month-navigation">
          <button class="month-nav prev">&#10094;</button>
          <h2 class="month-label">Janeiro</h2>
          <button class="month-nav next">&#10095;</button>
        </div>
        <div class="days-selector">
          <button class="day-nav prev">&#10094;</button>
          <div class="days-wrapper"></div>
          <button class="day-nav next">&#10095;</button>
        </div>
        <div class="slots-header">
          <div class="slot-title">Nome</div>
          <div class="slot-title">Telefone</div>
          <div class="slot-title">Plano</div>
        </div>
        <div class="slots-row">
          <div class="slot-item">Evandro</div>
          <div class="slot-item">(99) 9999-9999</div>
          <div class="slot-item">Plano Ouro</div>
        </div>
        <div class="slots-row">
          <div class="slot-item">Marina</div>
          <div class="slot-item">(88) 8888-8888</div>
          <div class="slot-item">Plano Prata</div>
        </div>
        <div class="slots-row">
          <div class="slot-item">Carlos</div>
          <div class="slot-item">(77) 7777-7777</div>
          <div class="slot-item">Plano Bronze</div>
        </div>
      </div>

    </div>
  </main>

  <!-- Botão Estoque -->
  <button id="btn-estoque" class="btn-estoque">
    <i class="fa fa-boxes"></i>
  </button>

  <!-- Seção Estoque -->
  <div id="estoque-section" class="content-section hidden">
    <div class="estoque-header">
      <h2>Estoque</h2>
      <button id="add-veiculo" class="fab-adicionar" title="Adicionar veículo">
        <i class="fa fa-plus"></i>
      </button>
    </div>

    <!-- Botão Voltar -->
    <button id="btn-voltar" class="fab-voltar" title="Voltar à tela principal">
      <i class="fa fa-arrow-left"></i>
    </button>

    <!-- Categorias -->
    <div class="estoque-categories">
      <button id="tab-carros" class="estoque-tab ativo">Carros</button>
      <button id="tab-motos" class="estoque-tab">Motos</button>
    </div>

<div id="list-carros"></div>
<div id="list-motos" class="hidden"></div>

  </div>

  <!-- Modal de Edição -->
  <div id="edit-modal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="edit-title">
    <div class="modal-content">
      <button id="close-edit-modal" class="modal-close" aria-label="Fechar">&times;</button>
      <h2 id="edit-title">Editar Veículo</h2>
      <form id="edit-form" enctype="multipart/form-data" method="POST">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" id="edit-id" name="id">

        <div class="form-group">
          <label for="edit-img">Fotos do veículo:</label>
          <input type="file" id="edit-img" name="fotos[]" accept="image/*" multiple>
          <div id="preview-edit-img" class="preview-edit-img"></div>
        </div>

        <div class="form-group">
          <label for="edit-marca">Marca</label>
          <input type="text" id="edit-marca" name="marca" required>
        </div>

        <div class="form-group">
          <label for="edit-modelo">Modelo</label>
          <input type="text" id="edit-modelo" name="modelo" required>
        </div>

        <div class="form-group">
          <label for="edit-ano">Ano</label>
          <input type="number" id="edit-ano" name="ano" required>
        </div>

        <div class="form-group">
          <label for="edit-km">Quilometragem</label>
          <input type="text" id="edit-km" name="quilometragem" required>
        </div>

        <div class="form-group">
          <label for="edit-cambio">Câmbio</label>
          <select id="edit-cambio" name="cambio">
            <option value="manual">Manual</option>
            <option value="automatico">Automático</option>
          </select>
        </div>

        <div class="form-group">
          <label for="edit-combustivel">Combustível</label>
          <select id="edit-combustivel" name="combustivel">
            <option value="gasolina">Gasolina</option>
            <option value="alcool">Álcool</option>
            <option value="flex">Flex</option>
            <option value="diesel">Diesel</option>
          </select>
        </div>

        <div class="form-group">
          <label for="edit-historico">Histórico / Descrição</label>
          <textarea id="edit-historico" name="historico" rows="3"></textarea>
        </div>

        <div class="form-group">
          <label for="edit-preco">Preço</label>
          <input type="text" id="edit-preco" name="preco" required>
        </div>

        <div class="form-group">
          <label for="edit-panorama">Imagem Panorâmica 360°</label>
          <input type="file" id="edit-panorama" name="panorama360" accept="image/*">
        </div>

        <button type="submit" class="btn-submit">Salvar</button>
      </form>
    </div>
  </div>

  <!-- Modal de Detalhes do Financiamento -->
  <div id="detail-modal-user" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="detail-user-title">
    <div class="modal-content">
      <button id="close-detail-user" class="modal-close" aria-label="Fechar">&times;</button>
      <h2 id="detail-user-title">Detalhes do Usuário</h2>
      <div id="detail-user-body" class="modal-body"></div>
    </div>
  </div>

  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="script.js"></script>
</body>
</html>
