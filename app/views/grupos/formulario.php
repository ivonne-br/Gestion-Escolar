<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Asignar Grupos</title>
    <link rel="stylesheet" href="/GestionEscolar/public/css/estilo.css">
    <link rel="stylesheet" href="/GestionEscolar/public/css/formulario_grupos.css">
</head>
<body>
    <div class="header">
        <img src="/GestionEscolar/public/img/logo_colegio.png" alt="Logo Colegio" class="header-logo">
        <div class="header-text">
            <div class="header-title">Colegio Abraham Lincoln</div>
            <div class="header-subtitle">fundado en 1971</div>
        </div>
    </div>
    <div class="usuario-info">
        <div class="usuario-texto">
            <?php
            session_start();
            echo isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : '';
            echo "<br>";
            echo isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : '';
            ?>
        </div>
        <img src="/GestionEscolar/public/img/usuario_icono.png" alt="Perfil" class="usuario-icono">
    </div>
    <div class="formulario-contenedor">
        <div class="formulario-layout">
            <div class="formulario-sidebar">
                <h2>Asignar Grupos</h2>

                <form class="formulario-grupo" method="GET" action="/GestionEscolar/public/grupos/formulario">
                    <label>Nivel:</label>
                    <select name="nivel" required>
                        <option value="">-- Selecciona un nivel --</option>
                        <option value="Primaria" <?= (isset($nivel) && $nivel === 'Primaria') ? 'selected' : '' ?>>Primaria</option>
                        <option value="Secundaria" <?= (isset($nivel) && $nivel === 'Secundaria') ? 'selected' : '' ?>>Secundaria</option>
                    </select>

                    <label>Grado:</label>
                    <select name="grado" required>
                        <option value="">-- Selecciona un grado --</option>
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="<?= $i ?>" <?= (isset($grado) && $grado == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>

                    <button type="submit" class="boton-filtrar">🔍 Filtrar</button>
                </form>

                <a href="/GestionEscolar/public/grupos/opciones" class="boton-volver">⬅ Volver a Grupos</a>
            </div>

            <div class="formulario-contenido">
                <?php if (!empty($nivel) && !empty($grado) && is_numeric($alumnosSinGrupo)): ?>
                    <p><strong>Alumnos sin grupo:</strong> <?= $alumnosSinGrupo ?> alumno(s)</p>

                    <form class="formulario-grupo" method="POST" action="/GestionEscolar/public/grupos/preview_asignacion">
                        <input type="hidden" name="nivel" value="<?= htmlspecialchars($nivel ?? '') ?>">
                        <input type="hidden" name="grado" value="<?= htmlspecialchars($grado ?? '') ?>">

                        <label>Número de Grupos:</label>
                        <select name="num_grupos" required>
                            <option value="">-- Número de grupos (ej. 3 para A, B, C) --</option>
                            <?php for ($i = 1; $i <= 7; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?> grupo(s)</option>
                            <?php endfor; ?>
                        </select>

                        <label>Ciclo Escolar:</label>
                        <select name="id_ciclo" required>
                            <option value="">-- Selecciona un ciclo escolar --</option>
                            <?php foreach ($ciclos as $c): ?>
                                <option value="<?= $c['id_ciclo'] ?>"><?= htmlspecialchars($c['nombre_ciclo']) ?></option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit">📄 Previsualizar Asignación</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>