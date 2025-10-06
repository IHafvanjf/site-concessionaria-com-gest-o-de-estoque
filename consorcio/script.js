
var currentSlideIndex = 0;
var simpleSliderElement = null;
var simpleSlideElements = null;
var simpleDotElements = null;
var simplePreviousButtonElement = null;
var simpleNextButtonElement = null;
var simpleSliderIntervalVariable = null;

function updateSimpleSlider() {
  if (!simpleSliderElement || !simpleSlideElements || !simpleDotElements) {
    return;
  }

  simpleSliderElement.style.transform = "translateX(-" + currentSlideIndex * 100 + "%)";

  for (var i = 0; i < simpleSlideElements.length; i++) {
    if (i === currentSlideIndex) {
      simpleSlideElements[i].classList.add("active");
    } else {
      simpleSlideElements[i].classList.remove("active");
    }
  }
  for (var d = 0; d < simpleDotElements.length; d++) {
    if (d === currentSlideIndex) {
      simpleDotElements[d].classList.add("active");
    } else {
      simpleDotElements[d].classList.remove("active");
    }
  }
}

function goToNextSlide() {
  if (!simpleSlideElements) return;
  currentSlideIndex = (currentSlideIndex + 1) % simpleSlideElements.length;
  updateSimpleSlider();
}

function goToPreviousSlide() {
  if (!simpleSlideElements) return;
  currentSlideIndex = (currentSlideIndex - 1 + simpleSlideElements.length) % simpleSlideElements.length;
  updateSimpleSlider();
}

function initSimpleSlider() {
  simpleSliderElement = document.querySelector(".simple-slider");
  simpleSlideElements = document.querySelectorAll(".simple-slide");
  simpleDotElements = document.querySelectorAll(".simple-dot");
  simplePreviousButtonElement = document.querySelector(".simple-prev");
  simpleNextButtonElement = document.querySelector(".simple-next");

  if (simplePreviousButtonElement)
    simplePreviousButtonElement.addEventListener("click", goToPreviousSlide);
  if (simpleNextButtonElement)
    simpleNextButtonElement.addEventListener("click", goToNextSlide);

  for (let dotIndex = 0; dotIndex < simpleDotElements.length; dotIndex++) {
    (function (idx) {
      simpleDotElements[idx].addEventListener("click", function () {
        currentSlideIndex = idx;
        updateSimpleSlider();
      });
    })(dotIndex);
  }

  simpleSliderIntervalVariable = setInterval(goToNextSlide, 5000);
  updateSimpleSlider();
}


function initMobileMenu() {
  var menuToggleElement = document.getElementById("menu-toggle");
  var navLinksElement = document.getElementById("nav-links");
  var navItemsNodeList = document.querySelectorAll(".nav-links a");

  menuToggleElement.addEventListener("click", function () {
    this.classList.toggle("active");
    navLinksElement.classList.toggle("active");
    document.body.classList.toggle("menu-open");
  });

  navItemsNodeList.forEach(function (navItem) {
    navItem.addEventListener("click", function () {
      menuToggleElement.classList.remove("active");
      navLinksElement.classList.remove("active");
      document.body.classList.remove("menu-open");
    });
  });
}


document.addEventListener("DOMContentLoaded", function () {
  initSimpleSlider();  // Se quiser usar o slider
  initMobileMenu();
});

function alternarCabecalho() {
  let cabecalho = document.querySelector("#cabecalho");
  cabecalho.classList.toggle("ativo1");
}
const slides = document.querySelectorAll(".slide");
for (const slide of slides) {
  slide.addEventListener("click", () => {
    removerClasseAtivo();
    slide.classList.add("ativo");
  });
}
function removerClasseAtivo() {
  slides.forEach((slide) => {
    slide.classList.remove("ativo");
  });
}

const rangeCredito = document.getElementById("rangeCredito");
const valorCreditoTexto = document.getElementById("valorCreditoTexto");
const planButtons = document.querySelectorAll(".plan-btn");
const btnSimular = document.getElementById("btnSimular");

if (rangeCredito) {
  valorCreditoTexto.textContent = Number(rangeCredito.value).toLocaleString("pt-BR");

  rangeCredito.addEventListener("input", function () {
    valorCreditoTexto.textContent = Number(this.value).toLocaleString("pt-BR");
  });
}

if (planButtons) {
  planButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      planButtons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
    });
  });
}

if (btnSimular) {
  btnSimular.addEventListener("click", function () {
    alert(
      "Simulação iniciada!\n\n" +
        "Plano: " +
        document.querySelector(".plan-btn.active").dataset.plan +
        "\nValor do Crédito: R$ " +
        valorCreditoTexto.textContent
    );
  });
}

const arrowLeft = document.getElementById("arrowLeft");
const arrowRight = document.getElementById("arrowRight");
const plansWrapper = document.querySelector(".plans-wrapper");
const planCards = document.querySelectorAll(".plan-card");

let currentPosition = 0;
const cardWidth = 260; 
const visibleCards = 3; 

if (arrowLeft && arrowRight && plansWrapper) {
  const maxScroll = (planCards.length * cardWidth) - (plansWrapper.clientWidth);
  
  arrowLeft.addEventListener("click", () => {
    currentPosition = Math.max(currentPosition - (visibleCards * cardWidth), 0);
    plansWrapper.style.scrollBehavior = "smooth";
    plansWrapper.scrollLeft = currentPosition;
    
    setTimeout(() => {
      plansWrapper.style.scrollBehavior = "auto";
    }, 500);
  });

  arrowRight.addEventListener("click", () => {
    currentPosition = Math.min(
      currentPosition + (visibleCards * cardWidth), 
      maxScroll
    );
    plansWrapper.style.scrollBehavior = "smooth";
    plansWrapper.scrollLeft = currentPosition;
    
    setTimeout(() => {
      plansWrapper.style.scrollBehavior = "auto";
    }, 500);
  });

  plansWrapper.addEventListener("scroll", () => {
    currentPosition = plansWrapper.scrollLeft;
  });
}

planCards.forEach(card => {
  card.addEventListener("mouseenter", () => {
    card.style.transform = "scale(1.05)";
    card.style.boxShadow = "0 0 30px rgba(0, 0, 0, 0.5)";
  });
  
  card.addEventListener("mouseleave", () => {
    card.style.transform = "scale(1)";
    card.style.boxShadow = "0 0 20px rgba(0, 0, 0, 0.3)";
  });
});

