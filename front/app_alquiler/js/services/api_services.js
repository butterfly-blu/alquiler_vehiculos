// Direcciones de los microservicios
const VEHICULOS_URL = "http://127.0.0.1:8081/vehiculos";
const CLIENTES_URL = "http://127.0.0.1:8082/clientes";
const RESERVAS_URL = "http://127.0.0.1:8083/reservas";

/**
 * Hace la peticion al microservicio y llama a callbacks.success(data)
 * o a callbacks.error(mensaje) segun la respuesta.
 */
const request = async (url, method, body, callbacks, serviceName) => {
  try {
    const options = { method: method };
    if (body) {
      options.headers = { "Content-Type": "application/json" };
      options.body = JSON.stringify(body);
    }
    const response = await fetch(url, options);
    let data = null;
    try {
      data = await response.json();
    } catch (e) {
      data = null;
    }
    if (!response.ok) {
      throw new Error(data && data.error ? data.error : "Error " + response.status);
    }
    callbacks.success(data);
  } catch (exception) {
    console.error(exception);
    callbacks.error(exception.message);
  }
  console.info("Fin del servicio " + serviceName);
};
