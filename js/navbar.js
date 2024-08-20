document.getElementById("navbar-toggle").addEventListener("click", function () {
  var navbarLinks = document.getElementById("navbar-links");
  var navbarElements = document.querySelectorAll(".navbar-element");

  if (navbarLinks.style.display === "flex") {
    navbarLinks.style.display = "none";
    navbarElements.forEach(function (element) {
      element.style.display = "none";
    });
    this.innerHTML = "&#9660;"; // Flecha hacia abajo
  } else {
    navbarLinks.style.display = "flex";
    navbarLinks.style.justifyContent = "space-around";
    navbarElements.forEach(function (element) {
      element.style.display = "flex";
    });
    this.innerHTML = "&#9650;"; // Flecha hacia arriba
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const scrollToTopBtn = document.getElementById("scroll-to-top");

  // Muestra u oculta el botón en función del scroll
  window.addEventListener("scroll", function () {
    if (window.scrollY > 100) {
      scrollToTopBtn.style.display = "block";
    } else {
      scrollToTopBtn.style.display = "flex";
    }
  });

  // Cuando se hace clic en el botón, desplaza hacia arriba
  scrollToTopBtn.addEventListener("click", function () {
    const scrollStep = -window.scrollY / (1000 / 30); // Velocidad de desplazamiento ajustable (1000 es la duración en // Función de animación de desplazamiento
    const scrollInterval = setInterval(function () {
      if (window.scrollY !== 0) {
        window.scrollBy(0, scrollStep);
      } else {
        clearInterval(scrollInterval); // Detiene la animación cuando llega al principio de la página
        scrollToTopBtn.style.display = "block"; // Asegura que el botón esté visible al final del desplazamiento
      }
    }, 15); // Intervalo de tiempo ajustable para suavizar el desplazamiento
  });
});
