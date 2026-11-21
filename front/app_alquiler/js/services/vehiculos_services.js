const queryVehicles = (callbacks) =>
  request(VEHICULOS_URL, "get", null, callbacks, "queryVehicles");

const queryVehicle = (id, callbacks) =>
  request(VEHICULOS_URL + "/" + id, "get", null, callbacks, "queryVehicle");

const queryAvailableVehicles = (fecha, callbacks) =>
  request(VEHICULOS_URL + "/disponibles/" + fecha, "get", null, callbacks, "queryAvailableVehicles");

const saveNewVehicle = (vehicle, callbacks) =>
  request(VEHICULOS_URL, "post", vehicle, callbacks, "saveNewVehicle");

const updateVehicle = (id, vehicle, callbacks) =>
  request(VEHICULOS_URL + "/" + id, "put", vehicle, callbacks, "updateVehicle");

const deleteVehicle = (id, callbacks) =>
  request(VEHICULOS_URL + "/" + id, "delete", null, callbacks, "deleteVehicle");
