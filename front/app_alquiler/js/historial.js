const histTipo = document.getElementById("hist-tipo");
const histElemento = document.getElementById("hist-elemento");
const historialTable = document.getElementById("historial-table");

// Llena los selects de reservas e historial con los clientes y vehículos cargados
const llenarSelects = () => {
  const vehicleText = (v) => v.marca + " " + v.modelo;
  fill_select(reservaForm["cliente_id"], clientes, (c) => c.nombre);
  fill_select(reservaForm["vehiculo_id"], vehiculos, (v) => vehicleText(v) + " (" + v.estado + ")");

  const seleccionado = histElemento.value;
  if (histTipo.value === "cliente") {
    fill_select(histElemento, clientes, (c) => c.nombre);
  } else {
    fill_select(histElemento, vehiculos, vehicleText);
  }
  if ([...histElemento.options].some((o) => o.value === seleccionado)) {
    histElemento.value = seleccionado;
  }
  cargarHistorial();
};

const cargarHistorial = () => {
  const id = histElemento.value;
  if (!id) {
    historialTable.getElementsByTagName("tbody")[0].textContent = "";
    return;
  }
  const filters = histTipo.value === "cliente" ? { cliente_id: id } : { vehiculo_id: id };
  queryReservations(filters, {
    success: (data) => trs_reservations(historialTable, data, null),
    error: msgError,
  });
};

histTipo.addEventListener("change", llenarSelects);
histElemento.addEventListener("change", cargarHistorial);
