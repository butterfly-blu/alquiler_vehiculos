const queryClients = (callbacks) =>
  request(CLIENTES_URL, "get", null, callbacks, "queryClients");

const queryClient = (id, callbacks) =>
  request(CLIENTES_URL + "/" + id, "get", null, callbacks, "queryClient");

const saveNewClient = (client, callbacks) =>
  request(CLIENTES_URL, "post", client, callbacks, "saveNewClient");

const updateClient = (id, client, callbacks) =>
  request(CLIENTES_URL + "/" + id, "put", client, callbacks, "updateClient");

const deleteClient = (id, callbacks) =>
  request(CLIENTES_URL + "/" + id, "delete", null, callbacks, "deleteClient");
