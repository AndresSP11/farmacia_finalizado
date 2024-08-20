const salida = document.querySelector(".title-links");
const padreLinks = document.querySelector(".show-links");
const girador = document.querySelector(".bx-chevron-right");

salida.addEventListener("click", function () {
  // Verifica si ya está expandido
  const isExpanded =
    padreLinks.style.height !== "0px" && padreLinks.style.height !== "";

  if (isExpanded) {
    // Contraer
    padreLinks.style.height = "0";
    Array.from(padreLinks.children).forEach((child) => {
      child.style.opacity = "0";
    });
    girador.classList.add("contra");
    girador.classList.remove("girar");
  } else {
    // Expandir
    // Calcula la altura total que deberían ocupar los enlaces
    const totalHeight = Array.from(padreLinks.children).reduce(
      (acc, child) => acc + child.offsetHeight,
      0
    );
    padreLinks.style.height = `${totalHeight}px`;
    Array.from(padreLinks.children).forEach((child) => {
      child.style.opacity = "1";
    });
    girador.classList.add("girar");
    girador.classList.remove("contra");
  }
});
