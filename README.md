# Finalidad  
El objetivo de este proyecto es simular un entorno dedicado a la gestión de alumnos y centros educativos, permitiendo asignar matrículas a los alumnos que relacionen centros y las titulaciones impartidas en ellos.

---

# Estructura del proyecto  

Se gestionan **dos ramas** principales:

## Rama `admin` (ROLE_ADMIN)  
Funciones disponibles:  
- ✅ Listar, crear, editar y eliminar usuarios (admin o alumno)  
- ✅ Gestionar titulaciones: nombre, precio, número de horas  
- ✅ Gestionar centros educativos: nombre y dirección  
- ✅ Asignar titulaciones a centros  
- ✅ Gestionar alumnos y matrículas:  
  - Ver todos los usuarios con rol alumno  
  - Asignar matrícula: centro + titulación + fechas (inicio/fin)  
  - Consultar historial de matrículas  

## Rama `alumno` (ROLE_ALUMNO)  
Funciones disponibles:  
- 📄 Ver matrículas activas (fecha actual dentro del rango)  
- 🕰️ Ver matrículas pasadas (fuera del rango)  
- ✅ Gestión de tareas por matrícula (lista To-Do):  
  - Crear, completar, editar o eliminar tareas  

---

# Tablas de funcionalidades  

A continuación se incluyen tablas que organizan de forma clara las funcionalidades por rol y las entidades con sus campos principales.

## Funcionalidades por rol

| Rol         | Panel             | Funcionalidades principales                                                                                                                                    |
|-------------|-------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| ROLE_ADMIN  | Administración    | Listar/crear/editar/eliminar usuarios; gestionar titulaciones (nombre, precio, horas); gestionar centros (nombre, dirección); asignar titulaciones a centros; gestionar matrículas (asignar, ver historial). |
| ROLE_ALUMNO | Alumno            | Ver matrículas activas y pasadas; gestionar tareas (crear, completar, editar, eliminar).                                                                        |

## Entidades y campos principales

| Entidad     | Campos clave                                                                      |
|-------------|-----------------------------------------------------------------------------------|
| Usuario     | id, email, contraseña, rol, activo                                                |
| Alumno      | (usa Usuario con rol ROLE_ALUMNO)                                                 |
| Titulación  | id, nombre, precio, horas, activo                                                 |
| Centro      | id, nombre, dirección, activo                                                     |
| Matrícula   | id, alumno (Usuario), centro, titulación, fechaInicio, fechaFin, activo           |
| Tarea       | id, matrícula, descripción, estado (completada/incompleta), fechaCreación, activo  |

---

# Eliminación lógica y estado de las entidades  

Las entidades **no se eliminan** físicamente, sino que se marcan como **inactivas** con un campo `activo` (booleano).  
Esto permite mantener historial y recuperar información si se requiere.

> [!NOTE]  
> Los administradores pueden ver **todos** los registros, incluyendo los desactivados, para su gestión completa.

- Al desactivar una titulación, se desactivan **todas** las matrículas asociadas.  
- Lo mismo ocurre con los centros.  
- Un centro desactivado **no** aparece al crear nuevas matrículas;  
  pero una titulación inactiva **sí** puede seleccionarse (para reactivarla luego).

---

# Dinamismo con AJAX  

La aplicación es **totalmente dinámica** con llamadas AJAX; por ejemplo:  
- Al crear o editar una matrícula:  
  1. Seleccionas un centro.  
  2. Se cargan automáticamente las titulaciones disponibles en ese centro.  
Esto evita al usuario buscar manualmente entre todas las titulaciones existentes.

---

# Organización interna y modales  

La estructura de carpetas sigue la convención de Symfony, con la adición de:  
- Carpeta `modals/` que almacena las modales de todo el proyecto.

> [!WARNING]  
> Todas las modales se cargan vía `fetch`. Esto también aplica al manejo de rutas del proyecto.

---

# Controlador de usuarios y redirección  

El controlador de usuarios gestiona vistas tanto para administradores como para alumnos.  
Tras el login, se redirige a `/afterlogin`, que detecta el rol:  
- Si ROLE_ALUMNO → se envía al template de alumno.  
- Si ROLE_ADMIN → se envía al panel de administración.  
Así se utilizan plantillas Twig distintas según el rol.

---

# Relaciones entre entidades  

Las relaciones se implementan con los métodos generados por Symfony mediante `make:entity`.  
A continuación un esquema de relaciones:

<details>
<summary>📊 Esquema de relaciones</summary>

- **Usuario/Alumno** 1––* **Matrícula** *––1 **Centro**  
- **Usuario/Alumno** 1––* **Matrícula** *––1 **Titulación**  
- **Matrícula** 1––* **Tarea**

</details>

---

# Estilos  

Todo el proyecto está estilado con **Bootstrap 5** para facilitar el diseño y la coherencia de los componentes visuales.

---

# Despliegue local  

## Instalación local

```bash
# 1. Clonar el repositorio
git clone <url-del-repo>
cd <directorio>

# 2. Configurar entorno
# Editar .env y .env.local: DATABASE_URL, APP_ENV=dev

# 3. Crear base de datos y ejecutar migraciones
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 4. Ejecutar servidor
symfony server:start
# o bien:
php -S 127.0.0.1:8000 -t public
```

> [!IMPORTANT]
> Una vez desplegado el proyecto necesitas crear nuestro primer usuario administrador con el comando :  php bin/console app:mkuseradmin "correo_del_user" "password"

