<?php 
include 'conexion.php';
session_start();

// Verificar que esté logueado
if (!isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

$rol = $_SESSION['rol'];

// Solo administradores pueden acceder
if ($rol != 1) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">
  <?php include 'navbar.php'; ?>
  
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-users"></i> Gestión de Usuarios</h2>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUsuario">
        <i class="fas fa-plus"></i> Agregar Usuario
      </button>
    </div>

    <?php if (isset($_GET['msg'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        switch($_GET['msg']) {
          case 'created': echo '<i class="fas fa-check"></i> Usuario creado exitosamente'; break;
          case 'updated': echo '<i class="fas fa-check"></i> Usuario actualizado exitosamente'; break;
          case 'deleted': echo '<i class="fas fa-check"></i> Usuario eliminado exitosamente'; break;
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> Error al procesar la solicitud
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Usuarios</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-dark">
              <tr>
                <th>Usuario</th>
                <th>Clave</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>DNI</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $resultado = mysqli_query($conexion, "SELECT u.*, r.nombre_rol FROM tbl_usuario u LEFT JOIN tbl_rol r ON u.rol = r.id_rol ORDER BY u.id");
              if (mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
              ?>
                <tr>
                  <td>
                    <strong><?php echo htmlspecialchars($fila['usuario']); ?></strong>
                  </td>
                  <td>
                    <span class="text-muted">••••••••</span>
                  </td>
                   <td><?php echo !empty($fila['apellidos']) ? htmlspecialchars($fila['apellidos']) : '<span class="text-muted">-</span>'; ?></td>
                  <td><?php echo !empty($fila['nombres']) ? htmlspecialchars($fila['nombres']) : '<span class="text-muted">-</span>'; ?></td>
                  <td><?php echo !empty($fila['dni']) ? htmlspecialchars($fila['dni']) : '<span class="text-muted">-</span>'; ?></td>
                  <td>
                    <span class="badge <?php 
                      switch($fila['rol']) {
                        case 1: echo 'bg-danger'; break;
                        case 2: echo 'bg-warning text-dark'; break;
                        case 3: echo 'bg-info'; break;
                        default: echo 'bg-secondary';
                      }
                    ?>">
                      <?php 
              
                      switch($fila['rol']) {
                          case 1: echo 'Administrador'; break;
                          case 2: echo 'Docente'; break;
                          case 3: echo 'Estudiante'; break;
                          default: echo 'Sin rol'; break;
                      }
                      ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge <?php echo $fila['estado'] == 1 ? 'bg-success' : 'bg-secondary'; ?>">
                      <?php echo $fila['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-warning mb-1"
                      data-bs-toggle="modal"
                      data-bs-target="#modalUsuario"
                      onclick='editarUsuario(<?php echo json_encode($fila); ?>)'>
                      <i class="fas fa-edit"></i> Editar
                    </button>
                    
                    <?php if ($fila['id'] != $_SESSION['usuario']): // No puede eliminarse a sí mismo ?>
                      <a href="crud_usuarios.php?eliminar=<?php echo $fila['id']; ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                        <i class="fas fa-trash"></i> Eliminar
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php 
                }
              } else {
                echo "<tr><td colspan='8' class='text-center'>No hay usuarios registrados</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Agregar/Editar Usuario -->
  <div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <form class="modal-content" method="POST" action="crud_usuarios.php" style="background-color: #e0f7fa;">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="fas fa-users me-2"></i>
            <span id="tituloModalUsuario">Registrar Usuario</span>
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px;">
          <input type="hidden" name="id" id="id">

          <!-- Sección Datos de Acceso -->
          <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white border-0 py-2">
              <h6 class="mb-0 text-primary">
                <i class="fas fa-key me-2"></i>Datos de Acceso
              </h6>
            </div>
            <div class="card-body bg-white py-3">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="usuario" class="form-label fw-bold">
                      <i class="fas fa-user text-primary me-1"></i> Usuario
                    </label>
                    <input type="text" name="usuario" id="usuario" class="form-control shadow-sm" 
                           required maxlength="50" placeholder="Ej: jgarcia"
                           style="border-left: 4px solid #667eea;">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="clave" class="form-label fw-bold">
                      <i class="fas fa-lock text-danger me-1"></i> Contraseña
                    </label>
                    <input type="password" name="clave" id="clave" class="form-control shadow-sm" 
                           required maxlength="100" placeholder="Contraseña segura"
                           style="border-left: 4px solid #dc3545;">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sección Datos Personales -->
          <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white border-0 py-2">
              <h6 class="mb-0 text-primary">
                <i class="fas fa-id-card me-2"></i>Información Personal
              </h6>
            </div>
            <div class="card-body bg-white py-3">
              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="apellidos" class="form-label fw-bold">
                      <i class="fas fa-user text-info me-1"></i> Apellidos
                    </label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control shadow-sm" 
                           required maxlength="100" placeholder="Ej: García López"
                           style="border-left: 4px solid #17a2b8;">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="nombres" class="form-label fw-bold">
                      <i class="fas fa-user text-success me-1"></i> Nombres
                    </label>
                    <input type="text" name="nombres" id="nombres" class="form-control shadow-sm" 
                           required maxlength="100" placeholder="Ej: Juan Carlos"
                           style="border-left: 4px solid #28a745;">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="dni" class="form-label fw-bold">
                      <i class="fas fa-id-card text-warning me-1"></i> DNI
                    </label>
                    <input type="text" name="dni" id="dni" class="form-control shadow-sm" 
                           required maxlength="8" pattern="[0-9]{8}" title="Debe contener 8 dígitos" placeholder="12345678"
                           style="border-left: 4px solid #ffc107;">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sección Configuración del Sistema -->
          <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white border-0 py-2">
              <h6 class="mb-0 text-primary">
                <i class="fas fa-cogs me-2"></i>Configuración del Sistema
              </h6>
            </div>
            <div class="card-body bg-white py-3">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="rol" class="form-label fw-bold">
                      <i class="fas fa-user-tag text-primary me-1"></i> Rol del Usuario
                    </label>
                    <select class="form-select shadow-sm" id="rol" name="rol" required
                            style="border-left: 4px solid #667eea;">
                      <option value="">🎯 Seleccione un rol</option>
                      <option value="1">👑 Administrador</option>
                      <option value="2">👨‍🏫 Docente</option>
                      <option value="3">👨‍🎓 Estudiante</option>
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
                      <option value="0">❌ Inactivo</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Información sobre roles -->
          <div class="alert alert-primary shadow-sm border-0 py-2" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); color: white;">
            <div class="d-flex align-items-center">
              <i class="fas fa-info-circle me-2"></i>
              <div>
                <strong>Roles:</strong>
                <span class="ms-2">👑 Admin: Control total | 👨‍🏫 Docente: Gestión estudiantes | 👨‍🎓 Estudiante: Solo consulta</span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="background: #f8f9fa; padding: 15px 20px;">
          <button type="submit" name="guardar" class="btn btn-lg px-4 shadow-sm" 
                  style="background: linear-gradient(135deg, #00b894 0%, #00a085 100%); color: white; border: none;">
            <i class="fas fa-save me-2"></i> Guardar Usuario
          </button>
          <button type="button" class="btn btn-lg btn-outline-secondary px-4 shadow-sm" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i> Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function editarUsuario(data) {
      // Cambiar título del modal
      document.getElementById('tituloModalUsuario').textContent = 'Editar Usuario';
      
      document.getElementById('id').value = data.id;
      document.getElementById('usuario').value = data.usuario;
      document.getElementById('clave').value = data.clave;
      document.getElementById('apellidos').value = data.apellidos;
      document.getElementById('nombres').value = data.nombres;
      document.getElementById('dni').value = data.dni;
      document.getElementById('rol').value = data.rol;
      document.getElementById('estado').value = data.estado;
    }

    // Limpiar formulario al cerrar modal
    document.getElementById('modalUsuario').addEventListener('hidden.bs.modal', function () {
      document.querySelector('#modalUsuario form').reset();
      document.getElementById('tituloModalUsuario').textContent = 'Registrar Usuario';
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>