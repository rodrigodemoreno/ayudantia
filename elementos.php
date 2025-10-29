<?php
include 'includes/db_conexion.php'; // Incluye la conexión

$conexion = conectarAyudantia(); // Conéctate a la DB Ayudantia

// 1. Manejar Operaciones (Alta, Modificación, Baja)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        
        // Asume que la tabla se llama 'elementos' y tiene 'id', 'nombre', 'serial', 'estado'
        if ($accion == 'alta') {
            $nombre = $conexion->real_escape_string($_POST['nombre']);
            $serial = $conexion->real_escape_string($_POST['serial']);
            $estado = $conexion->real_escape_string($_POST['estado']);

            $sql = "INSERT INTO elementos (nombre, serial, estado) VALUES ('$nombre', '$serial', '$estado')";
            if (!$conexion->query($sql)) {
                $mensaje = "Error al dar de Alta: " . $conexion->error;
            } else {
                $mensaje = "Elemento dado de Alta con éxito.";
            }
        } 
        // Lógica similar para 'modificacion' y 'baja' (requieren el ID del registro)
        // ... (código para Modificación y Baja)
    }
}

// 2. Listado de Elementos (READ)
$sql_listado = "SELECT id, nombre, serial, estado FROM elementos";
$resultado = $conexion->query($sql_listado);

// 3. Obtener datos para Modificación (opcional: si se usa un formulario de edición)
$elemento_a_modificar = null;
if (isset($_GET['modificar_id'])) {
    $id = (int)$_GET['modificar_id'];
    $sql_sel = "SELECT id, nombre, serial, estado FROM elementos WHERE id = $id";
    $res_sel = $conexion->query($sql_sel);
    if ($res_sel->num_rows == 1) {
        $elemento_a_modificar = $res_sel->fetch_assoc();
    }
}

$conexion->close();
?>

<?php /* Aquí se incluiría la cabecera HTML/Bootstrap (Navbar) */ ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">...</nav> <div class="container mt-5">
    <h2>Gestión de ELEMENTOS Informáticos</h2>
    
    <?php if (isset($mensaje)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <hr>
    
    <ul class="nav nav-tabs" id="crudTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="listado-tab" data-bs-toggle="tab" data-bs-target="#listado" type="button" role="tab" aria-controls="listado" aria-selected="true">Listado</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="alta-tab" data-bs-toggle="tab" data-bs-target="#alta" type="button" role="tab" aria-controls="alta" aria-selected="false">Alta</button>
        </li>
    </ul>

    <div class="tab-content" id="crudTabContent">
        <div class="tab-pane fade show active" id="listado" role="tabpanel" aria-labelledby="listado-tab">
            <h4 class="mt-4">Listado de Elementos</h4>
            <div class="table-responsive">
            <table class="table table-striped table-hover mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>N° de Serie</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($resultado && $resultado->num_rows > 0):
                        while($fila = $resultado->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $fila['id']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['serial']; ?></td>
                        <td><span class="badge <?php echo ($fila['estado'] == 'Disponible' ? 'bg-success' : 'bg-danger'); ?>"><?php echo $fila['estado']; ?></span></td>
                        <td>
                            <a href="elementos.php?modificar_id=<?php echo $fila['id']; ?>" class="btn btn-sm btn-warning">Modificar</a>
                            <form method="POST" action="elementos.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                                <input type="hidden" name="accion" value="baja">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar este elemento?');">Baja</button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay elementos registrados.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>

        <div class="tab-pane fade" id="alta" role="tabpanel" aria-labelledby="alta-tab">
            <h4 class="mt-4">Dar de Alta un Nuevo Elemento</h4>
            <form method="POST" action="elementos.php" class="row g-3 mt-2">
                <input type="hidden" name="accion" value="alta">
                
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre del Elemento</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="col-md-6">
                    <label for="serial" class="form-label">Número de Serie / Inventario</label>
                    <input type="text" class="form-control" id="serial" name="serial" required>
                </div>
                
                <div class="col-md-4">
                    <label for="estado" class="form-label">Estado Inicial</label>
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="Disponible" selected>Disponible</option>
                        <option value="En Reparación">En Reparación</option>
                        <option value="Descarte">Descarte</option>
                    </select>
                </div>
                
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success">Registrar Elemento</button>
                </div>
            </form>
        </div>
        
        <?php /* Aquí iría el formulario de modificación si $elemento_a_modificar no es null */ ?>

    </div>
</div>

<?php /* Aquí se incluiría el script de Bootstrap y el cierre del body/html */ ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>