const reservasTable = document.getElementById("reservas-table");
const reservaForm = document.forms["reserva-form"];

const consultarReservas = () => {
  queryReservations(null, {
    success: (data) =>
      trs_reservations(reservasTable, data, {
        devolver: devolverReserva,
        cancelar: cancelarReserva,
      }),
    error: (text) => msgError("Error al consultar las reservas: " + text),
  });
};

// Al cambiar una reserva cambia el estado del vehículo, por eso se recarga todo
const recargarReservas = () => {
  consultarReservas();
  consultarVehiculos();
  cargarHistorial();
};

const devolverReserva = (id) => {
  returnReservation(id, {
    success: () => {
      msg(true, "Vehículo devuelto");
      recargarReservas();
    },
    error: msgError,
  });
};

const cancelarReserva = (id) => {
  confirmar("¿Desea cancelar la reserva?", () => {
    cancelReservation(id, {
      success: () => {
        msg(true, "Reserva cancelada");
        recargarReservas();
      },
      error: msgError,
    });
  });
};

reservaForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const data = {
    cliente_id: parseInt(reservaForm["cliente_id"].value),
    vehiculo_id: parseInt(reservaForm["vehiculo_id"].value),
    fecha_inicio: reservaForm["fecha_inicio"].value,
    fecha_fin: reservaForm["fecha_fin"].value,
  };
  saveNewReservation(data, {
    success: () => {
      msg(true, "Reserva creada");
      reservaForm.reset();
      recargarReservas();
    },
    error: msgError,
  });
});
