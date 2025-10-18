# Sistema de Gestión de Averías

Sistema web desarrollado en CodeIgniter 4 para la gestión de averías con actualizaciones en tiempo real mediante WebSockets.

## Descripción

Este sistema permite registrar, listar y gestionar averías de clientes con las siguientes características:

- ✅ Registro de nuevas averías con cliente y descripción del problema
- ✅ Fecha y hora automática del sistema
- ✅ Estado automático inicial como "pendiente"
- ✅ Listado de averías pendientes con confirmación SweetAlert
- ✅ Vista separada de averías solucionadas
- ✅ Actualizaciones en tiempo real con WebSockets
- ✅ Animaciones suaves y UX moderna
- ✅ Notificaciones automáticas entre vistas

## Requisitos del Sistema

- **PHP 8.0+**
- **Composer**
- **MySQL/MariaDB**
- **Servidor web** (Apache/Nginx) o **Laragon**
- **Extensiones PHP**: `php-sockets`, `php-json`, `php-mysqli`

## Instalación y Configuración

### 1. Clonar o Descargar el Proyecto

```bash
git clone [URL_DEL_REPOSITORIO]
cd chat
```

### 2. Instalar Dependencias

```bash
composer install
```

### 3. Configurar Base de Datos

#### Opción A: Usar script SQL incluido
```bash
# Importar el archivo SQL en MySQL
mysql -u root -p < app/Database/database.sql
```

#### Opción B: Configurar manualmente
1. Crear base de datos `WOWDB`
2. Ejecutar migraciones:
```bash
php spark migrate
```

### 4. Configurar Variables de Entorno

Editar `app/Config/Database.php` si es necesario:
```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'WOWDB',
```

### 5. Levantar el Proyecto

#### Paso 1: Iniciar Servidor Web
```bash
# Opción A: Servidor integrado de PHP
php spark serve

# Opción B: Si usas Laragon
# El proyecto ya estará disponible en http://localhost/chat
```

#### Paso 2: Iniciar Servidor WebSocket
```bash
# En una terminal separada
php server.php

# O usar el servidor dedicado (recomendado)
php websocket-server.php
```

## URLs del Sistema

- **Página Principal**: `http://chat.test/`
- **Listar Averías**: `http://chat.test/averias/listar`
- **Registrar Avería**: `http://chat.test/averias/registrar`
- **Ver Solucionadas**: `http://chat.test/averias/soluciones`
- **WebSocket**: `ws://chat.test`

## Flujo de Trabajo

### 1. Registrar Nueva Avería
1. Ir a "Nueva Avería"
2. Llenar formulario (Cliente y Problema)
3. Guardar → Aparece automáticamente en "Averías Pendientes"

### 2. Solucionar Avería
1. En "Averías Pendientes", clic en "Marcar Solucionado"
2. Confirmar en SweetAlert: "¿El problema fue solucionado?"
3. La avería desaparece de pendientes y aparece en "Solucionadas"

### 3. Tiempo Real
- Las actualizaciones se sincronizan automáticamente entre pestañas
- Indicador visual: 🟢 Tiempo Real Activo / 🔴 Reconectando...

## Estructura del Proyecto

```
chat/
├── app/
│   ├── Controllers/
│   │   └── Averias.php          # Controlador principal
│   ├── Models/
│   │   └── AveriasModel.php     # Modelo de datos
│   ├── Views/
│   │   └── averias/             # Vistas del sistema
│   ├── Libraries/
│   │   └── WebSocketClient.php  # Cliente WebSocket
│   ├── WebSocket/
│   │   └── AveriasWebSocket.php # Servidor WebSocket
│   └── Database/
│       ├── database.sql         # Script de base de datos
│       └── Migrations/          # Migraciones
├── server.php                   # Servidor WebSocket original
├── websocket-server.php         # Servidor WebSocket mejorado
└── README.md                    # Este archivo
```

## Solución de Problemas

### WebSocket no conecta
```bash
# Verificar que el puerto 8080 esté libre
netstat -an | findstr :8080

# Reiniciar servidor WebSocket
php websocket-server.php
```

### Error de base de datos
```bash
# Verificar conexión
php spark db:table averias

# Ejecutar migraciones
php spark migrate
```

### Problemas de permisos
```bash
# En Linux/Mac
chmod -R 755 writable/
```

## Tecnologías Utilizadas

- **Backend**: CodeIgniter 4, PHP 8+
- **Frontend**: Bootstrap 5, SweetAlert2, JavaScript ES6
- **WebSocket**: Ratchet/Pawl
- **Base de Datos**: MySQL/MariaDB
- **Tiempo Real**: WebSockets con heartbeat
- **Validación**: CSRF Protection, Server-side validation

## Características Técnicas

### Seguridad
- ✅ Protección CSRF en formularios
- ✅ Validación de datos server-side
- ✅ Escape de HTML para prevenir XSS
- ✅ Sanitización de entradas

### Performance
- ✅ Conexiones WebSocket persistentes
- ✅ Heartbeat para mantener conexiones vivas
- ✅ Reconexión automática con backoff exponencial
- ✅ Animaciones CSS optimizadas

### UX/UI
- ✅ Diseño responsive con Bootstrap 5
- ✅ Confirmaciones con SweetAlert2
- ✅ Indicadores de estado en tiempo real
- ✅ Animaciones suaves de transición
- ✅ Notificaciones no intrusivas

## Autor

Desarrollado como proyecto educativo para demostrar:
- Integración de WebSockets en CodeIgniter 4
- Actualizaciones en tiempo real
- Buenas prácticas de UX/UI
- Arquitectura MVC limpia
- Comunicación bidireccional mediante WebSockets

---

**¡Listo para usar!** 🚀 Sigue las instrucciones de instalación paso a paso y tendrás un sistema completo de gestión de averías con actualizaciones en tiempo real funcionando perfectamente.

