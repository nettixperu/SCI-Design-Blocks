# SCI Design Blocks — M6-CERT-FINAL

**Dictamen técnico: NO-GO para release; M6 sigue ACTIVE.** La evidencia automatizada y la integridad del candidato pasan, y el Product Owner (PO) reporta QA interactiva favorable de ambos Heroes. Sin embargo, gates obligatorios aún carecen de evidencia: PHP 7.4 runtime, instalación limpia RC.2, actualización desde 1.4.0, responsive completo y accesibilidad. Este informe no aprueba publicación.

## Baseline y alcance

- Rama: `feature/sci-hero-patterns-css-compat`.
- HEAD: `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`.
- Estable congelada: 1.4.0. Candidato QA: 1.5.0-rc.2.
- Se conservaron los cambios de trabajo existentes. No se modificaron `main` ni el artefacto 1.4.0; no hubo commit, push, merge, tag o publicación.
- `git diff --check`: PASS. Antes de crear este informe, el estado acumulado M5/M6 registraba 10 archivos rastreados modificados y 10 sin seguimiento; este informe añade un archivo nuevo sin seguimiento.
- Inventario: 22 patterns (20 existentes + `sci-design-blocks/hero-cover` y `sci-design-blocks/hero-split`), 83 iconos, 7 Block Styles, 3 CSS, 0 custom blocks SCI, 0 JavaScript frontend SCI y 0 dependencias runtime nuevas.

## RC.2 y trazabilidad

ZIP: [`sci-design-blocks-1.5.0-rc.2-m6.zip`](<${SCI_QA_ROOT}/release-candidates/sci-design-blocks-1.5.0-rc.2-m6.zip>), SHA-256 `f8d5d7c65cb17b1645d407324d73f8c44c8cb1b4b1dbeb8977af23ef007ef477`. `unzip -t` pasó; paquete y manifiestos están íntegros. RC.1 se conserva sin reemplazo.

| Manifiesto | SHA-256 |
| --- | --- |
| `RC-FILE-MANIFEST-rc.2.sha256` | `f906b6d125b4217a8263545495ad5eab5e7046bb91d8cfa5f918fcb6beb0de4d` |
| `SOURCE-FILE-MANIFEST-rc.2.sha256` | `a685bb5f75709914b3ff5557084881b52ac4f635f04377a9ed147ad2c9b43368` |

HEAD no representa por sí solo el ZIP no versionado; usar manifiestos y hash para identificar RC.2.

## QA interactiva reportada por el PO

El PO comunica prueba externa de RC.2. Se registra estrictamente como **PASS — PO MANUAL QA**, no como ejecución de Codex ni prueba automatizada:

| Pattern | Insertar, editar, guardar, reabrir sin invalidación | Responsive reportado |
| --- | --- | --- |
| Hero Cover | PASS — PO MANUAL QA | móvil PASS — PO MANUAL QA |
| Hero Split | PASS — PO MANUAL QA; no reapareció “Block contains unexpected or invalid content” | móvil y tablet PASS — PO MANUAL QA |

No se aportaron versiones del navegador/WordPress/PHP, capturas, dimensiones exactas, ni pruebas explícitas del resto de viewports. No se infiere confirmación de controles/operaciones no citados. La evidencia sí satisface los flujos editoriales expresamente reportados y no requiere repetirlos sin cambio posterior.

La causa conocida de Hero Split fue `core/image` autocerrado. La corrección conserva el bloque Core editable con serialización `<figure><img alt=""/></figure>`. La prueba PHP de regresión confirma el marcado y el parser/serializer en WordPress 7.1.3; no sustituye la validación JavaScript del editor. La QA manual PO posterior proporciona evidencia interactiva del patrón, no una nueva captura de consola.

## Entorno y regresión ejecutada

Runtime local: PHP 8.5.7; no se encontró binario PHP 7.4 ni runtime local de contenedor/VM ya disponible. WordPress parser: 7.1.3. El entorno QA principal no estaba disponible de forma íntegra: `${QA_HTTP_HOST}:${QA_HTTP_PORT}` no escucha y algunas consultas WP-CLI fallan en la inicialización SQLite. No se concluye corrupción, no se reparó ni reinstaló. Los intentos de inspección no destructiva anteriores constan en [M6-CLOSE](M6-CLOSE-REPORT.md).

En esta corrida pasaron PHP lint, `check-icon-library.php`, `check-editorial-query-patterns.php`, `check-pricing-comparison-patterns.php`, `check-tabs-roundtrip.php`, los cuatro checks Python de SVG/CSS y `git diff --check`. El test de patterns confirma 22 registros, round-trip, 38 casos autocerrados permitidos entre bloques Core sin markup guardado/dinámicos y cero `core/image` autocerrados. No se alteraron requisitos ni pruebas para ocultar defectos.

Evidencia anterior reutilizada: CSS de Block Styles en WordPress 7.1/7.1.3 con Twenty Twenty-Five y Twenty Twenty-One, más auditoría estática offline de 144 rutas sin hallazgos reportables. La corrección M6-FIX-01 se limitó a markup estático de Hero Split y prueba; la revisión incremental no encontró scripts, URLs remotas, dependencias ni lógica ejecutable nueva. Esto no es una nueva auditoría completa ni una garantía de seguridad absoluta.

## Matriz final de gates

| Gate | Estado | Evidencia / versión | Responsable | Riesgo / bloqueo | Acción pendiente |
| --- | --- | --- | --- | --- | --- |
| Hero Cover editor | PASS — PO MANUAL QA | RC.2; insertar/editar/guardar/reabrir sin invalidación, según PO | PO | Sin metadata independiente | Conservar evidencia y versiones si se requiere auditoría; no repetir sin cambio |
| Hero Split editor | PASS — PO MANUAL QA | RC.2; mismo flujo y ausencia del aviso original, según PO | PO | No hay consola/capturas adjuntas | Conservar evidencia/versiones; no tocar pattern sin defecto nuevo |
| PHP lint / regresión PHP-Python | PASS | PHP 8.5.7; parser WordPress 7.1.3; comandos descritos arriba | Ingeniería | No demuestra PHP mínimo | Ninguna para el código actual |
| Inventario / parser / serializer | PASS | 22 patterns, 83 iconos, 7 estilos, 3 CSS; checks de fuente | Ingeniería | No equivale a QA interactiva completa | Ninguna para el inventario actual |
| PHP 7.4 runtime | NOT VERIFIED | No runtime 7.4 disponible; PHP 8.5.7 no sustituye la prueba | Ingeniería | Bloqueante: requisito mínimo sin demostrar | Proveer runtime 7.4 aislado y ejecutar contrato runtime; no cambiar PHP global |
| Instalación limpia RC.2 | NOT VERIFIED | No se completó carga/activación RC.2; lab local con SQLite inaccesible | Ingeniería | Bloqueante | Ejecutar en sitio desechable aislado; documentar PHP/WP/tema/DB |
| Upgrade 1.4.0 → RC.2 | NOT VERIFIED | No se ejecutó upgrade desde artefacto estable en esta certificación | Ingeniería | Bloqueante | Probar copia aislada con contenido fixture y ZIP oficial intacto |
| Responsive Heroes 1440 px | PENDING PO VALIDATION | Sin evidencia reportada para este viewport | PO | Bloqueante al gate completo | Validar ambos Heroes y registrar captura/resultado |
| Responsive Heroes 768 px | PASS parcial / PENDING exactitud | Hero Split “tablet” reportado; Hero Cover y ancho exacto no documentados | PO | Bloqueante al gate completo | Aclarar dimensión y validar faltantes |
| Responsive Heroes 390 px | PASS parcial / PENDING exactitud | Móvil reportado para ambos, sin dimensión exacta | PO | Bloqueante al gate completo | Confirmar ancho y validar faltantes |
| Responsive Heroes 320 px | PENDING PO VALIDATION | Sin evidencia | PO | Bloqueante | Validar ambos Heroes |
| Responsive otros patterns | NOT VERIFIED | Matriz 1440/768/390/320 pendiente según checklist | PO | Bloqueante | Completar Accordion, Tabs y Hover Zoom en matriz requerida |
| Accesibilidad básica | NOT VERIFIED | Sin evidencia suficiente de teclado, focus, orden, contraste, alt, enlaces, tap targets | PO | Bloqueante | Completar checklist acotado; no afirmar conformidad WCAG integral |
| Block Styles / entrega CSS | PASS WITH LIMITATIONS | Evidencia previa WP 7.1/7.1.3, TT5/TT21; sin cambio posterior relevante | Ingeniería | No prueba visual/editor completa en toda matriz | Reutilizar evidencia; incorporar verificación visual dentro de QA pendiente |
| Seguridad incremental M6-FIX-01 | PASS WITH LIMITATIONS | Revisión estática de markup y test; auditoría offline previa 144 rutas/0 hallazgos | Ingeniería | Alcance incremental, no garantía absoluta | Ninguna nueva auditoría completa justificada por este delta |
| No-lock-in (desactivación SCI) | PASS WITH LIMITATIONS | Evidencia anterior para fixtures/código anterior; no flujo de imagen post-fix desactivado | PO/Ingeniería | La persistencia específica del nuevo flujo sigue abierta | Verificar contenido creado con RC.2 tras desactivar plugin en copia desechable |
| RC.2 ZIP/manifiestos | PASS | Hashes verificados; `unzip -t` sin errores | Ingeniería | ZIP solo es candidato QA | Preservar sin regenerar salvo cambio de código |
| Aprobación de release | NOT AUTHORIZED | No existe autorización expresa de publicación | PO | Publicación prohibida | Decisión separada del PO después del GO técnico |

## Dictamen y próximos pasos

**NO-GO técnico. M6 = ACTIVE / gates abiertos; no `CLOSED — PASS`.** Los gaps obligatorios son verificables y concretos: runtime PHP 7.4, instalación limpia, upgrade, responsive completo y accesibilidad. El QA interactivo reportado es positivo y aceptado en su alcance exacto, pero no cubre esas brechas. No procede preparar procedimiento de publicación porque no hay TECHNICAL GO; una futura aprobación del PO seguirá siendo independiente.

1. Ejecutar PHP 7.4 en laboratorio aislado y documentar runtime; no instalar ni cambiar herramientas/PHP global durante esta tarea.
2. Recuperar o crear laboratorio desechable sin tocar instalaciones existentes y certificar instalación RC.2 y upgrade desde 1.4.0.
3. Completar viewports pendientes y matriz de accesibilidad; guardar versiones, método y evidencia.
4. Verificar no-lock-in del contenido de Hero Split corregido en una copia aislada.
5. Recalcular la matriz cuando los gates tengan evidencia; mantener prohibida toda publicación hasta autorización expresa.
