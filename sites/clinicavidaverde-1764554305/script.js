// ===============================
//  ANIMAÇÃO DE ENTRADA SUAVE
// ===============================

// Observa elementos que devem animar ao aparecer na tela
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add("show");
    }
  });
});

// Aplica a observação nos elementos com a classe .animate
document.querySelectorAll(".animate").forEach(el => observer.observe(el));



// ===============================
//  HEADER FIXO ANIMADO
// ===============================

const header = document.querySelector(".topbar");

let lastScroll = 0;

window.addEventListener("scroll", () => {
  const currentScroll = window.scrollY;

  if (currentScroll > lastScroll && currentScroll > 80) {
    header.classList.add("hide");
  } else {
    header.classList.remove("hide");
  }

  lastScroll = currentScroll;
});



// ===============================
//  SUAVIZA A ROLAGEM DOS LINKS
// ===============================

document.querySelectorAll('a[href^="#"]').forEach(link => {
  link.addEventListener("click", function (e) {
    const target = document.querySelector(this.getAttribute("href"));
    if (!target) return;

    e.preventDefault();

    window.scrollTo({
      top: target.offsetTop - 60,
      behavior: "smooth"
    });
  });
});



// ===============================
//  ANIMAÇÃO DO MENU HAMBURGER
// ===============================

const toggle = document.querySelector("#menu-toggle");
const navList = document.querySelector(".nav__list");

toggle.addEventListener("change", () => {
  navList.classList.toggle("open", toggle.checked);
});



// ===============================
//  ANIMAÇÃO DO HERO (digitação)
// ===============================

function typeEffect(element, speed = 45) {
  const text = element.innerText;
  element.innerText = "";
  let i = 0;

  function type() {
    if (i < text.length) {
      element.innerText += text.charAt(i);
      i++;
      setTimeout(type, speed);
    }
  }

  type();
}

// Ativa o efeito só quando a página carregar
window.addEventListener("DOMContentLoaded", () => {
  const heroTitle = document.querySelector(".hero__text h1");
  if (heroTitle) {
    typeEffect(heroTitle, 35);
  }
});



// ===============================
//  BOTÃO FLOTANTE AO ROLAR
// ===============================

const backToTop = document.createElement("button");
backToTop.className = "back-to-top";
backToTop.innerHTML = "↑";
document.body.appendChild(backToTop);

backToTop.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

window.addEventListener("scroll", () => {
  backToTop.classList.toggle("visible", window.scrollY > 300);
});
