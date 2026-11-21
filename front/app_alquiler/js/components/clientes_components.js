const trs_clients = (table, data, actions) => {
  const tbody = table.getElementsByTagName("tbody")[0];
  tbody.textContent = "";
  if (data.length === 0) {
    tbody.appendChild(empty_row(table, 6));
    return;
  }
  data.forEach((item) => {
    const tdBotones = td("");
    tdBotones.appendChild(button("Editar", () => actions.editar(item)));
    tdBotones.appendChild(button("Borrar", () => actions.borrar(item.id), "peligro"));

    tbody.appendChild(
      tr([
        td(item.id),
        td(item.nombre),
        td(item.telefono),
        td(item.correo),
        td(item.numero_licencia),
        tdBotones,
      ])
    );
  });
};
