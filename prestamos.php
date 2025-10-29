<?php
include 'includes/db_conexion.php'; 

$conexion = conectarBiblioteca(); // Conéctate a la DB Biblioteca

// Asume que la tabla se llama 'prestamos' y contiene los campos solicitados
$sql_listado = "SELECT 
                    fecha_prestamo, 
                    apellido, 
                    nombre, 
                    curso, 
                    fecha_devolucion 
                FROM prestamos 
                ORDER BY fecha_prestamo DESC";
                
$resultado = $conexion->query($sql_listado);

$conexion->close();
?>

<?php /* Aquí se incluiría la cabecera HTML/Bootstrap (Navbar) */ ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">...</nav> 

<div class="container mt-5">
    <h2>Elementos PRESTADOS</h2>
    <p>Visualización de los registros de préstamos de la base de datos **Biblioteca**.</p>

    <div class="table-responsive">
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-info">
            <tr>
                <th>Fecha Préstamo</th>
                <th>Apellido y Nombre</th>
                <th>Curso</th>
                <th>Fecha Devolución</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($resultado && $resultado->num_rows > 0):
                while($fila = $resultado->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($fila['fecha_prestamo'])); ?></td>
                <td><?php echo htmlspecialchars($fila['apellido']) . ", " . htmlspecialchars($fila['nombre']); ?></td>
                <td><?php echo htmlspecialchars($fila['curso']); ?></td>
                <td>
                    <?php 
                        $dev = $fila['fecha_devolucion'];
                        if (empty($dev) || $dev == '0000-00-00') {
                            echo '<span class="badge bg-danger">PENDIENTE</span>';
                        } else {
                            echo date('d/m/Y', strtotime($dev));
                        }
                    ?>
                </td>
                <td>
                    <?php 
                        // Lógica de estado simple
                        if (empty($dev) || $dev == '0000-00-00') {
                            echo '<span class="badge bg-warning text-dark">Activo</span>';
                        } else {
                            echo '<span class="badge bg-success">Devuelto</span>';
                        }
                    ?>
                </td>
            </tr>
            <?php 
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="5" class="text-center">No hay préstamos registrados.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?php /* Aquí se incluiría el script de Bootstrap y el cierre del body/html */ ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>