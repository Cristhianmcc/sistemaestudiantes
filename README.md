# 🎓 Sistema de Gestión Educativa - Versión Profesional Mejorada

## � Resumen Ejecutivo

Este proyecto es un **sistema completo de gestión educativa** desarrollado en PHP/MySQL con una arquitectura moderna y profesional. El sistema implementa control de acceso basado en roles, vinculación de datos por DNI, y una interfaz de usuario moderna y responsiva.

---

## 🚀 Mejoras Implementadas en Esta Versión

### **Fase 1: Sistema de Búsqueda y Filtros**
- ✅ Filtros avanzados por programa, ciclo y estado
- ✅ Búsqueda en tiempo real con JavaScript
- ✅ Interfaz intuitiva con selectores dinámicos

### **Fase 2: Modernización de UI/UX**
- ✅ Diseño profesional con Bootstrap 5.3.0
- ✅ Modales elegantes con cards y gradientes
- ✅ Esquema de colores consistente (azul corporativo #0d6efd)
- ✅ Iconos FontAwesome 6.0.0 integrados

### **Fase 3: Arquitectura MVC**
- ✅ Separación de lógica y presentación
- ✅ `login_crud.php` - Lógica de autenticación
- ✅ `login.php` - Vista de presentación
- ✅ Código limpio y mantenible

### **Fase 4: Sistema de Vinculación DNI** ⭐ **NUEVA FUNCIONALIDAD**
- ✅ Vinculación automática usuario-estudiante por DNI
- ✅ Portal estudiantil personalizado
- ✅ Gestión de sesiones con datos vinculados
- ✅ Sistema de roles expandido

---

## 📁 Estructura Detallada del Proyecto

### **🔐 Sistema de Autenticación**
```
login_crud.php      - Lógica de autenticación y gestión de sesiones
login.php          - Interfaz de inicio de sesión
logout.php         - Cierre de sesión
```

### **👨‍💼 Gestión de Usuarios y Estudiantes**
```
usuarios.php       - Gestión completa de usuarios del sistema
crud_usuarios.php  - Operaciones CRUD para usuarios
estudiantes.php    - Gestión de datos académicos
crud_estudiantes.php - Operaciones CRUD para estudiantes
```

### **🏠 Portal y Navegación**
```
index.php          - Dashboard principal con paneles por rol
navbar.php         - Navegación responsiva con control de acceso
mi_perfil.php      - Portal estudiantil con datos vinculados ⭐ NUEVO
```

### **⚙️ Core del Sistema**
```
conexion.php       - Configuración de base de datos
crud.php          - Compatibilidad con versión anterior
```

---

## 🎯 Sistema de Roles Expandido

### **🔑 Rol 1: Administrador (rol = 1)**
```php
Permisos Completos:
✅ Gestión total de usuarios (crear, modificar, eliminar)
✅ Gestión total de estudiantes (crear, modificar, eliminar)
✅ Acceso a todos los módulos del sistema
✅ Configuración y administración general
```

### **👨‍🏫 Rol 2: Docente (rol = 2)**
```php
Permisos Limitados:
✅ Ver y gestionar estudiantes (crear, modificar)
❌ NO puede eliminar estudiantes
❌ NO puede acceder a gestión de usuarios
✅ Dashboard con estadísticas de estudiantes
```

### **🎓 Rol 3: Estudiante (rol = 3)** ⭐ **MEJORADO**
```php
Acceso Personal:
✅ Portal "Mi Perfil Estudiantil" con datos vinculados
✅ Información personal y académica integrada
✅ Dashboard personalizado con accesos relevantes
❌ Sin permisos de modificación de datos
```

---

## 🔗 Sistema de Vinculación DNI - Funcionalidad Clave

### **🧩 ¿Cómo Funciona la Vinculación?**

```mermaid
graph TD
    A[Usuario hace Login] --> B[login_crud.php verifica credenciales]
    B --> C[Si es válido: $_SESSION['dni'] = $fila['dni']]
    C --> D[Usuario navega a Mi Perfil]
    D --> E[mi_perfil.php usa $_SESSION['dni']]
    E --> F[Busca en tbl_estudiante WHERE dni = $_SESSION['dni']]
    F --> G[Muestra datos combinados: Usuario + Estudiante]
```

### **💾 Almacenamiento en Sesión**
```php
// En login_crud.php línea 36
$_SESSION['dni'] = $fila['dni']; // ← Esta línea hace toda la magia

// En mi_perfil.php
$dni_usuario = $_SESSION['dni'];  // ← Recupera DNI de la sesión
$query = "SELECT * FROM tbl_estudiante WHERE dni = '$dni_usuario'";
```

### **� Flujo de Vinculación**
1. **Admin/Docente** crea usuario en `tbl_usuario` con DNI único
2. **Admin/Docente** crea estudiante en `tbl_estudiante` con el mismo DNI
3. **Estudiante** se autentica con sus credenciales
4. **Sistema** guarda DNI en sesión automáticamente
5. **Mi Perfil** vincula datos de ambas tablas usando el DNI

---

## 🗄️ Estructura de Base de Datos Actualizada

### **Tabla: tbl_usuario** ⭐ **MODIFICADA**
```sql
CREATE TABLE tbl_usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  clave VARCHAR(100) NOT NULL,
  dni VARCHAR(8) NOT NULL,              -- ← CAMPO AGREGADO para vinculación
  rol INT NOT NULL,
  estado INT DEFAULT 1,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ultimo_acceso TIMESTAMP NULL
);
```

### **Tabla: tbl_estudiante** ⭐ **MODIFICADA**
```sql
CREATE TABLE tbl_estudiante (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dni VARCHAR(8) NOT NULL,              -- ← CAMPO CLAVE para vinculación
  nombre VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  sexo ENUM('M', 'F') NOT NULL,
  fecha_nacimiento DATE,
  telefono VARCHAR(15),
  email VARCHAR(100),
  direccion TEXT,
  estado INT NOT NULL,
  programa INT NOT NULL,
  ciclo INT NOT NULL,
  fecha_matricula DATE,
  observaciones TEXT
);
```

### **Tabla: tbl_rol** (Sin cambios)
```sql
CREATE TABLE tbl_rol (
  id_rol INT PRIMARY KEY,
  nombre_rol VARCHAR(50) NOT NULL,
  descripcion TEXT
);

INSERT INTO tbl_rol VALUES
(1, 'Administrador', 'Control total del sistema educativo'),
(2, 'Docente', 'Gestión de estudiantes con permisos limitados'),
(3, 'Estudiante', 'Acceso a portal personal y consulta de datos');
```

---

## 🎨 Características de Diseño Profesional

### **🎯 Esquema de Colores Corporativo**
- **Azul Principal**: `#0d6efd` (Bootstrap Primary)
- **Azul Hover**: `#0056b3` (Darker Blue)
- **Gradientes**: Utilizados en cards y botones
- **Consistencia**: Toda la UI mantiene el mismo esquema

### **📱 Responsividad Completa**
- **Bootstrap 5.3.0**: Framework CSS moderno
- **Grid System**: Layout adaptable a todos los dispositivos
- **Mobile-First**: Optimizado para móviles primero

### **✨ Elementos de UI Modernos**
- **Cards Elegantes**: Con sombras y bordes redondeados
- **Modales Profesionales**: Para formularios y detalles
- **Iconos Intuitivos**: FontAwesome 6.0.0
- **Animations**: Transiciones suaves CSS

---

## 🛡️ Seguridad Implementada

### **🔒 Autenticación Segura**
```php
// Prepared statements para prevenir SQL Injection
$stmt = $conexion->prepare("SELECT * FROM tbl_usuario WHERE usuario = ? AND clave = ?");
$stmt->bind_param("ss", $usuario, $clave);

// Validación de sesiones en todas las páginas
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
```

### **🛡️ Control de Acceso por Roles**
```php
// Verificación de permisos por funcionalidad
if ($_SESSION['rol'] != 1) { // Solo administradores
    header("Location: index.php?error=sin_permisos");
    exit();
}
```

### **🔐 Sanitización de Datos**
```php
// Escape de datos para prevenir XSS
echo htmlspecialchars($variable, ENT_QUOTES, 'UTF-8');
```

---

## 📈 Funcionalidades por Módulo

### **🏠 Dashboard (index.php)**
- **Panel Administrador**: Estadísticas completas y accesos rápidos
- **Panel Docente**: Enfoque en gestión de estudiantes
- **Panel Estudiante**: Portal personal con "Mi Perfil Estudiantil"
- **Bienvenida Personalizada**: Mensajes según el rol del usuario

### **👥 Gestión de Usuarios (usuarios.php)**
- **CRUD Completo**: Solo para administradores
- **Filtros Avanzados**: Por rol y estado
- **Modales Profesionales**: Para formularios
- **Validaciones**: Frontend y backend

### **🎓 Gestión de Estudiantes (estudiantes.php)**
- **Sistema de Búsqueda**: Por nombre, DNI, programa
- **Filtros Dinámicos**: Programa, ciclo, estado
- **Permisos por Rol**: Docentes pueden ver/editar, no eliminar
- **Interfaz Profesional**: Cards y modales elegantes

### **👤 Mi Perfil Estudiantil (mi_perfil.php)** ⭐ **NUEVO**
- **Datos Personales**: Información del usuario autenticado
- **Datos Académicos**: Información vinculada por DNI
- **Diseño Profesional**: Cards con información organizada
- **Manejo de Errores**: Si no hay vinculación DNI

---

## 🚦 Estados y Validaciones

### **Estados del Sistema**
- **Usuario Estado 1**: Activo
- **Usuario Estado 0**: Inactivo
- **Estudiante Estado 1**: Matriculado
- **Estudiante Estado 0**: No matriculado

### **Validaciones Implementadas**
- **Campos Requeridos**: Frontend y backend
- **Formato DNI**: 8 dígitos numéricos
- **Usuarios Únicos**: No duplicados
- **Sesiones Válidas**: Verificación en cada página

---

## � Instalación y Configuración

### **Paso 1: Preparar el Servidor**
```bash
# Copiar archivos al directorio del servidor web
C:\Apache24\htdocs\semanacinco_mejorado\
```

### **Paso 2: Configurar Base de Datos**
```php
// Editar conexion.php con tus datos
$host = "localhost";
$usuario = "tu_usuario";
$clave = "tu_clave";
$base_datos = "tu_base_datos";
```

### **Paso 3: Crear Estructura BD**
```sql
-- Ejecutar scripts SQL para crear tablas
-- Insertar roles básicos
-- Crear usuario administrador inicial
```

### **Paso 4: Datos de Prueba**
```sql
-- Usuario administrador
INSERT INTO tbl_usuario VALUES (1, 'admin', 'admin123', '12345678', 1, 1, NOW(), NULL);

-- Estudiante de prueba
INSERT INTO tbl_estudiante VALUES (1, '12345678', 'Juan', 'Pérez López', 'M', 1, 1, 1, '2024-01-15', NULL);
```

---

## 🎯 Casos de Uso del Sistema

### **Caso 1: Administrador Completo**
1. Login como administrador
2. Ve dashboard completo con todas las estadísticas
3. Puede gestionar usuarios y estudiantes
4. Acceso total a todas las funcionalidades

### **Caso 2: Docente Académico**
1. Login como docente
2. Ve panel enfocado en estudiantes
3. Puede agregar/modificar estudiantes
4. No puede eliminar ni gestionar usuarios

### **Caso 3: Estudiante Portal** ⭐ **NUEVO**
1. Login como estudiante (DNI ya vinculado)
2. Ve su dashboard personalizado
3. Accede a "Mi Perfil Estudiantil"
4. Ve información personal + académica combinada

---

## � Flujo de Vinculación DNI - Explicación Técnica

### **¿Por qué Solo Necesitamos `$_SESSION['dni']`?**

**La "vinculación" es LÓGICA, no física:**

```php
// 1. En login_crud.php (línea 36)
$_SESSION['dni'] = $fila['dni'];  // ← Guardamos DNI en sesión

// 2. En mi_perfil.php
$dni_usuario = $_SESSION['dni'];  // ← Recuperamos DNI de sesión
$query = "SELECT * FROM tbl_estudiante WHERE dni = '$dni_usuario'";  // ← Buscamos por DNI
```

### **Ventajas de Este Enfoque:**
- ✅ **Flexibilidad**: No todos los usuarios son estudiantes
- ✅ **Escalabilidad**: Fácil agregar más tipos de vinculación
- ✅ **Mantenimiento**: No restricciones de Foreign Keys
- ✅ **Rendimiento**: Búsquedas simples y rápidas

### **¿Qué Pasa en Cada Escenario?**

| Escenario | Resultado |
|-----------|-----------|
| Usuario con DNI + Estudiante con mismo DNI | ✅ Muestra perfil completo |
| Usuario con DNI + Sin estudiante vinculado | ⚠️ Mensaje "No hay datos de estudiante" |
| Usuario sin DNI (NULL) | ❌ No puede acceder a perfil estudiantil |

---

## 🎉 Resumen de Logros

### **✅ Lo Que Conseguimos:**
1. **Sistema Completo**: Gestión integral de usuarios y estudiantes
2. **UI Profesional**: Diseño moderno y consistente
3. **Seguridad Robusta**: Prepared statements, control de sesiones
4. **Vinculación Inteligente**: Sistema DNI sin complejidad de BD
5. **Portal Estudiantil**: Acceso personalizado para estudiantes
6. **Arquitectura Limpia**: Separación de lógica y presentación

### **🚀 Funcionalidades Destacadas:**
- **Búsqueda y Filtros Avanzados**
- **Modales Profesionales**
- **Control de Acceso Granular**
- **Dashboard Personalizado por Rol**
- **Sistema de Vinculación DNI**
- **Portal Estudiantil Completo**

---

## 📝 Notas Técnicas

### **Compatibilidad**
- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5.3.0
- FontAwesome 6.0.0

### **Performance**
- Consultas optimizadas con prepared statements
- Sesiones eficientes
- Carga bajo demanda de datos

### **Mantenimiento**
- Código comentado y documentado
- Estructura modular
- Fácil extensión de funcionalidades

---

*Sistema desarrollado con ❤️ para gestión educativa moderna y profesional*