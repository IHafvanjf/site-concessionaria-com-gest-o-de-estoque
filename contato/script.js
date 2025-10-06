
var currentSlideIndex = 0;
var simpleSliderElement = null;
var simpleSlideElements = null;
var simpleDotElements = null;
var simplePreviousButtonElement = null;
var simpleNextButtonElement = null;
var simpleSliderIntervalVariable = null;

function updateSimpleSlider() {
  if (simpleSliderElement === null || simpleSlideElements === null || simpleDotElements === null) {
    return;
  }

  simpleSliderElement.style.transform = "translateX(-" + currentSlideIndex * 100 + "%)";

  for (var index = 0; index < simpleSlideElements.length; index++) {
    if (index === currentSlideIndex) {
      simpleSlideElements[index].classList.add("active");
    } else {
      simpleSlideElements[index].classList.remove("active");
    }
  }

  for (var indexDot = 0; indexDot < simpleDotElements.length; indexDot++) {
    if (indexDot === currentSlideIndex) {
      simpleDotElements[indexDot].classList.add("active");
    } else {
      simpleDotElements[indexDot].classList.remove("active");
    }
  }
}

function goToNextSlide() {
  if (simpleSlideElements === null) {
    return;
  }
  currentSlideIndex = (currentSlideIndex + 1) % simpleSlideElements.length;
  updateSimpleSlider();
}

function goToPreviousSlide() {
  if (simpleSlideElements === null) {
    return;
  }
  currentSlideIndex = (currentSlideIndex - 1 + simpleSlideElements.length) % simpleSlideElements.length;
  updateSimpleSlider();
}

function initSimpleSlider() {
  simpleSliderElement = document.querySelector(".simple-slider");
  simpleSlideElements = document.querySelectorAll(".simple-slide");
  simpleDotElements = document.querySelectorAll(".simple-dot");
  simplePreviousButtonElement = document.querySelector(".simple-prev");
  simpleNextButtonElement = document.querySelector(".simple-next");

  if (simplePreviousButtonElement !== null) {
    simplePreviousButtonElement.addEventListener("click", goToPreviousSlide);
  }

  if (simpleNextButtonElement !== null) {
    simpleNextButtonElement.addEventListener("click", goToNextSlide);
  }

  for (var dotIndex = 0; dotIndex < simpleDotElements.length; dotIndex++) {
    (function (indexValue) {
      simpleDotElements[indexValue].addEventListener("click", function () {
        currentSlideIndex = indexValue;
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
  initSimpleSlider();
  initMobileMenu();
});


// estabelecimento
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
  