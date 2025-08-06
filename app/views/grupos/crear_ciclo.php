<h3>🗓 Crear Nuevo Ciclo Escolar</h3>
<form method="POST" action="/GestionEscolar/public/grupos/crear_ciclo" onsubmit="return confirm('¿Estás seguro de crear un nuevo ciclo escolar?')">
    <label>Fecha de inicio:</label>
    <input type="date" name="fecha_inicio" required><br><br>

    <label>Fecha de fin:</label>
    <input type="date" name="fecha_fin" required><br><br>

    <button type="submit" name="crear_ciclo">➕ Crear Ciclo Escolar</button>
</form>
    <br>
    <a href="/GestionEscolar/public/grupos/opciones">⬅ Volver al Grupos</a>
<hr>