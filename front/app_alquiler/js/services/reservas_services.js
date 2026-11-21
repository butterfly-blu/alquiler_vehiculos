// filters: { cliente_id } o { vehiculo_id } para consultar el historial
const queryReservations = (filters, callbacks) => {
  const params = new URLSearchParams(filters || {}).toString();
  const url = params ? RESERVAS_URL + "?" + params : RESERVAS_URL;
  return request(url, "get", null, callbacks, "queryReservations");
};

const saveNewReservation = (reservation, callbacks) =>
  request(RESERVAS_URL, "post", reservation, callbacks, "saveNewReservation");

const returnReservation = (id, callbacks) =>
  request(RESERVAS_URL + "/" + id + "/devolver", "put", null, callbacks, "returnReservation");

const cancelReservation = (id, callbacks) =>
  request(RESERVAS_URL + "/" + id + "/cancelar", "put", null, callbacks, "cancelReservation");
