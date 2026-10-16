#  MillennialLife

> **Plataforma web integral para la gestión de finanzas, servicios recurrentes, salud y expedientes digitales para la vida adulta.**

---

##  Acerca del Proyecto

**MillennialLife** nace para resolver la sobrecarga administrativa del adulto joven (*adulting*). Centraliza en un solo lugar el monitoreo y liquidación quincenal de recibos de servicios del hogar, trámites anuales obligatorios, expediente médico con recetas adjuntas y analítica de gastos con reportes ejecutivos descargables.

---

##  Características Principales

* ** Gestión de Servicios y Pagos:** Monitoreo de servicios recurrentes (Luz, Agua, Gas, Internet) con fechas de corte y liquidación integrada mediante pasarela de pagos con **Stripe**.
* ** Sistema Polimórfico de Documentos:** Arquitectura con relaciones polimórficas (`morphToMany`) que permite asociar comprobantes, facturas y recetas médicas a cualquier entidad (`Service`, `MedicalAppointment`) sin duplicidad de tablas.
* ** Integridad de Datos:** Implementación de borrado lógico (**SoftDeletes**) en los módulos críticos para preservar historial y trazabilidad.
* ** Analítica y Reportes Estadísticos en PDF:** Generación de reportes ejecutivos en PDF mediante **DomPDF** y la API de **QuickChart**, graficando tendencias históricas de consumo y calculando variaciones porcentuales (incrementos y ahorros).
* ** Servidor Multimedia y Assets:** Módulo de almacenamiento y streaming seguro para imágenes y videos explicativos vía Laravel Storage.
** Interfaz Intuitiva y Responsiva:** Maquetada con **Tailwind CSS**, componentes dinámicos y una línea visual moderna y minimalista.

---

##  Stack Tecnológico

| Capa | Tecnologías |
| :--- | :--- |
| **Backend** | PHP 8.2+, Laravel 11/12 (Breeze, Eloquent ORM, Storage Facade) |
| **Frontend** | Blade Templates, Tailwind CSS, Alpine.js, Vite |
| **Base de Datos** | MySQL / SQLite |
| **Librerías & APIs** | Stripe API, QuickChart API, Barryvdh Laravel-DomPDF |

---

## Instalación y Puesta en Marcha

Sigue estos pasos para clonar y ejecutar el entorno localmente:

### 1. Clonar el repositorio
```bash
git clone [https://github.com/TU-USUARIO/millenniallife.git](https://github.com/franciscohpina/millenniallife.git)
cd millenniallife
```

### 2. Instalar dependencias de PHP y JavaScript
```bash
composer install
npm install
```

### 3. Configurar variables de entorno
```bash
cp .env.example .env
php artisan key:generate
```
*Configura tus credenciales de base de datos y llaves de prueba de Stripe en el archivo `.env`.*

### 4. Migraciones y enlace de almacenamiento
```bash
php artisan migrate --seed
php artisan storage:link
```

### 5. Compilar assets y levantar el servidor local
```bash
npm run build
php artisan serve
```

La aplicación estará disponible en `http://127.0.0.1:8000`.

---

## Autor

Desarrollado por **Francisco Piña**  
* Software Management and Development Engineering Student  
* [LinkedIn] https://www.linkedin.com/in/francisco-pi%C3%B1a-7448b2246/ • [GitHub](https://github.com/franciscohpina)