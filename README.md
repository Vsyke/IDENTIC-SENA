# Sykron S.A.S. - Sistema de Gestión de Asistencia e Inventario 🚀
Proyecto Formativo ADSO - SENA | Fase IV de Ejecución
Sykron S.A.S. es una aplicación web robusta desarrollada para el control de asistencia (aprendices, instructores, administrativos) y la gestión de inventario de equipos tecnológicos. Este sistema ha sido adaptado y evolucionado a partir del modelo COMPUBINSAS, integrando funcionalidades avanzadas de seguridad, reportes y escalabilidad.

## 📚 Origen y Créditos
Este proyecto utiliza como base el núcleo arquitectónico del curso "Desarrolla sistemas web en PHP Laravel 12, MySQL, Datatables - ADSO FASE IV".

Instructor Autor: Edubin Torres Peña.

Modelo de Referencia: Sistema COMPUBINSAS.

Derechos Reservados: © 2025 Edubin Torres Peña - SENA.

## Stack Tecnológico
Backend: Laravel 12 (PHP 8.2+)

Base de Datos: MariaDB / MySQL

Frontend: Bootstrap 5, AdminLTE, JavaScript Vanilla (ES6+)

Librerías Core:

Yajra Datatables: Tablas dinámicas con procesamiento en servidor.

Spatie Laravel Permission: Control de acceso basado en roles (RBAC).

SimpleQR: Generación de códigos para carnés y comprobantes.

DomPDF & Laravel Excel: Exportación masiva de reportes.

SweetAlert2: Notificaciones e interacción de usuario.

## 📋 Funcionalidades Implementadas
🛡️ Seguridad y Acceso
Autenticación Multirrol: Roles diferenciados para Admin, Vigilante, Maestro y Estudiante.

Tokens QR: Generación de identificadores únicos (UUID) para validación de identidad.

📊 Gestión de Asistencia (Módulo Sykron)
Filtro por Jornadas: Mañana, Tarde, Noche y Madrugada con lógica de cruce de medianoche.

Resumen en Tiempo Real: Dashboard con métricas porcentuales y conteo de usuarios únicos (prevención de duplicados).

Historial Detallado: Registro de entradas y salidas con marcas de tiempo precisas.

💻 Control de Equipos e Inventario
Vinculación de Hardware: Registro de equipos asociados a usuarios específicos.

Trazabilidad: Seguimiento de ingreso y salida de dispositivos del centro de formación.

🗂️ Módulos de Referencia COMPUBINSAS
Gestión de productos, categorías y unidades.

Control de proveedores y clientes con tipos de documentos.

Correlativos automáticos para documentos oficiales.

## 📜 Derechos y Permisos de Uso
Este software se rige bajo las condiciones establecidas por el autor original para fines educativos:

✅ Permitido: Modificar, adaptar e implementar el código en entornos personales o para clientes finales.

❌ No Permitido: Comercializar el código fuente como producto independiente o publicarlo íntegramente en repositorios públicos sin autorización expresa.

## 🚀 Instalación Rápida
Requisitos: Composer, PHP 8.2+, MySQL/MariaDB.

Configuración:

Bash
composer install
cp .env.example .env
php artisan key:generate
Base de Datos:

Bash
php artisan migrate --seed
Servidor:

Bash
php artisan serve
## ⚠ Aviso Legal
Este software se entrega "tal cual", orientado a la formación profesional integral del SENA. Se solicita a los usuarios hacer un uso responsable y respetar la autoría intelectual de los componentes base.

© 2026 Ruiz - Desarrollo y Adaptación | Basado en el trabajo de Edubin Torres Peña.
