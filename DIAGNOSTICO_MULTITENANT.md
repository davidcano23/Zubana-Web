# Diagnóstico técnico: conversión de Zubana BienRaíz a SaaS multi-tenant

> Informe de diagnóstico y plan. **No se ha modificado código.** Fecha: 15/06/2026.

---

## Aclaración previa importante (léela primero)

Recorrí todo el código (controladores, modelos, consultas SQL). Conviene corregir una premisa antes de empezar:

**El sistema actual NO gestiona clientes ni contratos.** No existe ninguna tabla, modelo ni consulta de `clientes` ni `contratos`. Lo que el sistema hace hoy es:

- Publicar un **catálogo de inmuebles** (casas, apartamentos, locales, lotes) con sus imágenes.
- Una **zona pública** donde los visitantes ven y filtran esas propiedades.
- Una **zona privada** con un único login (tabla `usuarios`) para crear/editar/eliminar propiedades.

Es decir, hoy es un **portal de publicación inmobiliaria de un solo dueño**, no un CRM. Esto cambia el alcance del SaaS: para "muchas inmobiliarias" lo que aíslas por ahora son **propiedades, imágenes y usuarios**. Si más adelante añades clientes/contratos, nacerán ya con `tenant_id` siguiendo el mismo patrón de este informe.

---

## (a) Resumen de la arquitectura actual

El proyecto es un **MVC artesanal en PHP** (sin framework), con autoload PSR-4 vía Composer. El flujo de una petición es:

**1. Punto de entrada y reescritura de URLs.**
Hay dos `.htaccess`. El de la raíz reenvía todo a `/public/`. El de `/public/` sirve archivos reales (css/js/img) y manda el resto a `public/index.php`, pasando la ruta como `PATH_INFO` y `url`:

```apache
# public/.htaccess
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^(.*)$ index.php?PATH_INFO=/$1&url=/$1 [QSA,L]
```

Por tanto, **el único front controller real es `public/index.php`**.

**2. Bootstrap.**
`public/index.php` carga `vendor/autoload.php` e `includes/app.php`. `app.php` hace tres cosas: incluye funciones, abre la conexión a base de datos y la inyecta en el modelo base:

```php
// includes/app.php
require 'config/database.php';
$db = conectarDB();
use Model\ActiveRecord;
ActiveRecord::setDB($db);   // <-- conexión global única (singleton de facto)
```

**3. Enrutado.**
`public/index.php` registra todas las rutas en un `Router` (`Router.php`). El router guarda las rutas en dos arrays asociativos (`rutasGET`, `rutasPOST`) indexados por la URL exacta, y resuelve con `comprobarRutas()`:

```php
// Router.php (resumen)
public function comprobarRutas(): void {
    session_start();
    $auth = $_SESSION['login'] ?? null;
    $rutas_protegidas = [ '/tipo-propiedad', '/propiedades/crear-casa', ... ];
    $urlActual = $_SERVER['PATH_INFO'] ?? '/';   // con fallback a REQUEST_URI
    $fn = $metodo === 'GET' ? ($this->rutasGET[$urlActual] ?? null)
                            : ($this->rutasPOST[$urlActual] ?? null);
    if (in_array($urlActual, $rutas_protegidas) && !$auth) { header('location: /'); exit; }
    if ($fn) call_user_func($fn, $this);
}
```

La protección de rutas es **una lista blanca codificada a mano** (`$rutas_protegidas`) comprobada contra `$_SESSION['login']`. No hay roles ni concepto de "a qué cuenta pertenece esta sesión".

**4. Controladores.**
Clases estáticas en `controllers/` (uno por tipo de propiedad: `PropiedadController`=casas/fincas, `ApartamentoController`, `LocalController`, `LotesController`; más `PaginaController` para la zona pública, `LoginController`, `ApiBusquedaController`). Reciben el `$router`, leen `$_POST`/`$_FILES`/`$_GET`, instancian modelos y renderizan vistas con `$router->render('vista', [datos])`.

**5. Modelos y acceso a datos.**
Todos heredan de `Model\ActiveRecord` (`models/ActiveRecord.php`), un mini-ORM. Cada modelo declara `static $tabla` y `static $columnasDB`. `ActiveRecord` centraliza **toda** la generación de SQL: `crear()`, `actualizar()`, `eliminar()`, `find()`, `todas()`, `where()`, `filtrar()`, `getPaginadas()`, `getRecomendadas()`, `get()`, `contar()` y `consultarSQL()`. Las consultas se construyen por **concatenación de strings** con `escape_string` (no usa *prepared statements*, salvo en `Admin::existeUsuario()` y en `ApiBusquedaController`).

**6. Login / sesión.**
`LoginController::login()` acepta peticiones AJAX (modal) o normales. Valida con `Model\Admin` (tabla `usuarios`), comprueba la contraseña con `password_verify` y abre sesión:

```php
// Admin::iniciarSesion()
session_regenerate_id(true);
$_SESSION['usuario'] = $this->email;
$_SESSION['login']   = true;   // <-- booleano global, sin tenant
```

`logout()` vacía `$_SESSION`. **La sesión no sabe a qué inmobiliaria pertenece el usuario.** Esa es la pieza central que falta para multi-tenant.

**Diagnóstico de la arquitectura:** es sencilla y, afortunadamente, **el acceso a datos está muy centralizado en `ActiveRecord`**. Eso es una gran ventaja: el aislamiento por tenant se puede inyectar mayoritariamente en un único archivo. Los puntos que se escapan de esa centralización (y por eso son los más peligrosos) son tres: `PaginaController` arma SQL crudo con `consultarSQL()`, `ApiBusquedaController` consulta tablas con SQL propio, y `find($id)` busca solo por `id`.

---

## (b) Inventario de tablas/modelos y archivos a modificar

### Datos de negocio (necesitan `tenant_id`)

| Tabla | Modelo | Rol | ¿Aislar? |
|---|---|---|---|
| `casa` | `models/Casa.php` | Casas / fincas / campestres | **Sí** |
| `apartamento` | `models/Apartamento.php` | Apartamentos / aparta-estudios | **Sí** |
| `local` | `models/Local.php` | Locales comerciales | **Sí** |
| `lotes` | `models/Lote.php` | Lotes | **Sí** |
| `imagenes_propiedad` | `models/ImagenCasa.php` | Galería de casas (`casa_id`) | **Sí**\* |
| `imagenes_propiedad_apartamento` | `models/ImagenApart.php` | Galería de apartamentos | **Sí**\* |
| `imagenes_propiedad_local` | `models/ImagenLocal.php` | Galería de locales | **Sí**\* |
| `imagenes_propiedad_lotes` | `models/ImagenLotes.php` | Galería de lotes | **Sí**\* |
| `usuarios` | `models/Admin.php` | Login del panel | **Sí** (cada usuario pertenece a una inmobiliaria) |

\* Las tablas de imágenes ya están ligadas a su propiedad por clave foránea (`casa_id`, etc.). Heredan el tenant de la propiedad. Aun así conviene **añadirles `tenant_id` de forma redundante** (desnormalizado) para poder filtrarlas directamente y blindar consultas como `ImagenCasa::todas()` en `PaginaController`, que hoy traen imágenes de **todos** los registros sin filtro.

### Configuración / infraestructura global (NO llevan `tenant_id` propio, son globales o definen el tenant)

| Elemento | Archivo | Por qué es global |
|---|---|---|
| Conexión a BD | `includes/config/database.php` | Infraestructura. Será compartida por todos los tenants. |
| Router y rutas | `Router.php`, `public/index.php` | Lógica de aplicación común. |
| Funciones / constantes | `includes/funciones.php` (`CARPETA_IMAGENES`) | Globales. La carpeta de imágenes pasará a segmentarse por tenant (ver plan). |
| Vistas | `views/**` | Plantillas comunes. |
| Tablas catálogo (tipos válidos, modalidades) | Hoy son arrays en `PaginaController` | Listas fijas del dominio, iguales para todos. |

### Tablas NUEVAS que habrá que crear (no existen aún)

| Tabla nueva | Rol |
|---|---|
| `tenants` (o `inmobiliarias`) | Una fila por inmobiliaria: nombre, subdominio, estado, plan, fechas. **Esta es global**, define los inquilinos. |
| `planes` | Catálogo de planes (precio, límites). Global. |
| `suscripciones` | Vincula `tenant_id` ↔ plan ↔ estado de pago. |

---

## (c) Puntos de riesgo de filtrado de datos (lo más crítico)

Aquí está el corazón de la seguridad. Localicé **todas** las consultas. Las agrupo por riesgo.

### Riesgo CRÍTICO — fugas entre inquilinos (IDOR / listados sin filtro)

**1. `ActiveRecord::find($id)`** — `models/ActiveRecord.php`
```php
public static function find(int $id): ?object {
    $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
    ...
}
```
Busca **solo por `id`**. En multi-tenant, la inmobiliaria A podría abrir `/propiedades/actualizar-casa?id=37` y editar/ver una propiedad de la inmobiliaria B. Es la fuga clásica (IDOR). **Hay que añadir `AND tenant_id = :tenant`.** Se usa en `PropiedadController`, `ApartamentoController`, `FincaController`, `LocalController`, `LotesController` y `PaginaController`.

**2. Listados sin filtro:** `todas()`, `get()`, `getPaginadas()`, `getRecomendadas()`, `where()`, `filtrar()`, `contar()` en `ActiveRecord`. Todas hacen `SELECT ... FROM <tabla>` sin condición de tenant → devuelven datos de **todos**.

**3. SQL crudo en `PaginaController::index()`** — `controllers/PaginaController.php` (líneas ~153-156):
```php
$casas = Casa::consultarSQL("SELECT * FROM casa {$whereCasa} ORDER BY id DESC");
// idem apartamento, local, lotes
```
Estas consultas **se construyen a mano** y se pasan a `consultarSQL()`, que **no aplica ningún filtro**. Aunque centralices el filtro en `ActiveRecord`, estas líneas lo saltan. Hay que inyectar `tenant_id` dentro del `WHERE` construido aquí.

**4. `construirMapaImagenes()`** — `PaginaController` (líneas ~18-33): hace `ImagenCasa::todas()`, `ImagenApart::todas()`, etc. → trae **todas** las imágenes de todos los tenants. Hay que filtrarlas por tenant.

**5. `ApiBusquedaController::buscar()`** — `controllers/ApiBusquedaController.php`: abre su **propia** conexión (`conectarDB()`) y consulta `['casa','apartamento','local','lotes']` con SQL propio (usa *prepared statements*, bien hecho contra inyección, pero **sin tenant**). El autocompletado del buscador filtraría sugerencias de todas las inmobiliarias. Hay que añadir `AND tenant_id = ?`.

### Riesgo de escritura sin tenant

**6. `crear()` y `actualizar()`** en `ActiveRecord`: el `INSERT` toma las columnas de `$columnasDB`; si `tenant_id` no se setea, las propiedades nuevas nacerían sin dueño. El `UPDATE` filtra solo `WHERE id = ...` → una inmobiliaria podría sobrescribir un registro de otra. Hay que (a) setear `tenant_id` automáticamente al crear y (b) añadir `AND tenant_id = ...` al `UPDATE`/`DELETE`.

**7. `eliminar()` / `eliminarImg()`** en `ActiveRecord`: `DELETE ... WHERE id = ...` sin tenant. Mismo riesgo que el `UPDATE`.

### ¿Centralizar o no?

**Sí, conviene centralizar en el modelo base `ActiveRecord`**, porque ya es el cuello de botella de casi todo el SQL. La estrategia recomendada:

1. Dar a `ActiveRecord` un **contexto de tenant** estático (`self::$tenantId`), seteado una sola vez en el bootstrap (`includes/app.php`) tras resolver el tenant.
2. Reescribir los métodos de lectura/escritura para que inyecten `tenant_id` automáticamente.
3. Para las **tres excepciones** que arman SQL fuera de `ActiveRecord` (`PaginaController`, `ApiBusquedaController`), hay que tocarlas a mano porque no pasan por el patrón estándar. Idealmente, refactorizarlas para que usen un método centralizado (p. ej. añadir un helper `scopeTenant(string $sql)` o un `whereTenant()` en `ActiveRecord`) en lugar de concatenar SQL suelto.

La estructura **facilita** la centralización (mini-ORM con un único punto de generación de SQL). Lo único que la dificulta son esas tres excepciones de SQL crudo, que son finitas y localizables.

---

## (d) Decisión de base de datos: estrategia de multi-tenancy recomendada

**Recomendación: base de datos compartida + columna `tenant_id` (modelo de "discriminador").**

Comparativa para tu caso concreto:

| Estrategia | Aislamiento | Coste/operación | Encaje con tu código |
|---|---|---|---|
| **BD compartida + `tenant_id`** ✅ | Lógico (en el WHERE) | El más barato; 1 sola BD | **Excelente.** Solo una conexión global ya existe (`ActiveRecord::setDB`), y el SQL está centralizado. Cambio mínimo. |
| BD por inquilino | Físico (fuerte) | Caro; Hostinger compartido limita nº de BDs; provisión por cliente | Malo. Habría que abrir conexión dinámica por petición y migrar el esquema N veces. Tu `conectarDB()` es estático y hardcodeado. |
| Esquema por inquilino (mismo MySQL, distinto `database`) | Medio | Medio | Regular. MySQL no tiene "schemas" como Postgres; serían BDs separadas → mismos problemas que la opción 2. |

**Justificación:**
- Tu app ya usa **una sola conexión global** inyectada en `ActiveRecord`. La opción de BD compartida es la única que aprovecha eso sin reescribir el bootstrap.
- El SQL está **centralizado**, así que inyectar `tenant_id` es de bajo coste y bajo riesgo.
- En **Hostinger (hosting compartido)**, crear una BD por cada inmobiliaria es inviable a escala (límites de BDs, sin automatización de provisión).
- Para un SaaS inmobiliario de PYMES, el aislamiento lógico bien implementado (filtro obligatorio + índices por `tenant_id`) es el estándar de la industria y el más rentable.

**Mitigación del riesgo del aislamiento lógico:** como el aislamiento es "solo" un `WHERE`, un olvido = fuga de datos. Por eso el filtro debe vivir **en el modelo base** (no repetido en cada controlador) y debes añadir un **índice compuesto `(tenant_id, id)`** en cada tabla de negocio para rendimiento.

> Si en el futuro fichas clientes "enterprise" que exijan aislamiento físico, el patrón `tenant_id` permite migrar tenants concretos a su propia BD sin rehacer la app. Empezar compartido y endurecer después es lo correcto.

---

## (e) Plan de migración por fases (priorizado)

Orden deliberado: **primero el aislamiento de datos (seguridad), luego resolución de tenant, después self-service, planes/límites y por último pagos.** No tiene sentido abrir registro de inmobiliarias si los datos aún se filtran entre ellas.

### FASE 0 — Decisiones y preparación (antes de tocar código)
Resolver las "decisiones técnicas clave" de la sección siguiente. Hacer **copia de seguridad** de la BD de Hostinger.

### FASE 1 — Esquema de base de datos (cimientos)
**Qué:** crear la tabla `tenants` y añadir `tenant_id` a todas las tablas de negocio; migrar los datos actuales a un tenant inicial.

**Por qué:** sin la columna no se puede filtrar nada. Hacerlo primero y con *backfill* evita romper el sitio actual (todos los datos existentes pasan a ser del "tenant 1").

```sql
-- 1. Tabla de inquilinos (global)
CREATE TABLE tenants (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  nombre       VARCHAR(150) NOT NULL,
  subdominio   VARCHAR(63)  NOT NULL UNIQUE,   -- p.ej. 'zubana' -> zubana.tudominio.com
  estado       ENUM('activo','suspendido','prueba') NOT NULL DEFAULT 'prueba',
  plan_id      INT NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Crear el tenant para los datos actuales
INSERT INTO tenants (nombre, subdominio, estado) VALUES ('Zubana BienRaíz', 'zubana', 'activo');

-- 3. Añadir tenant_id a cada tabla de negocio (repetir por tabla)
ALTER TABLE casa            ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;
ALTER TABLE apartamento     ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;
ALTER TABLE local           ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;
ALTER TABLE lotes           ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;
ALTER TABLE usuarios        ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;
ALTER TABLE imagenes_propiedad             ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;
ALTER TABLE imagenes_propiedad_apartamento ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;
ALTER TABLE imagenes_propiedad_local       ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;
ALTER TABLE imagenes_propiedad_lotes       ADD COLUMN tenant_id INT NOT NULL DEFAULT 1 AFTER id;

-- 4. Índices (rendimiento y aislamiento)
ALTER TABLE casa        ADD INDEX idx_tenant (tenant_id);
-- ... idem en cada tabla

-- 5. Quitar el DEFAULT 1 después del backfill, para que futuros INSERT exijan tenant_id explícito
ALTER TABLE casa ALTER COLUMN tenant_id DROP DEFAULT;
-- ... idem
```

**Procedimiento:** el `DEFAULT 1` temporal hace que los datos existentes queden asignados al tenant actual sin escribir un solo `UPDATE`. Una vez migrado, se quita el default para forzar que el código siempre indique el tenant (así un bug de "olvidé el tenant" falla en vez de asignar silenciosamente al tenant 1).

### FASE 2 — Aislamiento de datos en el código (lo más crítico)
**Archivos:** `models/ActiveRecord.php` (núcleo), `models/Admin.php`, `controllers/PaginaController.php`, `controllers/ApiBusquedaController.php`.

**2.1. Contexto de tenant en el modelo base.** Añadir a `ActiveRecord`:
```php
protected static $tenantId = null;

public static function setTenant(?int $tenantId): void {
    self::$tenantId = $tenantId;
}
protected static function tid(): int {
    if (self::$tenantId === null) {
        throw new \RuntimeException('Tenant no resuelto'); // falla seguro: nunca consultar sin tenant
    }
    return self::$tenantId;
}
```
**Por qué `throw`:** si por un bug se ejecuta una consulta antes de resolver el tenant, preferimos un error visible a una fuga silenciosa de datos.

**2.2. Inyectar el filtro en lecturas.** Ejemplo con `find()` y `todas()`:
```php
public static function find(int $id): ?object {
    $t = self::tid();
    $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id} AND tenant_id = {$t} LIMIT 1";
    return array_shift(self::consultarSQL($query));
}
public static function todas(): array {
    return self::consultarSQL("SELECT * FROM " . static::$tabla . " WHERE tenant_id = " . self::tid());
}
```
Hacer lo mismo en `where()`, `filtrar()`, `getPaginadas()`, `getRecomendadas()`, `get()`, `contar()`.

**2.3. Inyectar el tenant en escrituras.** En `crear()`, forzar `tenant_id` antes del `INSERT`:
```php
public function crear(): void {
    $atributos = $this->sanetizarAtributos();
    $atributos['tenant_id'] = self::tid();   // se añade siempre, lo declare o no el modelo
    ... // resto igual
}
```
En `actualizar()`, `eliminar()`, `eliminarImg()`: añadir `AND tenant_id = <tid>` al `WHERE`. Así una inmobiliaria nunca puede modificar/borrar registros de otra aunque adivine el `id`.

**2.4. Añadir `tenant_id` a `$columnasDB`** en cada modelo (`Casa`, `Apartamento`, `Local`, `Lote`, y los `Imagen*`) y declarar la propiedad pública `$tenant_id`, para que el ORM la reconozca.

**2.5. Refactor de `PaginaController`.** Inyectar `tenant_id` en los `WHERE` que arma a mano (líneas ~153-156) y filtrar `construirMapaImagenes()`. Lo más limpio: pasar el filtro como condición base:
```php
$tid = (int) (self::$tenantPublico); // tenant resuelto por subdominio (ver Fase 3)
$condBase[] = "tenant_id = {$tid}";
```

**2.6. Refactor de `ApiBusquedaController`.** Añadir `AND tenant_id = ?` a la consulta preparada de `sugerenciasTabla()` y pasar el tenant por `bind_param`.

**2.7. Login con tenant.** En `Admin::existeUsuario()` añadir `AND tenant_id = ?`, y en `iniciarSesion()` guardar el tenant en sesión:
```php
$_SESSION['tenant_id'] = $usuario->tenant_id;
```

**Verificación obligatoria de la fase:** crear un segundo tenant de prueba con datos propios y comprobar, con dos sesiones distintas, que ninguna ve datos de la otra (incluido probar `?id=` de la otra inmobiliaria → debe dar "no encontrado").

### FASE 3 — Resolución de tenant
Hay **dos contextos** distintos y conviene resolverlos diferente:

- **Zona privada (panel):** el tenant sale de la **sesión** (`$_SESSION['tenant_id']`, fijado en login). Simple y seguro.
- **Zona pública (catálogo que ven los visitantes):** el visitante no tiene sesión, así que el tenant se resuelve por **subdominio** (`zubana.tudominio.com`) o dominio propio:

```php
// includes/app.php, tras conectar la BD
$host = $_SERVER['HTTP_HOST'] ?? '';
$sub  = explode('.', $host)[0];          // 'zubana'
$tenant = Tenant::porSubdominio($sub);   // SELECT * FROM tenants WHERE subdominio = ?
if (!$tenant) { http_response_code(404); exit('Inmobiliaria no encontrada'); }
ActiveRecord::setTenant($tenant->id);
```

**Por qué subdominio para lo público:** cada inmobiliaria necesita su propia URL pública para compartir con sus clientes; el subdominio identifica el tenant sin login. En Hostinger esto requiere un **subdominio comodín** (`*.tudominio.com`) apuntando a la app.

**Archivos:** `includes/app.php` (resolución central), nuevo `models/Tenant.php`, y ajustar `Router::comprobarRutas()` para tomar el tenant de sesión en rutas privadas.

### FASE 4 — Registro self-service
Permitir que una inmobiliaria se dé de alta sola.

**Qué construir:**
- Vista y ruta `/registro` (GET/POST) y un `RegistroController`.
- Al registrarse: crear fila en `tenants` (con su `subdominio`), crear el primer `usuario` admin de ese tenant (con `tenant_id`), arrancar en estado `prueba`.
- Validar unicidad del subdominio y formato.
- Segmentar el almacenamiento de imágenes por tenant: hoy `CARPETA_IMAGENES` es global; conviene `imagenes/<tenant_id>/...` para evitar colisiones y facilitar límites de cuota. **Archivos:** `includes/funciones.php` (constante/función de carpeta), y los controladores que guardan imágenes (`PropiedadController`, `ApartamentoController`, etc.).

### FASE 5 — Planes y límites
**Qué:** tablas `planes` y `suscripciones`; aplicar límites (p. ej. máximo de propiedades por plan).

**Dónde se aplica el límite:** en los controladores `crear*` antes de guardar:
```php
if (Casa::contar() + Apartamento::contar() + ... >= $plan->max_propiedades) {
    $errores[] = 'Has alcanzado el límite de tu plan. Mejora tu suscripción.';
}
```
(Recuerda: tras la Fase 2, `contar()` ya filtra por tenant, así que esto cuenta solo las propiedades de esa inmobiliaria.)

### FASE 6 — Pagos / suscripción mensual
**Qué:** integrar pasarela y vincular el estado de pago al `estado` del tenant.

**Recomendación de pasarela:** dado que es Colombia (precios en COP, propietarios/contacto locales), evaluar **Mercado Pago** o **Wompi/PayU** para cobros locales recurrentes, o **Stripe** si vas a cobrar internacionalmente. *(Conviene verificar disponibilidad y condiciones actuales de cada una antes de decidir.)*

**Mecánica:** webhook de la pasarela → al confirmarse el pago, `tenants.estado = 'activo'`; si falla/cancela, `'suspendido'`. En `app.php`, tras resolver el tenant, si está suspendido, bloquear el panel y mostrar aviso de pago. **Importante:** los pagos los ejecuta siempre el usuario en la pasarela; la app solo refleja el estado.

---

## (f) Decisiones técnicas clave que debes tomar ANTES de empezar

1. **Nombre del campo discriminador.** Sugiero `tenant_id` (estándar). Una vez elegido, no cambiarlo.

2. **¿Subdominio, dominio propio, o ambos para la zona pública?** Subdominio (`zubana.tudominio.com`) es lo más simple para empezar; dominios propios (la inmobiliaria usa `www.sumarca.com`) son un plus comercial pero requieren gestión de DNS/SSL por cliente. Recomiendo subdominio en V1, dominio propio como mejora posterior. *(Requiere subdominio comodín en Hostinger; conviene confirmar que tu plan lo permite.)*

3. **¿Un usuario pertenece a una sola inmobiliaria o puede operar varias?** Lo simple y habitual: **un usuario = un tenant** (columna `tenant_id` en `usuarios`). Multi-tenant por usuario complica login y sesión; no lo recomiendo en V1.

4. **Estrategia de almacenamiento de imágenes.** ¿Carpeta por tenant (`imagenes/<tenant_id>/`)? Recomendado, por aislamiento y cuotas. Implica migrar las imágenes actuales a `imagenes/1/`.

5. **Seguridad del SQL.** Hoy las escrituras usan concatenación con `escape_string`, no *prepared statements*. Al tocar `ActiveRecord` para el tenant, es el momento ideal para **migrar a consultas preparadas**. Decisión: ¿lo haces ahora (más trabajo, más seguro) o lo dejas para después? Recomiendo al menos parametrizar las consultas que reciben entrada de usuario.

6. **Comportamiento ante "tenant no resuelto".** Confirmar que el sistema debe **fallar cerrado** (error/404) y nunca devolver datos sin filtro. (Así está propuesto con el `throw` en `tid()`.)

7. **Datos actuales.** Confirmar que todos los registros existentes pertenecen a "Zubana BienRaíz" y se asignan al `tenant_id = 1` en el *backfill*.

8. **Roadmap de clientes/contratos.** Como hoy no existen, decidir si entran en el alcance del SaaS (cambiaría de "portal" a "CRM") o se dejan fuera de V1. Afecta el modelo de datos y el precio.

---

## Resumen ejecutivo del orden de trabajo

1. **Fase 0–1:** backup + esquema (`tenants`, `tenant_id`, índices, backfill).
2. **Fase 2 (crítica):** aislar datos en `ActiveRecord` + las 3 excepciones de SQL crudo + login con tenant. **Verificar con 2 tenants de prueba.**
3. **Fase 3:** resolver tenant (sesión en privado, subdominio en público).
4. **Fase 4:** registro self-service + imágenes por tenant.
5. **Fase 5:** planes y límites.
6. **Fase 6:** pasarela de pago y estados de suscripción.

El mayor activo de tu código es que **el acceso a datos está centralizado en `ActiveRecord`**: el 80% del aislamiento se logra en un solo archivo. El mayor riesgo son las **tres excepciones** que arman SQL fuera de ese patrón (`PaginaController`, `ApiBusquedaController`, y `find()` por-id), que hay que tratar explícitamente.
