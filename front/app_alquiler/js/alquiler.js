// Utilidades compartidas por todas las secciones: mensajes, modal y pestañas
const msgContent = document.getElementById("msg-content");
const confirmarModal = document.getElementById("confirmar-modal");
let accionModal = null;
let msgTimer = null;

// Listas en memoria usadas por los selects de reservas e historial
let vehiculos = [];
let clientes = [];

const msg = (show, text, isError = false) => {
  if (show) {
    msgContent.getElementsByTagName("p")[0].textContent = text;
    msgContent.classList.toggle("error", isError);
    msgContent.classList.remove("hidden");
    clearTimeout(msgTimer);
    msgTimer = setTimeout(() => msg(false, ""), 2500);
  } else {
    msgContent.classList.add("hidden");
  }
};

const msgError = (text) => msg(true, text, true);

const confirmar = (texto, accion) => {
  document.getElementById("confirmar-modal-texto").textContent = texto;
  accionModal = accion;
  confirmarModal.classList.remove("close");
};

document.getElementById("cancelar-modal-btn").addEventListener("click", () => {
  confirmarModal.classList.add("close");
});

document.getElementById("aceptar-modal-btn").addEventListener("click", () => {
  confirmarModal.classList.add("close");
  if (accionModal) accionModal();
});

document.querySelectorAll(".tab").forEach((tab) => {
  tab.addEventListener("click", () => {
    document.querySelectorAll(".tab").forEach((t) => t.classList.remove("activo"));
    document.querySelectorAll(".seccion").forEach((s) => s.classList.add("oculto"));
    tab.classList.add("activo");
    document.getElementById(tab.dataset.seccion).classList.remove("oculto");
  });
});
