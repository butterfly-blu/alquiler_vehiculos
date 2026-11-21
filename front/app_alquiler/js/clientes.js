const clientesTable = document.getElementById("clientes-table");
const clienteForm = document.forms["cliente-form"];

const consultarClientes = () => {
  queryClients({
    success: (data) => {
      clientes = data;
      trs_clients(clientesTable, data, {
        editar: editarCliente,
        borrar: borrarCliente,
      });
      llenarSelects();
    },
    error: (text) => msgError("Error al consultar los clientes: " + text),
  });
};

const editarCliente = (item) => {
  clienteForm["id"].value = item.id;
  clienteForm["nombre"].value = item.nombre;
  clienteForm["telefono"].value = item.telefono;
  clienteForm["correo"].value = item.correo;
  clienteForm["numero_licencia"].value = item.numero_licencia;
  clienteForm.querySelector("button[type=submit]").textContent = "Actualizar";
};

const borrarCliente = (id) => {
  confirmar("¿Desea borrar el cliente?", () => {
    deleteClient(id, {
      success: () => {
        msg(true, "Cliente borrado");
        consultarClientes();
      },
      error: msgError,
    });
  });
};

clienteForm.addEventListener("reset", () => {
  clienteForm["id"].value = "";
  clienteForm.querySelector("button[type=submit]").textContent = "Guardar";
});

clienteForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const id = clienteForm["id"].value;
  const data = {
    nombre: clienteForm["nombre"].value,
    telefono: clienteForm["telefono"].value,
    correo: clienteForm["correo"].value,
    numero_licencia: clienteForm["numero_licencia"].value,
  };
  const callbacks = {
    success: () => {
      msg(true, "Cliente guardado");
      clienteForm.reset();
      consultarClientes();
    },
    error: msgError,
  };
  if (id) {
    updateClient(id, data, callbacks);
  } else {
    saveNewClient(data, callbacks);
  }
});
