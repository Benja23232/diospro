# Diospro 🎯

Sistema de gestión de eventos, inscripciones y participantes desarrollado en **Symfony**.  
Este proyecto forma parte de mi portafolio y refleja mi experiencia en desarrollo web con PHP, Symfony, Doctrine y Twig.

---

## 🚀 Características principales
- Gestión de **eventos** y **participantes**.
- Sistema de **inscripciones** con relaciones entre entidades.
- Panel de administración con roles y seguridad.
- Formularios dinámicos y validados.
- Plantillas Twig con diseño modular y reutilizable.
- Carga de datos iniciales mediante **fixtures**.

---

## 🛠️ Instalación

### 1. Clonar el repositorio
bash
git clone https://github.com/Benja23232/diospro.git
cd diospro

---

2. Instalar dependencias
bash
composer install

---

3. Configurar entorno
Copiar el archivo .env.example a .env y ajustar la conexión a la base de datos:

bash
cp .env.example .env
Configuracion de la Base de Datos: 
DATABASE_URL="mysql://user:password@127.0.0.1:3306/diospro_db?serverVersion=8&charset=utf8mb4"

4. Crear la base de datos
bash
php bin/console doctrine:database:create

---

5. Ejecutar migraciones
bash
php bin/console doctrine:migrations:migrate

---

6. Cargar datos de prueba (fixtures)
bash
php bin/console doctrine:fixtures:load --env=dev

---

🖥️ Uso
Levantar el servidor local de Symfony:
bash
symfony server:start
Acceder en el navegador:
http://127.0.0.1:8000

---

📌 Tecnologías utilizadas
Symfony 6
Doctrine ORM
PHP
JAVASCRIPT
CSS
Twig
Composer
MySQL/MariaDB

👨‍💻 Autor
Benjamin Desarrollador web y analista de sistemas en formación. Especializado en Symfony, Python, Vue.js y pedagogía técnica.

📜 Licencia
Este proyecto se comparte con fines educativos y de portafolio.

