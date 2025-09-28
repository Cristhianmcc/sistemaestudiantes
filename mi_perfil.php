<?php 
include 'conexion.php';
session_start();

// Verificar que esté logueado y sea estudiante
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    header("Location: index.php");
    exit();
}

// Verificar que tenga DNI en la sesión
if (!isset($_SESSION['dni']) || empty($_SESSION['dni'])) {
    $error_vinculacion = "No se puede acceder al perfil: DNI no disponible en la sesión.";
    $estudiante = null;
} else {
    $dni_usuario = $_SESSION['dni'];
    
    // Obtener datos del estudiante por DNI
    $query = "SELECT * FROM tbl_estudiante WHERE dni = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $dni_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $estudiante = $resultado->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Mi Perfil - Sistema Educativo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">
  <?php include 'navbar.php'; ?>
  
  <div class="container mt-4">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2><i class="fas fa-user-graduate text-primary"></i> Mi Perfil Estudiantil</h2>
          <div class="text-muted">
            <small><i class="fas fa-user me-1"></i> Usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></small>
          </div>
        </div>
      </div>
    </div>

    <?php if (isset($error_vinculacion)): ?>
    <!-- Error de vinculación -->
    <div class="row">
      <div class="col-12">
        <div class="alert alert-danger" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Error de Vinculación:</strong> <?php echo $error_vinculacion; ?>
        </div>
      </div>
    </div>
    
    <?php elseif ($estudiante): ?>
    <!-- Datos del estudiante encontrado -->
    <div class="row">
      <!-- Datos Personales -->
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Información Personal</h5>
          </div>
          <div class="card-body">
            <div class="alert alert-success border-0 mb-3">
              <i class="fas fa-link me-2"></i>
              <strong>Vinculación Exitosa:</strong> Tu usuario está conectado correctamente con tus datos académicos.
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold text-muted">DNI</label>
                  <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                    <i class="fas fa-id-card text-primary me-2"></i>
                    <?php echo htmlspecialchars($estudiante['dni']); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold text-muted">Nombres</label>
                  <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                    <i class="fas fa-user text-success me-2"></i>
                    <?php echo htmlspecialchars($estudiante['nombre']); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold text-muted">Apellidos</label>
                  <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                    <i class="fas fa-user text-info me-2"></i>
                    <?php echo htmlspecialchars($estudiante['apellidos']); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold text-muted">Sexo</label>
                  <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                    <i class="fas fa-venus-mars text-warning me-2"></i>
                    <?php echo $estudiante['sexo'] == 'M' ? 'Masculino' : 'Femenino'; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Estado Académico -->
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Estado Académico</h5>
          </div>
          <div class="card-body text-center">
            <div class="mb-3">
              <span class="badge <?php echo $estudiante['estado'] == 1 ? 'bg-success' : 'bg-warning'; ?> p-3 fs-5">
                <i class="fas <?php echo $estudiante['estado'] == 1 ? 'fa-check-circle' : 'fa-pause-circle'; ?> me-2"></i>
                <?php echo $estudiante['estado'] == 1 ? 'ACTIVO' : 'INACTIVO'; ?>
              </span>
            </div>
            <div class="text-muted">
              <small>Estado de Matrícula Actual</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Información Académica -->
    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Información Académica</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="text-center p-4 border rounded bg-light">
                  <i class="fas fa-book text-primary fa-2x mb-3"></i>
                  <h6 class="fw-bold">Programa Académico</h6>
                  <div class="text-primary fw-bold">
                    <?php echo htmlspecialchars($estudiante['programa'] ?: 'No asignado'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center p-4 border rounded bg-light">
                  <i class="fas fa-layer-group text-info fa-2x mb-3"></i>
                  <h6 class="fw-bold">Ciclo Académico</h6>
                  <div class="text-info fw-bold fs-4">
                    <?php echo htmlspecialchars($estudiante['ciclo'] ?: 'N/A'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center p-4 border rounded bg-light">
                  <i class="fas fa-clock text-warning fa-2x mb-3"></i>
                  <h6 class="fw-bold">Turno</h6>
                  <div class="text-warning fw-bold">
                    <?php 
                    if ($estudiante['turno'] == 1) {
                        echo '<i class="fas fa-sun me-1"></i> Diurno';
                    } elseif ($estudiante['turno'] == 2) {
                        echo '<i class="fas fa-moon me-1"></i> Nocturno';
                    } else {
                        echo 'No asignado';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php else: ?>
    <!-- No se encontró estudiante vinculado -->
    <div class="row">
      <div class="col-12">
        <div class="alert alert-warning" role="alert">
          <i class="fas fa-unlink me-2"></i>
          <strong>Sin Vinculación:</strong> No se encontraron datos académicos asociados a tu DNI 
          <strong><?php echo isset($_SESSION['dni']) ? htmlspecialchars($_SESSION['dni']) : 'No disponible'; ?></strong>.
        </div>
        
        <div class="card border-info">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-question-circle me-2"></i>¿Qué Significa Esto?</h6>
          </div>
          <div class="card-body">
            <p>Tu usuario está creado correctamente, pero aún no se han registrado tus datos académicos en el sistema.</p>
            
            <h6 class="text-info">Para solucionarlo:</h6>
            <ol>
              <li>Contacta al <strong>Administrador</strong> o <strong>Docente</strong></li>
              <li>Proporciona tu DNI: <strong><?php echo isset($_SESSION['dni']) ? htmlspecialchars($_SESSION['dni']) : 'No disponible'; ?></strong></li>
              <li>Solicita que registren tus datos académicos (programa, ciclo, etc.)</li>
              <li>Una vez registrado, podrás ver toda tu información aquí</li>
            </ol>
            
            <div class="alert alert-light border-info mt-3">
              <i class="fas fa-info-circle text-info me-2"></i>
              La vinculación se hace automáticamente por tu DNI. No necesitas ninguna configuración adicional.
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Información Adicional -->
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card border-primary">
          <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-shield-check me-2"></i>Seguridad y Privacidad</h6>
          </div>
          <div class="card-body">
            <ul class="list-unstyled mb-0">
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Tus datos están protegidos y seguros</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Solo tú puedes ver tu información personal</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> La vinculación es automática y confiable</li>
            </ul>
          </div>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="card border-info">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-question-circle me-2"></i>¿Necesitas Ayuda?</h6>
          </div>
          <div class="card-body">
            <p class="mb-3">Si encuentras algún problema con tu información:</p>
            <div class="d-grid">
              <a href="index.php" class="btn btn-outline-info">
                <i class="fas fa-home me-2"></i>Volver al Inicio
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>