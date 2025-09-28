<?php
// Iniciar sesión para verificar si ya está logueado
session_start();

// Si ya está logueado, redirigir al índice
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistema Educativo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 60px 0 20px 0;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(15px);
      border-radius: 16px;
      box-shadow: 0 15px 35px rgba(13, 110, 253, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.2);
      max-width: 420px;
      width: 100%;
    }

    .login-header {
      background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
      color: white;
      border-radius: 16px 16px 0 0;
      padding: 1.5rem 2rem;
    }

    .btn-login {
      background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
      border: none;
      transition: all 0.3s ease;
      padding: 0.75rem 1rem;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
    }

    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .navbar-custom {
      background: rgba(13, 110, 253, 0.95);
      backdrop-filter: blur(10px);
    }

    .login-body {
      padding: 1.5rem 2rem;
    }

    .role-section {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      border-radius: 12px;
      padding: 1rem;
      margin-top: 1rem;
    }

    .role-item {
      text-align: center;
      padding: 0.5rem;
    }

    .form-control {
      padding: 0.75rem;
      border-radius: 8px;
    }

    @media (max-height: 700px) {
      body { padding: 80px 0 10px 0; }
      .login-header { padding: 1rem 1.5rem; }
      .login-body { padding: 1rem 1.5rem; }
      .role-section { padding: 0.75rem; margin-top: 0.5rem; }
    }
  </style>
</head>
<body>
  <!-- Navbar simple -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-graduation-cap"></i> Sistema Educativo
      </a>
      <a class="btn btn-outline-light btn-sm" href="index.php">
        <i class="fas fa-home"></i> Volver al Inicio
      </a>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
      <div class="col-11 col-sm-8 col-md-6 col-lg-4">
        <div class="card login-card border-0 mx-auto">
          <div class="card-header login-header text-center">
            <h4 class="mb-1">
              <i class="fas fa-graduation-cap me-2"></i>
              Sistema Educativo
            </h4>
            <p class="mb-0 opacity-75 small">Ingresa tus credenciales para continuar</p>
          </div>
          
          <div class="login-body">
            <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-1"></i>
                <strong>Error:</strong> Credenciales incorrectas.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'empty_fields'): ?>
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i>
                <strong>Atención:</strong> Todos los campos son obligatorios.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>

            <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check me-1"></i>
                <strong>Éxito:</strong> Sesión cerrada correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>

            <form action="login_crud.php" method="POST">
              <div class="mb-3">
                <label for="usuario" class="form-label fw-bold">
                  <i class="fas fa-user text-primary me-1"></i> Usuario
                </label>
                <input type="text" 
                       class="form-control" 
                       id="usuario" 
                       name="usuario" 
                       required 
                       placeholder="Ingresa tu usuario"
                       autocomplete="username">
              </div>
              
              <div class="mb-3">
                <label for="clave" class="form-label fw-bold">
                  <i class="fas fa-lock text-primary me-1"></i> Contraseña
                </label>
                <input type="password" 
                       class="form-control" 
                       id="clave" 
                       name="clave" 
                       required 
                       placeholder="Ingresa tu contraseña"
                       autocomplete="current-password">
              </div>
              
              <button type="submit" class="btn btn-login btn-lg w-100 text-white mb-3">
                <i class="fas fa-sign-in-alt me-2"></i>
                Iniciar Sesión
              </button>
            </form>

            <!-- Indicadores de roles compactos -->
            <div class="role-section">
              <div class="row text-center">
                <div class="col-4">
                  <div class="role-item">
                    <i class="fas fa-user-shield text-danger fa-lg mb-1"></i>
                    <div class="small fw-bold">Admin</div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="role-item">
                    <i class="fas fa-chalkboard-teacher text-warning fa-lg mb-1"></i>
                    <div class="small fw-bold">Docente</div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="role-item">
                    <i class="fas fa-user-graduate text-primary fa-lg mb-1"></i>
                    <div class="small fw-bold">Estudiante</div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="text-center mt-2">
              <small class="text-muted">
                <i class="fas fa-shield-check text-success me-1"></i>
                Sistema Seguro v2.0
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
      var alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>
</body>
</html>