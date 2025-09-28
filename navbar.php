<?php
if (!isset($_SESSION)) {
    session_start();
}
$rol = $_SESSION['rol'] ?? 0;
$usuario = $_SESSION['usuario'] ?? '';
$isLoggedIn = !empty($usuario);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-graduation-cap"></i> Sistema Estudiantes
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
            <i class="fas fa-home"></i> Inicio
          </a>
        </li>
        
        <?php if ($isLoggedIn): ?>
          <?php if ($rol == 1 || $rol == 2): // Administrador o Docente pueden ver estudiantes ?>
            <li class="nav-item">
              <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'estudiantes.php' ? 'active' : ''; ?>" href="estudiantes.php">
                <i class="fas fa-user-graduate"></i> Estudiantes
              </a>
            </li>
          <?php endif; ?>
          
          <?php if ($rol == 1): // Solo Administrador puede ver usuarios ?>
            <li class="nav-item">
              <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'usuarios.php' ? 'active' : ''; ?>" href="usuarios.php">
                <i class="fas fa-users"></i> Usuarios
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
      
      <ul class="navbar-nav">
        <?php if ($isLoggedIn): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user"></i> <?php echo htmlspecialchars($usuario); ?>
              <small class="badge bg-secondary">
                <?php 
                  switch($rol) {
                    case 1: echo 'Admin'; break;
                    case 2: echo 'Docente'; break;
                    case 3: echo 'Estudiante'; break;
                    default: echo 'Usuario';
                  }
                ?>
              </small>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <?php if ($rol == 3): // Solo para estudiantes ?>
                <li><a class="dropdown-item" href="mi_perfil.php"><i class="fas fa-user-graduate"></i> Mi Perfil Estudiantil</a></li>
                <li><hr class="dropdown-divider"></li>
              <?php endif; ?>
              <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>" href="login.php">
              <i class="fas fa-sign-in-alt"></i> Login
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>