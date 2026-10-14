# M6-FINAL-EXIT — Cierre de QA y preparación de release

**Estado de hito: M6 CLOSED WITH LIMITATIONS. Dictamen técnico de release: NO-GO.** RC.5 pasó las verificaciones reproducibles de paquete, instalación limpia, actualización desde 1.4.0, inventario y regresión automatizada. No se concede certificación final: faltan comprobaciones visuales e interactivas sobre RC.5, incluida la confirmación del contraste corregido de Hero Cover, la matriz responsive completa y la accesibilidad. 1.5.0 NOT RELEASED; M7+M8 READY FOR AUTHORIZATION. No se realizó ninguna operación de publicación.

## Candidato y alcance

- Candidato examinado: `sci-design-blocks-1.5.0-rc.5-m6.zip`.
- SHA-256: `a2c33fa331eb653d916bbcdf59d4341c98e6961add89e1f7be9c81df9dbffc73`.
- Manifiesto del paquete: 105 entradas; SHA-256 `c181951be9262bb7b249ee8c7716a0180cf92f928e0845f89a4de2a16b15f38c`.
- Manifiesto de fuentes: 105 entradas; SHA-256 `9000cdc3769c4c23803db2afce09362fb7cb88bce4381a83b2f7f08e3ad9b993`.
- `unzip -t`, suma del ZIP, hashes de todas las entradas, equivalencia fuente/paquete y revisión de secretos/rutas privadas/enlaces simbólicos: PASS.
- RC.1–RC.4 y el ZIP estable 1.4.0 conservaron los hashes previamente registrados. 1.4.0 no se modificó.

### Conciliación de metadatos fuente / RC.5

El código fuente conserva `Version: 1.5.0-rc.3`; RC.5 declara `Version: 1.5.0-rc.5`. La evidencia del empaquetado de RC.5 indica que se copió el RC.4 preservado a un directorio temporal, se aplicó el ajuste funcional de Hero Cover y se cambió la versión en esa copia antes de generar el ZIP y sus manifiestos. La comparación directa de los archivos extraídos de RC.4 y RC.5 encontró exactamente dos entradas distintas: `patterns/sci-hero-cover.html` y `sci-design-blocks.php`. La primera contiene el cambio funcional de contraste también presente en la fuente; la segunda cambia únicamente el encabezado de versión. No se alteró ni reconstruyó ningún ZIP durante esta conciliación.

Verificaciones de solo lectura: los manifiestos representan 105 rutas cada uno; al quitar el prefijo de paquete `sci-design-blocks/`, las listas y hashes del manifiesto RC y del manifiesto de distribución coinciden exactamente. La suma del ZIP sigue siendo `a2c33fa331eb653d916bbcdf59d4341c98e6961add89e1f7be9c81df9dbffc73`; las sumas de manifiestos siguen siendo las registradas arriba. `shasum -c` aplicado al manifiesto de distribución contra el árbol fuente actual pasa para los 104 archivos restantes y falla solo en `sci-design-blocks.php`, cuyo cambio byte a byte es la línea de versión `rc.3` → `rc.5`. Se comparó además esa entrada del ZIP con el archivo fuente: no aparecen diferencias funcionales en ella. El contenido funcional de los otros 104 archivos coincide. Por tanto, la discrepancia es exclusivamente metadato temporal de distribución; la fuente y su prueba de requisitos permanecen sin cambio.

Para los informes, las ubicaciones locales se expresan con placeholders: `${SCI_QA_SITE_DIR}` es la raíz de fixtures/capturas de WordPress QA; `${SCI_QA_ROOT}` contiene los subdirectorios `site/` y `release-candidates/`; `${PHP83_BIN}`, el CLI PHP 8.3; `${HOMEBREW_PREFIX}`, el prefijo local del gestor; `${QA_HTTP_HOST}`/`${QA_HTTP_PORT}` y `${QA_DB_HOST}`/`${QA_DB_PORT}`, los endpoints loopback aislados; `${TMPDIR}` y `${CODEX_STATE}`, las áreas temporales/de estado locales. Sustituirlos por valores del propio equipo al reproducir las pruebas. No se incluyen credenciales.

La inspección se mantuvo en la rama `feature/sci-hero-patterns-css-compat`, HEAD `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`. Se preservó el worktree preexistente; no se crearon commits, tags, pushes ni releases.

## Instalación, actualización y no lock-in

Las dos pruebas usaron el ZIP exacto de RC.5, WordPress 7.1.3, PHP 8.3.35 arm64 y la MariaDB local aislada:

| Escenario | Evidencia | Resultado |
| --- | --- | --- |
| Instalación limpia | `${SCI_QA_SITE_DIR}/fixtures/m6-final-exit-clean-2026-10-13`, esquema dedicado `${QA_CLEAN_DB_SCHEMA}`; instalar y activar el ZIP desde cero. Versión activa 1.5.0-rc.5. | PASS; base íntegra; portada y login HTTP 200. |
| Actualización | `${SCI_QA_SITE_DIR}/fixtures/m6-final-exit-upgrade-2026-10-13`, esquema `${QA_UPGRADE_DB_SCHEMA}`; instalar ZIP oficial 1.4.0 (`1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`), crear contenido para sus 20 patterns, actualizar con RC.5. | PASS; todos los hashes agregados del contenido antes/después coinciden: `72d678c77b04ee617c3dc560e3593b50e78726367333a0dcd0041c3528b9cd44`. |

Después de instalar y actualizar: 22 patterns, 83 iconos, siete Block Styles, tres CSS y cero bloques SCI personalizados. Desactivar/reactivar el plugin en la fixture de actualización conservó el mismo hash de contenido; WordPress Core siguió parseando los bloques guardados. Portada, login y una página de pattern respondieron HTTP 200. Esto respalda no lock-in del contenido; no sustituye una prueba visual/editorial.

Las fixtures usan directorios y bases dedicadas; sus credenciales quedan fuera del repositorio. Los servidores temporales de las pruebas se detuvieron y sus puertos quedaron cerrados. No se borraron fixtures ni se alteraron servicios globales.

## Regresión automatizada

Con PHP 8.3.35 y el Core de la fixture WordPress 7.1.3:

- PHP lint de todos los archivos PHP y cuatro pruebas PHP: PASS.
- Cuatro pruebas Python (SVG, Accordion CSS, Tabs CSS, editorial-image CSS): PASS.
- Parser/serializer e inventario de 22 patterns: PASS; incluye la serialización vacía de `core/image` en Hero Split y revisión de bloques autocerrados.
- Siete Block Styles, tres hojas CSS, variantes de Tabs y reglas de movimiento reducido: PASS.
- Regresión de overlay oscuro de Hero Cover y distribución vertical de los CTA de Pricing: PASS con parser de Core.
- `git diff --check`: PASS.

La respuesta HTTP y el parseo verifican disponibilidad/estructura, no diseño renderizado, selección real de medios ni comportamiento accesible en el editor.

## Evidencia manual reutilizable y gates pendientes

El checklist histórico `PO-QA-CHECKLIST.md` registra inserción, edición, guardado/reapertura y revisión responsive de Hero Cover y Hero Split sobre **RC.2**; no da versiones exactas de navegador ni anchos. El Product Owner informó manualmente que los tres Pricing patterns pasaron alineación de CTA, móvil, guardado/reapertura y ausencia de bloques inválidos sobre **RC.4**. Se conserva esa evidencia con su versión original; no se presenta como validación de RC.5.

RC.5 cambió el overlay de Hero Cover de 40% a 70% para corregir el contraste detectado (2.49:1 antes del cambio). El cálculo teórico sobre fondo blanco da aproximadamente 6.10:1 después del cambio, pero falta medir/confirmar en navegador con el fondo real y revisar legibilidad. Por eso la corrección de fuente/paquete está verificada, pero el gate visual de RC.5 sigue abierto.

Bloqueos concretos para certificación:

1. Confirmar en editor real RC.5 selección/cambio de imagen y texto alternativo, guardar/reabrir, y ausencia de bloques inválidos en Hero Split y Hero Cover.
2. Verificar Hero Cover (incluida legibilidad/contraste con la imagen real), Heroes y Pricing a 1440, 768, 390 y 320 px. La matriz histórica no certifica todos esos cruces para RC.5; la tabla comparativa usa desplazamiento horizontal a 320 px y falta certificar su operación táctil.
3. Completar teclado y accesibilidad: Tabs no tiene interacción manual certificada; Accordion requiere revisar Enter, Space, foco y navegación; falta confirmar zoom por hover y movimiento reducido en navegador y realizar revisión con tecnología de asistencia. No se declara conformidad WCAG.
4. Repetir las comprobaciones visuales/interactivas pertinentes sobre el candidato RC.5 y registrar navegador, viewport y resultado. HTTP 200 y pruebas estáticas no las reemplazan.

No se controló el navegador del usuario: la sesión Firefox activa pertenece al usuario y ya había cambiado durante un intento anterior. Para cerrar el gate, se solicita **una ronda PO de 15–20 minutos**, en pestaña privada/nueva, comprobando primero que el plugin activo sea RC.5 en el sitio local `http://${QA_HTTP_HOST}:${QA_HTTP_PORT}`:

| Comprobación | Registro breve del PO |
| --- | --- |
| Hero Cover ID 24 y Hero Split ID 25: insertar/editar; elegir imagen, editar alt, guardar y reabrir; sin invalidación | PASS/FAIL + nota |
| Cover: texto legible sobre imagen y contraste; Split: imagen/columnas | PASS/FAIL + navegador |
| Responsive de Cover, Split y Pricing IDs 19–21 a 1440/768/390/320 px | PASS/FAIL por ancho |
| Pricing: CTA alineados, móvil, guardar/reabrir | PASS/FAIL |
| Accordion ID 12 y Tabs: teclado, foco, Enter/Space y navegación | PASS/FAIL + nota |
| Block Styles: fixture ID 27; confirmar siete estilos visualmente | PASS/FAIL |

El fixture histórico `M6 QA — Block Styles RC.4` se sirve con RC.5 en ese entorno; confirmar versión activa antes de evaluar. No hace falta repetir los 22 patterns si no se alteran.

## Decisión

**M6 queda CLOSED WITH LIMITATIONS; el release de 1.5.0 sigue TECHNICAL NO-GO y NOT RELEASED.** Los gates automatizados y de instalación/actualización están cerrados; permanecen pendientes solo los gates visuales, interactivos y de accesibilidad enumerados arriba. M7+M8 quedan READY FOR AUTHORIZATION sobre un baseline identificable, no autorizados para ejecución automática. Tras registrar la ronda PO, corresponde reevaluar esos gates y solicitar aprobación expresa antes de cualquier publicación. No se preparó ni ejecutó push, merge, tag, release ni publicación de GitHub.
