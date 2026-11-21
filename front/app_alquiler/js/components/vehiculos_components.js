const vehicle_cells = (item) => [
  td(item.id),
  td(item.marca),
  td(item.modelo),
  td(item.anio),
  td(item.categoria),
  etiqueta(item.estado),
];

const trs_vehicles = (table, data, actions) => {
  const tbody = table.getElementsByTagName("tbody")[0];
  const columns = actions ? 7 : 6;
  tbody.textContent = "";
  if (data.length === 0) {
    tbody.appendChild(empty_row(table, columns));
    return;
  }
  data.forEach((item) => {
    const cells = vehicle_cells(item);
    if (actions) {
      const tdBotones = td("");
      tdBotones.appendChild(button("Editar", () => actions.editar(item)));
      tdBotones.appendChild(button("Borrar", () => actions.borrar(item.id), "peligro"));
      cells.push(tdBotones);
    }
    tbody.appendChild(tr(cells));
  });
};
