var namespaceSvg = "http://www.w3.org/2000/svg";
var namespaceXlink = "http://www.w3.org/1999/xlink";

TweenMax.set("svg", { visibility: "visible" });
TweenMax.set("#wholeCar", { y: 500, opacity: 1 });
TweenMax.set("#loader", { opacity: 1 });
TweenMax.set("#navbar", { opacity: 0 });

TweenMax.to("#wholeCar", 2, {
  y: 0,
  ease: Power2.easeInOut
});

TweenMax.to("#loader", 0.5, {
  delay: 2,
  opacity: 0,
  onComplete: function () {
    document.getElementById("loader").style.display = "none";
    document.body.style.backgroundColor = "#ffffff";
    TweenMax.to("#navbar", 1, { opacity: 1 });
  }
});

TweenMax.set(["#headlightL", "#headlightR"], { opacity: 1 });
TweenMax.set("#bonnetStart", { y: 75 });
TweenMax.set("#bumper", { drawSVG: "50% 50%", alpha: 0 });
TweenMax.set("#shadow", { alpha: 0.2 });
TweenMax.set("#wholeCar", { transformOrigin: "50% 50%", y: -30 });
TweenMax.set("#frame", { transformOrigin: "50% 50%", scaleX: 0 });
TweenMax.set(["#headlightL", "#headlightR"], { svgOrigin: "410 320", scale: 0 });
TweenMax.set(["#tyreL", "#tyreR"], { y: -30, transformOrigin: "50% 50%" });
TweenMax.set("#mirrorL", {
  transformOrigin: "100% 100%",
  scale: 0,
  rotation: 90,
  y: 20,
  x: 10
});
TweenMax.set("#mirrorR", {
  transformOrigin: "0% 100%",
  scale: 0,
  rotation: -90,
  y: 20,
  x: -10
});

var timelineAnimation = new TimelineMax({ repeat: -1, repeatDelay: 0 });
timelineAnimation
  .set("#bumper", { alpha: 1 })
  .to("#bumper", 1, { drawSVG: "0% 100%", ease: Power1.easeInOut })
  .from(
    "#shadow",
    1,
    {
      attr: { rx: 0, ry: "-=6" },
      ease: Power1.easeInOut
    },
    "-=1"
  )
  .to(
    "#bonnetStart",
    0.9,
    { y: 0, ease: Power2.easeInOut },
    "-=0.9"
  )
  .to(
    "#frame",
    1,
    { scaleX: 1, ease: Power2.easeInOut },
    "-=1"
  )
  .to(
    ["#headlightL", "#headlightR"],
    1,
    { scale: 1, ease: Power2.easeInOut },
    "-=1"
  )
  .to(
    "#wholeCar",
    1.2,
    { y: 0, ease: Power2.easeInOut },
    "-=1"
  )
  .to(
    ["#tyreL", "#tyreR"],
    0.5,
    { y: 0, ease: Power2.easeInOut },
    "-=1"
  )
  .to(
    ["#tyreL", "#tyreR"],
    0.2,
    { scaleX: 1.1, ease: Power2.easeOut },
    "-=0.5"
  )
  .to(
    "#chassis",
    0.2,
    { y: 5, transformOrigin: "50% 50%", ease: Power1.easeIn },
    "-=0.5"
  )
  .to("#chassis", 0.4, { y: 0 }, "-=0.2")
  .to(
    ["#tyreL", "#tyreR"],
    2,
    { scaleX: 1, ease: Elastic.easeOut.config(1, 0.8) },
    "-=0.4"
  )
  .to(
    ["#mirrorL", "#mirrorR"],
    1,
    { scale: 1, y: 0, x: 0 },
    "-=2.5"
  )
  .to(
    ["#mirrorL", "#mirrorR"],
    4,
    { rotation: 0, ease: Elastic.easeOut.config(1, 0.2) },
    "-=1.8"
  )
  .set("#shadow", { alpha: 0 })
  .set("#shadowFollow", { alpha: 0.2 })
  .to("#wholeCar", 2, { scale: 1.82, y: 600, ease: Power1.easeIn });
timelineAnimation.timeScale(2);


document.addEventListener("DOMContentLoaded", function () {
  var navItemsNodeList = document.querySelectorAll(".nav-links a");
  const menuToggleElement = document.getElementById("menu-toggle");
  const navLinksElement = document.getElementById("nav-links");

  if (menuToggleElement && navLinksElement) {
    menuToggleElement.addEventListener("click", function () {
      this.classList.toggle("active");
      navLinksElement.classList.toggle("active");
    });
  }

  navItemsNodeList.forEach(function (navItem) {
    navItem.addEventListener("click", function () {
      menuToggleElement.classList.remove("active");
      navLinksElement.classList.remove("active");
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var cardElementsNodeList = document.querySelectorAll(".card");

  cardElementsNodeList.forEach(function (singleCardElement) {
    var carouselInnerElement = singleCardElement.querySelector(".carousel-inner");
    if (!carouselInnerElement) return;

    var imageElementsNodeList = carouselInnerElement.querySelectorAll("img");
    var currentImageIndex = 0;
    var prevBtn = singleCardElement.querySelector(".prev-btn");
    var nextBtn = singleCardElement.querySelector(".next-btn");

    function updateCarousel() {
      carouselInnerElement.style.transform = "translateX(" + (-currentImageIndex * 100) + "%)";
    }

    if (prevBtn) {
      prevBtn.addEventListener("click", function () {
        currentImageIndex = (currentImageIndex - 1 + imageElementsNodeList.length) % imageElementsNodeList.length;
        updateCarousel();
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener("click", function () {
        currentImageIndex = (currentImageIndex + 1) % imageElementsNodeList.length;
        updateCarousel();
      });
    }
  });
});


var sliderEstablishmentElement = document.querySelector(".slider");
function activateEstablishmentSlider(event) {
  var items = document.querySelectorAll(".item");
  if (!sliderEstablishmentElement) return;
  if (event.target.matches(".next")) {
    sliderEstablishmentElement.append(items[0]);
  }
  if (event.target.matches(".prev")) {
    sliderEstablishmentElement.prepend(items[items.length - 1]);
  }
}
document.addEventListener("click", activateEstablishmentSlider, false);


var currentSlideIndex = 0;
var simpleSliderElement, simpleSlideElements, simpleDotElements;
var simplePrevBtn, simpleNextBtn, simpleInterval;

function updateSimpleSlider() {
  if (!simpleSliderElement) return;
  simpleSliderElement.style.transform = "translateX(-" + currentSlideIndex * 100 + "%)";
  simpleSlideElements.forEach((slide, i) =>
    slide.classList.toggle("active", i === currentSlideIndex)
  );
  simpleDotElements.forEach((dot, i) =>
    dot.classList.toggle("active", i === currentSlideIndex)
  );
}
function goToNextSlide() {
  currentSlideIndex = (currentSlideIndex + 1) % simpleSlideElements.length;
  updateSimpleSlider();
}
function goToPrevSlide() {
  currentSlideIndex = (currentSlideIndex - 1 + simpleSlideElements.length) % simpleSlideElements.length;
  updateSimpleSlider();
}
function initSimpleSlider() {
  simpleSliderElement = document.querySelector(".simple-slider");
  simpleSlideElements = Array.from(document.querySelectorAll(".simple-slide"));
  simpleDotElements = Array.from(document.querySelectorAll(".simple-dot"));
  simplePrevBtn = document.querySelector(".simple-prev");
  simpleNextBtn = document.querySelector(".simple-next");

  if (simplePrevBtn) simplePrevBtn.addEventListener("click", goToPrevSlide);
  if (simpleNextBtn) simpleNextBtn.addEventListener("click", goToNextSlide);

  simpleDotElements.forEach((dot, idx) =>
    dot.addEventListener("click", () => {
      currentSlideIndex = idx;
      updateSimpleSlider();
    })
  );

  simpleInterval = setInterval(goToNextSlide, 5000);
  updateSimpleSlider();
}
document.addEventListener("DOMContentLoaded", initSimpleSlider);


document.addEventListener("DOMContentLoaded", function () {
  var offerBtns = document.querySelectorAll(".oferta-button");
  var ofertaDetalhes = document.getElementById("oferta-detalhes");
  var navbar = document.getElementById("navbar");
  var allSections = document.querySelectorAll("section");
  var backBtn = document.getElementById("btn-voltar");
  var desktopSearch = document.querySelector(".desktop-search");
  var mobileSearch  = document.querySelector(".mobile-search-container");

  offerBtns.forEach(function (btn) {
    btn.addEventListener("click", function () {
      var card = btn.closest(".card");
      var nome = card.querySelector("h3").textContent.trim();
      var imgs = card.querySelectorAll(".carousel-inner img");
      var mainImg = document.getElementById("imagem-carro-principal");

      if (imgs.length) {
        mainImg.src = imgs[0].src;
        mainImg.alt = imgs[0].alt;
      }
      document.querySelector(".info-principal h2").textContent = nome;

      var gal = document.querySelector(".galeria-container");
      gal.innerHTML = "";
      imgs.forEach(function (im) {
        var thumb = document.createElement("img");
        thumb.src = im.src;
        thumb.alt = im.alt;
        thumb.classList.add("galeria-thumb");
        thumb.addEventListener("click", function () {
          mainImg.src = this.src;
        });
        gal.appendChild(thumb);
      });

      // preenche info
      var ano = card.querySelector(".ano").textContent.trim();
      var km  = card.querySelector(".km").textContent.trim();
      document.querySelector(".info-carro").textContent = ano + " | " + km;
      var desc = btn.dataset.descricao || "Sem descrição disponível.";
      document.querySelector(".descricao p").textContent = desc;

      // esconde tudo antes e a pesquisa
      allSections.forEach(s => (s.style.display = "none"));
      if (desktopSearch) desktopSearch.style.display = "none";
      if (mobileSearch) mobileSearch.style.display  = "none";

      // mostra navbar e detalhes
      navbar.style.display = "flex";
      ofertaDetalhes.style.display = "block";
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  });

  backBtn.addEventListener("click", function () {
    // reexibe tudo via reload
    window.location.reload();
  });
});


document.addEventListener("DOMContentLoaded", function () {
  var gal = document.querySelector(".galeria-container");
  var prev = document.querySelector(".galeria-prev");
  var next = document.querySelector(".galeria-next");
  if (prev && next && gal) {
    prev.addEventListener("click", () => (gal.scrollLeft -= 80));
    next.addEventListener("click", () => (gal.scrollLeft += 80));
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const filterBtns = document.querySelectorAll(".filtro-btn");
  filterBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      filterBtns.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      const val = btn.textContent.trim().toLowerCase();
      document.querySelectorAll(".card").forEach(card => {
        const st = card.dataset.status;
        card.style.display = (val === "todos" || st === val.slice(0,-1)) ? "" : "none";
      });
    });
  });
});


document.addEventListener("DOMContentLoaded", () => {
  const inputs  = document.querySelectorAll(".search-input");
  const buttons = document.querySelectorAll(".search-button");

  function aplicarFiltro(termo) {
    termo = termo.toLowerCase();
    document.querySelectorAll(".card").forEach(card => {
      const name = (card.dataset.name||"").toLowerCase();
      card.style.display = name.includes(termo) ? "" : "none";
    });
    document.querySelectorAll(".categoria").forEach(cat => {
      Array.from(cat.querySelectorAll(".aviso-vazio")).forEach(a => a.remove());
      if (!Array.from(cat.querySelectorAll(".card")).some(c => c.style.display !== "none")
          && !cat.querySelector(".mensagem-vazia")) {
        let p = document.createElement("p");
        p.className = "aviso-vazio";
        p.textContent = "Nenhum veículo encontrado nesta categoria.";
        p.style.fontStyle = "italic";
        cat.appendChild(p);
      }
    });
  }

  function carregarSug(termo, lista) {
    fetch(`carregar_veiculos.php?sugestao=${encodeURIComponent(termo)}`)
      .then(r=>r.text()).then(html=>{
        lista.innerHTML = html;
        lista.style.display = termo.length ? "block":"none";
      });
  }

  inputs.forEach(inp => {
    const lista = inp.closest(".search-container, .mobile-search").querySelector(".sugestoes-lista");
    if (!lista) return;
    inp.addEventListener("input", ()=> carregarSug(inp.value.trim(), lista));
    inp.addEventListener("keypress", e => {
      if (e.key==="Enter") {
        e.preventDefault();
        aplicarFiltro(inp.value.trim());
        lista.innerHTML = "";
      }
    });
    document.addEventListener("click", e => {
      const item = e.target.closest(".sugestao-item");
      if (item) {
        inp.value = item.dataset.busca;
        aplicarFiltro(inp.value);
        lista.innerHTML = "";
      } else if (!e.target.classList.contains("search-input")) {
        lista.innerHTML = "";
      }
    });
  });

  buttons.forEach(btn => {
    btn.addEventListener("click", e => {
      e.preventDefault();
      let inp = btn.closest(".search-container, .mobile-search").querySelector(".search-input");
      aplicarFiltro(inp.value.trim());
      const lista = btn.closest(".search-container, .mobile-search").querySelector(".sugestoes-lista");
      if (lista) lista.innerHTML = "";
    });
  });
});

document.addEventListener("DOMContentLoaded", function() {
  const btnFin = document.querySelector(".btn-financiamento");
  if (btnFin) {
    btnFin.addEventListener("click", e => {
      e.preventDefault();
      document.getElementById("oferta-detalhes").style.display = "none";
      document.getElementById("financiamento-form").style.display = "block";
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }
  const btnVol = document.querySelector(".btn-voltar-financiamento");
  if (btnVol) {
    btnVol.addEventListener("click", e => {
      e.preventDefault();
      document.getElementById("financiamento-form").style.display = "none";
      document.getElementById("oferta-detalhes").style.display = "block";
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  document.querySelector(".btn-proxima-etapa").addEventListener("click", function(e) {
    e.preventDefault();
    const nome    = document.getElementById("nome").value.trim();
    const celular = document.getElementById("celular").value.trim();
    const email   = document.getElementById("email").value.trim();
    const cpf     = document.getElementById("cpf").value.trim();
    const temTroca= document.getElementById("temTroca").checked?1:0;
    const erroN   = document.getElementById("erro-nome");
    if (!nome||!celular||!cpf) {
      erroN.style.display="block"; return;
    }
    erroN.style.display="none";
    let fd = new FormData();
    ["nome","celular","email","cpf"].forEach(k=>fd.append(k,document.getElementById(k).value.trim()));
    fd.append("temTroca", temTroca);
    fetch("enviar_financiamento.php", { method:"POST", body:fd })
      .then(r=>r.json())
      .then(data=>{
        if(data.sucesso) {
          document.querySelector(".form-container").innerHTML =
            `<div class="confirmacao-sucesso">
               <h2>🎉 Solicitação enviada!</h2>
               <p>Obrigado, <strong>${nome}</strong>. Em breve entraremos em contato.</p>
               <button onclick="window.location.reload()" class="btn-voltar">Voltar à página inicial</button>
             </div>`;
        } else alert("Erro ao enviar: "+(data.erro||"tente novamente."));
      })
      .catch(err=>{
        console.error(err);
        alert("Erro ao enviar o formulário.");
      });
  });
});

document.getElementById('btn-voltar').addEventListener('click', function() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
  window.location.reload();
});

document.getElementById('btn-360').addEventListener('click', function() {
  alert('Abrir visão 360° aqui!');
});