const vehiculosTable = document.getElementById("vehiculos-table");
const vehiculoForm = document.forms["vehiculo-form"];

const consultarVehiculos = () => {
  queryVehicles({
    success: (data) => {
      vehiculos = data;
      trs_vehicles(vehiculosTable, data, {
        editar: editarVehiculo,
        borrar: borrarVehiculo,
      });
      llenarSelects();
    },
    error: (text) => msgError("Error al consultar los vehículos: " + text),
  });
};

const editarVehiculo = (item) => {
  vehiculoForm["id"].value = item.id;
  vehiculoForm["marca"].value = item.marca;
  vehiculoForm["modelo"].value = item.modelo;
  vehiculoForm["anio"].value = item.anio;
  vehiculoForm["categoria"].value = item.categoria;
  vehiculoForm["estado"].value = item.estado;
  vehiculoForm.querySelector("button[type=submit]").textContent = "Actualizar";
};

const borrarVehiculo = (id) => {
  confirmar("¿Desea borrar el vehículo?", () => {
    deleteVehicle(id, {
      success: () => {
        msg(true, "Vehículo borrado");
        consultarVehiculos();
      },
      error: msgError,
    });
  });
};

vehiculoForm.addEventListener("reset", () => {
  vehiculoForm["id"].value = "";
  vehiculoForm.querySelector("button[type=submit]").textContent = "Guardar";
});

vehiculoForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const id = vehiculoForm["id"].value;
  const data = {
    marca: vehiculoForm["marca"].value,
    modelo: vehiculoForm["modelo"].value,
    anio: parseInt(vehiculoForm["anio"].value),
    categoria: vehiculoForm["categoria"].value,
    estado: vehiculoForm["estado"].value,
  };
  const callbacks = {
    success: () => {
      msg(true, "Vehículo guardado");
      vehiculoForm.reset();
      consultarVehiculos();
    },
    error: msgError,
  };
  if (id) {
    updateVehicle(id, data, callbacks);
  } else {
    saveNewVehicle(data, callbacks);
  }
});

// ---------- disponibilidad ----------
document.getElementById("disp-consultar").addEventListener("click", () => {
  const fecha = document.getElementById("disp-fecha").value;
  if (!fecha) {
    msgError("Elige una fecha");
    return;
  }
  queryAvailableVehicles(fecha, {
    success: (data) => trs_vehicles(document.getElementById("disponibles-table"), data, null),
    error: msgError,
  });
});
