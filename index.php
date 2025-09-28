<?php 
include 'conexion.php';
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Sistema de Gestión - Inicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">
  <?php include 'navbar.php'; ?>
  
  <div class="container mt-5">
    <div class="row">
      <div class="col-12">
        <div class="jumbotron bg-primary text-white p-5 rounded mb-4">
          <h1 class="display-4">
            <i class="fas fa-graduation-cap"></i> Sistema de Gestión Educativa
          </h1>
          <p class="lead">Bienvenido al sistema de gestión de estudiantes y usuarios</p>
          
          <?php if (isset($_SESSION['usuario'])): ?>
            <hr class="my-4">
            <p>Hola, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>!</p>
            <p>Tu rol es: 
              <span class="badge bg-light text-dark fs-6">
                <?php 
                  $rol = $_SESSION['rol'];
                  switch($rol) {
                    case 1: echo 'Administrador - Acceso total al sistema'; break;
                    case 2: echo 'Docente - Gestión de estudiantes'; break;
                    case 3: echo 'Estudiante - Solo consulta'; break;
                    default: echo 'Usuario';
                  }
                ?>
              </span>
            </p>
          <?php else: ?>
            <hr class="my-4">
            <p>Para acceder a todas las funcionalidades, por favor inicia sesión</p>
            <a class="btn btn-light btn-lg" href="login.php" role="button">
              <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php if (isset($_SESSION['usuario'])): ?>
    <div class="row">
      <div class="col-12">
        <h3 class="mb-4">Accesos Rápidos</h3>
      </div>
      
      <?php if ($rol == 1 || $rol == 2): // Administrador o Docente ?>
      <div class="col-md-6 mb-3">
        <div class="card h-100">
          <div class="card-body text-center">
            <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
            <h5 class="card-title">Gestión de Estudiantes</h5>
            <p class="card-text">Administrar información de estudiantes, agregar nuevos registros y consultar datos.</p>
            <a href="estudiantes.php" class="btn btn-primary">
              <i class="fas fa-arrow-right"></i> Ir a Estudiantes
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>
      
      <?php if ($rol == 1): // Solo Administrador ?>
      <div class="col-md-6 mb-3">
        <div class="card h-100">
          <div class="card-body text-center">
            <i class="fas fa-users fa-3x text-success mb-3"></i>
            <h5 class="card-title">Gestión de Usuarios</h5>
            <p class="card-text">Administrar cuentas de usuario, roles y permisos del sistema.</p>
            <a href="usuarios.php" class="btn btn-success">
              <i class="fas fa-arrow-right"></i> Ir a Usuarios
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>
      
      <?php if ($rol == 3): // Estudiante ?>
      <div class="col-md-6 mb-3">
        <div class="card h-100 border-primary">
          <div class="card-body text-center">
            <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
            <h5 class="card-title">Mi Perfil Estudiantil</h5>
            <p class="card-text">Consulta tu información personal, datos académicos y estado de matrícula.</p>
            <a href="mi_perfil.php" class="btn btn-primary">
              <i class="fas fa-eye"></i> Ver Mi Perfil
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card h-100 border-info">
          <div class="card-body text-center">
            <i class="fas fa-search fa-3x text-info mb-3"></i>
            <h5 class="card-title">Consulta de Información</h5>
            <p class="card-text">Accede a información general del sistema educativo y programas académicos.</p>
            <div class="d-grid">
              <button class="btn btn-outline-info disabled">
                <i class="fas fa-info-circle"></i> Información General
              </button>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Sistema</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <h6><i class="fas fa-user-shield"></i> Administrador</h6>
                <p class="small">Acceso completo: usuarios, estudiantes, configuraciones</p>
              </div>
              <div class="col-md-4">
                <h6><i class="fas fa-chalkboard-teacher"></i> Docente</h6>
                <p class="small">Gestión de estudiantes: agregar, modificar, consultar</p>
              </div>
              <div class="col-md-4">
                <h6><i class="fas fa-user-graduate"></i> Estudiante</h6>
                <p class="small">Acceso a perfil personal, información académica y datos de matrícula</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>