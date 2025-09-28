<?php 
include 'conexion.php';
session_start();

// Verificar que esté logueado
if (!isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

$rol = $_SESSION['rol'];

// Verificar permisos (solo admin y docente pueden acceder)
if ($rol == 3) {
    header("Location: index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Estudiantes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">
  <?php include 'navbar.php'; ?>
  
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-user-graduate"></i> Gestión de Estudiantes</h2>
      <?php if ($rol == 1 || $rol == 2): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEstudiante">
          <i class="fas fa-plus"></i> Agregar Estudiante
        </button>
      <?php endif; ?>
    </div>

    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Estudiantes</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive" style="height: 500px; overflow-y: auto;">
          <table class="table table-bordered table-hover">
            <thead class="table-dark">
              <tr>
                <th>DNI</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Sexo</th>
                <th>Estado</th>
                <th>Programa</th>
                <th>Ciclo</th>
                <th>Turno</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $resultado = mysqli_query($conexion, "SELECT * FROM tbl_estudiante ORDER BY id");
              if (mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
              ?>
                <tr>
                  <td><?php echo $fila['dni']; ?></td>
                  <td><?php echo $fila['nombre']; ?></td>
                  <td><?php echo $fila['apellidos']; ?></td>
                  <td>
                    <span class="badge <?php echo $fila['sexo'] == 'M' ? 'bg-primary' : 'bg-info'; ?>">
                      <?php echo $fila['sexo'] == 'M' ? 'Masculino' : 'Femenino'; ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge <?php echo $fila['estado'] == 1 ? 'bg-success' : 'bg-warning'; ?>">
                      <?php echo $fila['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                    </span>
                  </td>
                  <td>
                    <?php echo $fila['programa'] ?: 'Programa no especificado'; ?>
                  </td>
                  <td>
                    <span class="badge bg-secondary"><?php echo $fila['ciclo'] ?: 'N/A'; ?></span>
                  </td>
                  <td>
                    <span class="badge <?php echo $fila['turno'] == 1 ? 'bg-warning text-dark' : 'bg-dark'; ?>">
                      <?php echo $fila['turno'] == 1 ? 'Diurno' : ($fila['turno'] == 2 ? 'Nocturno' : 'N/A'); ?>
                    </span>
                  </td>
                  <td>
                    <?php if ($rol == 1 || $rol == 2): ?>
                      <button class="btn btn-sm btn-warning mb-1"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEstudiante"
                        onclick='editarEstudiante(<?php echo json_encode($fila); ?>)'>
                        <i class="fas fa-edit"></i> Editar
                      </button>
                    <?php endif; ?>

                    <?php if ($rol == 1): ?>
                      <a href="crud_estudiantes.php?eliminar=<?php echo $fila['id']; ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('¿Seguro que deseas eliminar este registro?');">
                        <i class="fas fa-trash"></i> Eliminar
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php 
                }
              } else {
                echo "<tr><td colspan='9' class='text-center'>No hay estudiantes registrados</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Agregar/Editar Estudiante -->
  <div class="modal fade" id="modalEstudiante" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <form class="modal-content" method="POST" action="crud_estudiantes.php" style="background-color: #e0f7fa;">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="fas fa-user-graduate me-2"></i>
            <span id="tituloModal">Registrar Estudiante</span>
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px;">
          <input type="hidden" name="id" id="id">

          <!-- Sección Datos Personales -->
          <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white border-0 py-2">
              <h6 class="mb-0 text-primary">
                <i class="fas fa-user me-2"></i>Datos Personales
              </h6>
            </div>
            <div class="card-body bg-white py-3">
              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="dni" class="form-label fw-bold">
                      <i class="fas fa-id-card text-primary me-1"></i> DNI
                    </label>
                    <input type="text" name="dni" id="dni" class="form-control shadow-sm" 
                           required maxlength="8" pattern="[0-9]{8}" 
                           title="Debe contener 8 dígitos" placeholder="Ej: 12345678"
                           style="border-left: 4px solid #667eea;">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="nombres" class="form-label fw-bold">
                      <i class="fas fa-user text-success me-1"></i> Nombres
                    </label>
                    <input type="text" name="nombres" id="nombres" class="form-control shadow-sm" 
                           required placeholder="Ej: Cristhian"
                           style="border-left: 4px solid #28a745;">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="apellidos" class="form-label fw-bold">
                      <i class="fas fa-user text-info me-1"></i> Apellidos
                    </label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control shadow-sm" 
                           required placeholder="Ej: García López"
                           style="border-left: 4px solid #17a2b8;">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="sexo" class="form-label fw-bold">
                      <i class="fas fa-venus-mars text-warning me-1"></i> Sexo
                    </label>
                    <select class="form-select shadow-sm" id="sexo" name="sexo" required
                            style="border-left: 4px solid #ffc107;">
                      <option value="">🎯 Seleccione una opción</option>
                      <option value="M">👨 Masculino</option>
                      <option value="F">👩 Femenino</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="estado" class="form-label fw-bold">
                      <i class="fas fa-toggle-on text-success me-1"></i> Estado
                    </label>
                    <select class="form-select shadow-sm" id="estado" name="estado" required
                            style="border-left: 4px solid #28a745;">
                      <option value="">🎯 Seleccione estado</option>
                      <option value="1">✅ Activo</option>
                      <option value="2">⚠️ Inactivo</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sección Datos Académicos -->
          <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white border-0 py-2">
              <h6 class="mb-0 text-primary">
                <i class="fas fa-graduation-cap me-2"></i>Información Académica
              </h6>
            </div>
            <div class="card-body bg-white py-3">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="programa" class="form-label fw-bold">
                      <i class="fas fa-book text-primary me-1"></i> Programa Académico
                    </label>
                    <select class="form-select shadow-sm" id="programa" name="programa" required
                            style="border-left: 4px solid #667eea;">
                      <option value="">📚 Seleccione programa</option>
                      <option value="Programación y Diseño Web">💻 Programación y Diseño Web</option>
                      <option value="Contabilidad">📊 Contabilidad</option>
                      <option value="Prótesis Dental">🦷 Prótesis Dental</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="mb-3">
                    <label for="ciclo" class="form-label fw-bold">
                      <i class="fas fa-layer-group text-info me-1"></i> Ciclo
                    </label>
                    <select class="form-select shadow-sm" id="ciclo" name="ciclo" required
                            style="border-left: 4px solid #17a2b8;">
                      <option value="">📅 Seleccione</option>
                      <option value="I">🥇 I</option>
                      <option value="II">🥈 II</option>
                      <option value="III">🥉 III</option>
                      <option value="IV">🏆 IV</option>
                      <option value="V">⭐ V</option>
                      <option value="VI">🌟 VI</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="mb-3">
                    <label for="turno" class="form-label fw-bold">
                      <i class="fas fa-clock text-warning me-1"></i> Turno
                    </label>
                    <select class="form-select shadow-sm" id="turno" name="turno" required
                            style="border-left: 4px solid #ffc107;">
                      <option value="">⏰ Seleccione</option>
                      <option value="1">🌅 Diurno</option>
                      <option value="2">🌙 Nocturno</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Mensaje informativo -->
          <!-- <div class="alert alert-info shadow-sm border-0" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); color: white;">
            <div class="d-flex align-items-center">
              <i class="fas fa-info-circle me-3 fs-4"></i>
              <div>
                <strong>Información importante:</strong><br>
                <small>Todos los campos marcados son obligatorios. Asegúrese de completar correctamente la información del estudiante.</small>
              </div>
            </div>
          </div> -->
        </div>
        <div class="modal-footer" style="background: #f8f9fa; padding: 15px 20px;">
          <button type="submit" name="guardar" class="btn btn-lg px-4 shadow-sm" 
                  style="background: linear-gradient(135deg, #00b894 0%, #00a085 100%); color: white; border: none;">
            <i class="fas fa-save me-2"></i> Guardar Estudiante
          </button>
          <button type="button" class="btn btn-lg btn-outline-secondary px-4 shadow-sm" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i> Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function editarEstudiante(data) {
      // Cambiar título del modal
      document.getElementById('tituloModal').textContent = 'Editar Estudiante';
      
      document.getElementById('id').value = data.id;
      document.getElementById('nombres').value = data.nombre;
      document.getElementById('apellidos').value = data.apellidos;
      document.getElementById('sexo').value = data.sexo;
      document.getElementById('estado').value = data.estado;
      document.getElementById('programa').value = data.programa;
      document.getElementById('ciclo').value = data.ciclo;
      document.getElementById('turno').value = data.turno;
      document.getElementById('dni').value = data.dni;
    }

    // Limpiar formulario al cerrar modal
    document.getElementById('modalEstudiante').addEventListener('hidden.bs.modal', function () {
      document.querySelector('#modalEstudiante form').reset();
      document.getElementById('tituloModal').textContent = 'Registrar Estudiante';
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>