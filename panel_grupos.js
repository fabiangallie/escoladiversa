document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('crearGrupo');
  const alumnosContainer = document.getElementById('alumnosContainer');
  const gruposGuardados = document.getElementById('gruposGuardados');

  // Cargar grupos al iniciar
  cargarGrupos();

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const nombreGrupo = document.getElementById('nombreGrupo').value.trim();
    const alumnos = Array.from(document.getElementsByName('alumno'))
      .map(input => input.value.trim())
      .filter(nombre => nombre !== '');

    if (!nombreGrupo || alumnos.length === 0) {
      alert('Debes ingresar un nombre de grupo y al menos un alumno.');
      return;
    }

    const grupos = JSON.parse(localStorage.getItem('grupos')) || {};
    grupos[nombreGrupo] = alumnos;
    localStorage.setItem('grupos', JSON.stringify(grupos));

    form.reset();
    cargarGrupos();
  });

  function cargarGrupos() {
    gruposGuardados.innerHTML = '<h3>Grupos guardados</h3>';
    const grupos = JSON.parse(localStorage.getItem('grupos')) || {};

    Object.keys(grupos).forEach(grupo => {
      const div = document.createElement('div');
      div.className = 'grupo';
      div.innerHTML = `
        <strong>${grupo}</strong>: ${grupos[grupo].join(', ')}
      `;
      gruposGuardados.appendChild(div);
    });
  }
});
