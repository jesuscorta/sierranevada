mausWP — WordPress Theme Starter

mausWP es un tema base minimalista para proyectos WordPress personalizados.
Incluye integración con Tailwind CSS, estructura modular con PHP, y soporte para bloques ACF sin depender de React.

Pensado para desarrollos rápidos, escalables y limpios.

------------------------------------------------------------
🚀 Características principales
------------------------------------------------------------
- Tailwind CSS integrado (compilación a /dist)
- Estructura modular en `/inc/` para mantener el código ordenado
- Compatibilidad con ACF Blocks mediante renderizado en PHP
- Sin dependencias innecesarias ni archivos de relleno
- Preparado para ampliar con plantillas, bloques y componentes
- Convenciones estables de nombres y organización

------------------------------------------------------------
📂 Estructura del tema
------------------------------------------------------------
mauswp/
├─ inc/
│  ├─ setup.php           # Configuración del tema
│  └─ assets.php          # Registro de estilos y scripts
│
├─ assets/
│  └─ src/
│     ├─ css/app.css      # Entrada CSS para Tailwind
│     └─ js/app.js        # JS principal del tema
│
├─ dist/
│  ├─ app.css             # CSS compilado por Tailwind
│  └─ app.js              # JS de producción (si aplica)
│
├─ template-parts/
│  └─ blocks/
│     └─ hero.php         # Bloque Hero de ejemplo
│
├─ functions.php          # Carga de módulos del tema
├─ style.css              # Cabecera del tema para WP
├─ index.php              # Plantilla de fallback
├─ header.php             # Cabecera del tema
├─ footer.php             # Pie del tema
├─ front-page.php         # Plantilla de portada (opcional)
├─ tailwind.config.js     # Configuración de Tailwind
└─ package.json           # Scripts y dependencias

------------------------------------------------------------
📦 Instalación
------------------------------------------------------------
1. Subir la carpeta `mauswp` a `/wp-content/themes/`
   o instalar el ZIP desde el panel de WordPress.

2. Instalar dependencias (opcional para desarrollo):
   npm install

3. Compilar Tailwind:
   - Desarrollo: npm run dev
   - Producción: npm run build

------------------------------------------------------------
📘 Bloques ACF
------------------------------------------------------------
El tema soporta bloques ACF mediante `render_template`.
Los bloques se organizan en `/template-parts/blocks/`.

Ejemplo incluido: Hero (acf/hero).

------------------------------------------------------------
🧱 Convenciones
------------------------------------------------------------
- Archivos en minúsculas, separados por guiones.
- Prefijo de funciones PHP: `mauswp_`
- Clases PHP: `Mauswp_ClassName`
- Estilos únicamente con Tailwind.

------------------------------------------------------------
🛠 Requisitos
------------------------------------------------------------
- WordPress 6.x
- PHP 8.x
- ACF Pro
- Node.js + npm (solo si compilas Tailwind)

------------------------------------------------------------
✔ Ideal para…
------------------------------------------------------------
- Sitios corporativos
- Landings
- Diseños personalizados
- Proyectos basados en bloques ACF
- Temas reutilizables de agencia

------------------------------------------------------------
📄 Licencia
------------------------------------------------------------
Uso interno o libre.

------------------------------------------------------------
🧑‍💻 Autor
------------------------------------------------------------
Starter para proyectos WordPress personalizados.
