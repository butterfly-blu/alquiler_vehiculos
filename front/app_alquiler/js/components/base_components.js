const td = (content) => {
  const component = document.createElement("td");
  component.textContent = content == null ? "" : content;
  return component;
};

const button = (content, onClick, className = "") => {
  const component = document.createElement("button");
  component.textContent = content;
  if (className) component.className = className;
  component.addEventListener("click", () => onClick());
  return component;
};

const etiqueta = (estado) => {
  const component = document.createElement("span");
  component.className = "etiqueta " + estado;
  component.textContent = estado;
  const cell = document.createElement("td");
  cell.appendChild(component);
  return cell;
};

const tr = (cells) => {
  const component = document.createElement("tr");
  cells.forEach((cell) => component.appendChild(cell));
  return component;
};

const empty_row = (table, colspan, text = "No hay datos registrados...") => {
  const cell = td(text);
  cell.colSpan = colspan;
  return tr([cell]);
};

const option = (value, text) => {
  const component = document.createElement("option");
  component.value = value;
  component.textContent = text;
  return component;
};

const fill_select = (select, items, getText) => {
  select.textContent = "";
  items.forEach((item) => select.appendChild(option(item.id, getText(item))));
};
