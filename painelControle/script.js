document.addEventListener("DOMContentLoaded", () => {
  "use strict";

  // =============== Utils =================
  const $   = (sel) => document.querySelector(sel);
  const $$  = (sel) => Array.from(document.querySelectorAll(sel));
  const $id = (id)  => document.getElementById(id);
  const on  = (el, evt, fn) => el && el.addEventListener(evt, fn);

  // =============== Swiper (se existir) =================
  if ($(".swiper")) {
    new Swiper(".swiper", {
      grabCursor: true,
      speed: 500,
      effect: "slide",
      loop: true,
      autoplay: { delay: 3000, disableOnInteraction: false },
      mousewheel: { invert: false, sensitivity: 1, releaseOnEdges: true }
    });
  }

  // =============== Calendário (Financiamento/Consórcio) ===============
  const monthNames = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];

  function setupCalendar(container) {
    const monthLabel = container.querySelector(".month-label");
    const monthPrev  = container.querySelector(".month-nav.prev");
    const monthNext  = container.querySelector(".month-nav.next");
    const daysWrapper= container.querySelector(".days-wrapper");
    const dayPrev    = container.querySelector(".day-nav.prev");
    const dayNext    = container.querySelector(".day-nav.next");
    if (!monthLabel || !monthPrev || !monthNext || !daysWrapper || !dayPrev || !dayNext) return;

    let currentMonthIndex = new Date().getMonth();

    function updateCalendar() {
      const now  = new Date();
      const year = now.getFullYear();
      monthLabel.textContent = monthNames[currentMonthIndex];
      const dayCount = new Date(year, currentMonthIndex + 1, 0).getDate();
      daysWrapper.innerHTML = "";
      for (let d = 1; d <= dayCount; d++) {
        const dayEl = document.createElement("div");
        dayEl.className = "day-item";
        dayEl.textContent = d;
        if (currentMonthIndex === now.getMonth() && d === now.getDate()) dayEl.classList.add("today");
        dayEl.addEventListener("click", () => {
          container.querySelector(".day-item.active")?.classList.remove("active");
          dayEl.classList.add("active");
        });
        daysWrapper.appendChild(dayEl);
      }
    }

    on(monthPrev, "click", () => { currentMonthIndex = (currentMonthIndex + 11) % 12; updateCalendar(); });
    on(monthNext, "click", () => { currentMonthIndex = (currentMonthIndex + 1)  % 12; updateCalendar(); });
    on(dayPrev,   "click", () => daysWrapper.scrollBy({ left: -daysWrapper.clientWidth, behavior: "smooth" }));
    on(dayNext,   "click", () => daysWrapper.scrollBy({ left:  daysWrapper.clientWidth, behavior: "smooth" }));
    updateCalendar();
  }

  $$(".content-section").forEach(section => {
    if (section.querySelector(".month-navigation")) setupCalendar(section);
  });

  // =============== Tabs =================
  (function setupTabs(){
    const tabs = $$(".tabs .tab");
    const indicator = $(".tabs .indicator");
    const sections = $$(".content-section");
    if (!tabs.length || !indicator || !sections.length) return;

    function moveIndicator(el) {
      indicator.style.width = el.offsetWidth + "px";
      indicator.style.left  = el.offsetLeft + "px";
    }
    tabs.forEach((tab, index) => {
      tab.addEventListener("click", () => {
        $(".tab.active")?.classList.remove("active");
        tab.classList.add("active");
        moveIndicator(tab);
        sections.forEach((sec, i) => sec.classList.toggle("hidden", i !== index));
      });
    });
    moveIndicator($(".tab.active") || tabs[0]);
  })();

  // =============== Anúncios (Empresa/Cliente) ===============
  (function setupAnunciosToggle(){
    const btnEmpresa = $id("opt-empresa");
    const btnCliente = $id("opt-cliente");
    const formEmpresa= $id("form-empresa");
    const formCliente= $id("form-cliente");
    if (!btnEmpresa || !btnCliente || !formEmpresa || !formCliente) return;

    function toggleForms(showEmpresa) {
      btnEmpresa.classList.toggle("active", showEmpresa);
      btnCliente.classList.toggle("active", !showEmpresa);
      formEmpresa.classList.toggle("hidden", !showEmpresa);
      formCliente.classList.toggle("hidden", showEmpresa);
    }
    on(btnEmpresa, "click", () => toggleForms(true));
    on(btnCliente, "click", () => toggleForms(false));
    toggleForms(true);
  })();

  // Tipo do veículo no formulário
  (function setupTipoVeiculo(){
    const btnCarroForm = $id("btn-carro");
    const btnMotoForm  = $id("btn-moto");
    const grupoCambio  = $id("grupo-cambio");
    const inputTipo    = $id("tipo-veiculo");
    on(btnCarroForm, "click", () => {
      btnCarroForm.classList.add("ativo");
      btnMotoForm?.classList.remove("ativo");
      if (grupoCambio) grupoCambio.style.display = "block";
      if (inputTipo) inputTipo.value = "carro";
    });
    on(btnMotoForm, "click", () => {
      btnMotoForm.classList.add("ativo");
      btnCarroForm?.classList.remove("ativo");
      if (grupoCambio) grupoCambio.style.display = "none";
      if (inputTipo) inputTipo.value = "moto";
    });
  })();

  // Preview de imagens do formulário
  (function setupPreviewForm(){
    const inputFotos = $id("fotos");
    on(inputFotos, "change", function() {
      const preview = $id("preview-fotos");
      if (!preview) return;
      preview.innerHTML = "";
      [...this.files].forEach(file => {
        const r = new FileReader();
        r.onload = e => {
          const img = document.createElement("img");
          img.src = e.target.result;
          img.style.width = "100px";
          img.style.marginRight = "10px";
          preview.appendChild(img);
        };
        r.readAsDataURL(file);
      });
    });
  })();

const tabCarros   = $id("tab-carros");
const tabMotos    = $id("tab-motos");
const listCarros  = $id("list-carros");
const listMotos   = $id("list-motos");
const btnEstoque  = $id("btn-estoque");
const mainSection = document.querySelector("main");
const estoqueSec  = $id("estoque-section");

let estoqueInFlight = false;      // evita “piscar”
let estoqueTipoOnScreen = null;   // lembra o que está visível

function aplicarHtmlEstoque(tipo, html) {
  if (tipo === "carro") {
    listCarros.innerHTML = html;
    listCarros.classList.remove("hidden");
    listMotos.classList.add("hidden");
    tabCarros.classList.add("ativo");
    tabMotos.classList.remove("ativo");
  } else {
    listMotos.innerHTML = html;
    listMotos.classList.remove("hidden");
    listCarros.classList.add("hidden");
    tabMotos.classList.add("ativo");
    tabCarros.classList.remove("ativo");
  }
  estoqueTipoOnScreen = tipo;
  if (typeof atualizarEventosEstoque === "function") atualizarEventosEstoque();
}

function carregarEstoque(tipo) {
  if (!listCarros || !listMotos) return;
  if (estoqueInFlight) return;
  estoqueInFlight = true;

  fetch(`carregar_estoque.php?tipo=${encodeURIComponent(tipo)}&t=${Date.now()}`)
    .then(r => r.text())
    .then(html => aplicarHtmlEstoque(tipo, html))
    .catch(err => console.error("Erro ao carregar estoque:", err))
    .finally(() => setTimeout(() => { estoqueInFlight = false; }, 80));
}

// abrir estoque (carrega carros)
on(btnEstoque, "click", () => {
  if (mainSection) mainSection.style.display = "none";
  estoqueSec.classList.remove("hidden");
  if (estoqueTipoOnScreen !== "carro") carregarEstoque("carro");
});

// trocar abas
on(tabCarros, "click", () => carregarEstoque("carro"));
on(tabMotos,  "click", () => carregarEstoque("moto"));

// voltar e adicionar
on($id("btn-voltar"), "click", () => {
  estoqueSec.classList.add("hidden");
  if (mainSection) mainSection.style.display = "block";
});
on($id("add-veiculo"), "click", () => {
  estoqueSec.classList.add("hidden");
  if (mainSection) mainSection.style.display = "block";
  document.querySelectorAll(".tab")[1]?.click();
  $id("opt-empresa")?.click();
});



  // Eventos que dependem de itens carregados via AJAX
  function atualizarEventosEstoque() {
    // Excluir
    $$(".action-delete").forEach(btn => {
      on(btn, "click", () => {
        const id = btn.getAttribute("data-id");
        if (!id) return;
        if (!confirm("Tem certeza que deseja excluir este veículo?")) return;
        fetch("carregar_estoque.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `acao=excluir&id=${encodeURIComponent(id)}`
        })
          .then(r => r.json())
          .then(d => {
            if (d.status === "ok") {
              const tipoAtual = estoqueTipoOnScreen || "carro";
              carregarEstoque(tipoAtual);
            } else { alert("Erro ao excluir"); }
          });
      });
    });

    // Editar (abrir modal e preencher)
    const editModal = $id("edit-modal");
    const previewBox= $id("preview-edit-img");
    $$(".action-edit").forEach(btn => {
      on(btn, "click", () => {
        $id("edit-form")?.setAttribute("data-id", btn.dataset.id || "");
        $id("edit-id").value        = btn.dataset.id || "";
        $id("edit-marca").value     = btn.dataset.marca || "";
        $id("edit-modelo").value    = btn.dataset.modelo || "";
        $id("edit-ano").value       = btn.dataset.ano || "";
        $id("edit-km").value        = btn.dataset.km || "";
        $id("edit-cambio").value    = btn.dataset.cambio || "manual";
        $id("edit-combustivel").value = btn.dataset.combustivel || "gasolina";
        $id("edit-historico").value = btn.dataset.historico || "";
        $id("edit-preco").value     = btn.dataset.preco || "";
        if (previewBox) previewBox.innerHTML = ""; // limpa thumbs
        editModal?.classList.remove("hidden");
      });
    });
  }

  // Modal de edição (fechar + preview imagens)
  (function setupEditModal(){
    const editModal = $id("edit-modal");
    const closeEdit = $id("close-edit-modal");
    on(closeEdit, "click", () => editModal?.classList.add("hidden"));
    on(editModal, "click", e => { if (e.target === editModal) editModal.classList.add("hidden"); });

    on($id("edit-img"), "change", function() {
      const preview = $id("preview-edit-img");
      if (!preview) return;
      preview.innerHTML = "";
      [...this.files].forEach(file => {
        const r = new FileReader();
        r.onload = e => {
          const img = document.createElement("img");
          img.src = e.target.result;
          img.style.width = "90px";
          img.style.marginRight = "8px";
          preview.appendChild(img);
        };
        r.readAsDataURL(file);
      });
    });

    // Submit edição
    on($id("edit-form"), "submit", function(e) {
      e.preventDefault();
      const id = this.getAttribute("data-id");
      if (!id) { alert("ID inválido"); return; }
      const fd = new FormData(this);
      fd.set("acao","editar");
      fd.set("id", id);
      const pan = $id("edit-panorama");
      if (pan && pan.files[0]) fd.append("imagem360", pan.files[0]);

      fetch("carregar_estoque.php", { method:"POST", body: fd })
        .then(r => r.json())
        .then(d => {
          if (d.status === "ok") {
            editModal?.classList.add("hidden");
            const tipoAtual = estoqueTipoOnScreen || "carro";
            carregarEstoque(tipoAtual);
          } else { alert("Erro ao editar"); }
        });
    });
  })();

  // =============== Anúncios de Clientes (lista) ===============
  fetch("listar_anuncios_clientes.php")
    .then(res => res.text())
    .then(html => { const c = $id("anuncios-clientes"); if (c) c.innerHTML = html; });

  // =============== Modais de Detalhe (clientes e financiamentos) ===============
  let anuncioAtual = null;

  function mostrarDetalhesCliente(btn) {
    anuncioAtual = {
      id:         btn.dataset.id,
      nome_cliente: btn.dataset.nome,
      telefone_cliente: btn.dataset.telefone,
      email_cliente: btn.dataset.email,
      marca:      btn.dataset.marca,
      modelo:     btn.dataset.modelo,
      ano:        btn.dataset.ano,
      cambio:     btn.dataset.cambio,
      quilometragem: btn.dataset.km,
      valor_pedido: btn.dataset.valor,
      renavam:    btn.dataset.renavam,
      historico:  btn.dataset.historico,
      documentos: btn.dataset.documentos
    };
    const div = $id("detalhes-carro");
    if (div) {
      div.innerHTML = `
        <p><strong>Cliente:</strong> ${anuncioAtual.nome_cliente}</p>
        <p><strong>Telefone:</strong> ${anuncioAtual.telefone_cliente}</p>
        <p><strong>Email:</strong> ${anuncioAtual.email_cliente || 'N/A'}</p>
        <p><strong>Marca:</strong> ${anuncioAtual.marca}</p>
        <p><strong>Modelo:</strong> ${anuncioAtual.modelo}</p>
        <p><strong>Ano:</strong> ${anuncioAtual.ano}</p>
        <p><strong>Câmbio:</strong> ${anuncioAtual.cambio}</p>
        <p><strong>Quilometragem:</strong> ${anuncioAtual.quilometragem}</p>
        <p><strong>Valor Pedido:</strong> R$ ${anuncioAtual.valor_pedido}</p>
        <p><strong>RENAVAM:</strong> ${anuncioAtual.renavam}</p>
        <p><strong>Histórico:</strong> ${anuncioAtual.historico || 'N/A'}</p>
        <p><strong>Documento:</strong> <a href="../uploads/documentos_propostas/${anuncioAtual.documentos}" target="_blank">${anuncioAtual.documentos}</a></p>
      `;
    }
    const modal = $id("modal-detalhes");
    if (modal) { modal.classList.remove("hidden"); modal.style.display = "flex"; }
  }

  function mostrarDetalhesFinanciamento(btn) {
    const body = $id("detail-user-body");
    if (!body) return;
    const dados = {
      nome: btn.dataset.nome || "—",
      cpf: btn.dataset.cpf || "—",
      email: btn.dataset.email || "—",
      telefone: btn.dataset.telefone || "—",
      troca: btn.dataset.troca || "—",
      carro: btn.dataset.carro || "—",
      data: btn.dataset.data || "—"
    };
    body.innerHTML = `
      <div class="detail-field"><strong>Email:</strong> ${dados.email}</div>
      <div class="detail-field"><strong>CPF:</strong> ${dados.cpf}</div>
      <div class="detail-field"><strong>Nome:</strong> ${dados.nome}</div>
      <div class="detail-field"><strong>Telefone:</strong> ${dados.telefone}</div>
      <div class="detail-field"><strong>Veículo na troca:</strong> ${dados.troca}</div>
      <div class="detail-field"><strong>Veículo selecionado:</strong> ${dados.carro}</div>
      <div class="detail-field"><strong>Enviado em:</strong> ${dados.data}</div>
    `;
    $id("detail-user-title").textContent = "Detalhes do Financiamento";
    $id("detail-modal-user")?.classList.remove("hidden");
  }

  // Delegação de cliques para ambos os contextos
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".detail-dots");
    if (!btn) return;
    if (btn.closest("#form-cliente")) {        // lista de anúncios de clientes
      mostrarDetalhesCliente(btn);
      return;
    }
    if (btn.closest("#financiamento-content")) { // listagem de financiamentos
      mostrarDetalhesFinanciamento(btn);
    }
  });

  // Fechar modal de financiamento
  on($id("close-detail-user"), "click", () => {
    $id("detail-modal-user")?.classList.add("hidden");
    const cont = $id("detail-user-body"); if (cont) cont.innerHTML = "";
  });

  // =============== Carregar financiamentos (uma vez) ===============
  function carregarFinanciamentos() {
    fetch("listar_financiamentos.php")
      .then(res => res.json())
      .then(dados => {
        const content = $id("financiamento-content");
        if (!content) return;
        $$("#financiamento-content .slots-row").forEach(el => el.remove());
        dados.forEach(f => {
          const row = document.createElement("div");
          row.className = "slots-row";
          row.innerHTML = `
            <div class="slot-item">${f.email || 'Não informado'}</div>
            <div class="slot-item">${f.carro || 'Sem veículo vinculado'}</div>
            <div class="slot-item">
              <button 
                type="button" 
                class="detail-dots" 
                aria-label="Detalhes do financiamento"
                data-nome="${f.nome || ''}"
                data-cpf="${f.cpf || ''}"
                data-email="${f.email || ''}"
                data-telefone="${f.celular || ''}"
                data-troca="${f.troca === '1' ? 'Sim' : 'Não'}"
                data-carro="${f.carro || 'Desconhecido'}"
                data-data="${f.enviado_em || ''}"
              >⋮⋮⋮</button>
            </div>
          `;
          content.appendChild(row);
        });
      })
      .catch(err => console.error("Erro ao carregar financiamentos:", err));
  }
  carregarFinanciamentos();
});

// ======= Funções globais usadas no HTML (modal de clientes) =======
let anuncioAtual = null;
function fecharModal() {
  const modal = document.getElementById("modal-detalhes");
  if (modal) { modal.classList.add("hidden"); modal.style.display = "none"; }
  anuncioAtual = null;
}
function aprovarAnuncio() {
  if (!anuncioAtual) return;
  fetch('atualizar_status_anuncio.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: anuncioAtual.id, status: 'Aprovado' })
  })
  .then(res => res.json())
  .then(data => {
    if (data.via === 'whatsapp') window.open(data.link, '_blank');
    location.reload();
  });
}
function reprovarAnuncio() {
  if (!anuncioAtual) return;
  fetch('atualizar_status_anuncio.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: anuncioAtual.id, status: 'Reprovado' })
  })
  .then(res => res.json())
  .then(data => {
    if (data.via === 'whatsapp') window.open(data.link, '_blank');
    location.reload();
  });
}
