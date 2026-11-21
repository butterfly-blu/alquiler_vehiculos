const trs_reservations = (table, data, actions) => {
  const tbody = table.getElementsByTagName("tbody")[0];
  const columns = actions ? 7 : 6;
  tbody.textContent = "";
  if (data.length === 0) {
    tbody.appendChild(empty_row(table, columns));
    return;
  }
  data.forEach((item) => {
    const cells = [
      td(item.id),
      td(item.cliente),
      td(item.vehiculo),
      td(item.fecha_inicio),
      td(item.fecha_fin),
      etiqueta(item.estado),
    ];
    if (actions) {
      const tdBotones = td("");
      if (item.estado === "activa") {
        tdBotones.appendChild(button("Devolver", () => actions.devolver(item.id)));
        tdBotones.appendChild(button("Cancelar", () => actions.cancelar(item.id), "peligro"));
      }
      cells.push(tdBotones);
    }
    tbody.appendChild(tr(cells));
  });
};
