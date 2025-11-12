# Sistema de Inscripción – Parcial Programación 3

## 💡 Descripción
Sistema de inscripción de alumnos desarrollado en PHP (POO), con validación, carga de documentos, comprobante PDF y paginación para administración.

---

## 🧱 Estructura del sistema
- Registro y login (usuarios)
- Una inscripción por usuario
- Subida de archivo/documento (PDF, JPG, PNG)
- Confirmación con captcha
- Comprobante PDF generado con FPDF
- Listado de inscripciones con paginación (solo admin)

---

## ⚙️ Requisitos
- XAMPP o similar
- PHP 8.1+
- MySQL
- Librería FPDF (ya incluida en `/vendor/fpdf186`)

---

## 🚀 Instalación
1. Copiar la carpeta `Sistema-Inscripcion` dentro de `htdocs/`.
2. Importar el archivo `database.sql` en phpMyAdmin.
3. Configurar conexión en `/config/conexion.php` si es necesario.
4. Iniciar Apache y MySQL desde XAMPP.
5. Abrir [http://localhost/Sistema-Inscripcion/public/register.php](http://localhost/Sistema-Inscripcion/public/register.php)

---

## 👤 Usuarios demo
- **Admin/demo:** admin@mail.com / 123456
- **Usuario demo:** fede@mail.com / 123456

---

## 📘 Funcionalidades
| Módulo | Descripción |
|--------|--------------|
| Registro/Login | Usuarios registrados acceden al sistema |
| Inscripción | Cada usuario puede crear o editar su inscripción |
| Validaciones | Campos obligatorios, extensión de archivo, tamaño |
| Captcha | Antes de confirmar la inscripción |
| PDF | Generación automática del comprobante con FPDF |
| Paginación | En el panel de inscripciones (admin) |

---

## 🧠 Defensa técnica
### Requisitos POO cumplidos:
- Clases `Crud`, `User`, `Inscripcion`, `UploadHandler`, `Captcha`
- Uso de encapsulamiento, métodos y objetos
- Reutilización de librería CRUD

### Seguridad y validación:
- Uso de `password_hash()` y `password_verify()`
- Validación de archivos (`$_FILES`)
- Validación de sesión activa
- Captcha para confirmar

### Librerías:
- **FPDF** para comprobante PDF
- **Librería CRUD** desarrollada por el alumno
- **Paginación** manual implementada en `admin_inscripciones.php`

---

## 📦 Entrega
El archivo ZIP incluye:
- Código completo
- Base de datos `.sql`
- Carpeta `/uploads` con ejemplos
- `README.md`
- Captura de comprobante PDF generado

---

© Federico Bitsch — Programación 3
