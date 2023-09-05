# com_mantenimiento

# **Componente Joomla para la gestión de mantenimientos de estaciones**



## **Autor**





Angel Garitagotia de Castro





**Descripción**


Componente para la gestión de estaciones automáticas y sus mantenimientos dando la posibilidad de su visualización en un mapa.
La aplicación permite la subida de fotos por medio de los formularios de estaciones y mantenimientos en la carpeta "images/fotos_estaciones/" de Multimedia

**Puesta en operación**


**Tablas de la Base de datos**

Al instalar se crean 4 tablas:

1 __estaciones

2 __mantenimientos

3 __mantenimiento_mapa


**Elementos menú**

Se crean 6 tipos de elementos de menú para el Item Mantenimientos:

1.  Generador KML. Genera un fichero KML a partir de la tabla estaciones para su visualización en GoogleMaps y GoogleEarth

2.  Histórico de mantenimientos. Formulario para consultar los mantenimientos guardados por estación y a partir de una fecha

3.  Listado de estaciones: Muestra un listado de todas las estaciones guardadas

4.  Listado de mantenimientos: Muestra un listado con los últimos mantenimientos de cada estación

5.  Mapa estaciones: Muestra en un mapa las estaciones guardadas con la información de la estación y del último mantenimiento en un popup

6.  Mapa solo estaciones. Muestra en el mapa las estaciones con sus datos independientemente de que exitan o no mantenimientos.



**Menú Componentes en backend**
En el menú componentes del backend:

1.  Item Mantenimiento-> Mapas: Configuración mapa donde podemos indicar el zoom, latitud y longitud del centro del mapa (en grados decimales), ancho y alto del mapa (en pixeles) que queremos mostrar.



**AVISOS**

1.  Para que una estación aparezca en el item "Mapa estaciones" debe existir algún mantenimiento. Si se crea desde el formulario del componente, el primer mantenimiento se crea con los valores a "Inicio".

2.  Para añadir, editar o borrar estaciones es necesario que el usuario esté registrado y que tenga permisos de crear, borrar y editar. Sería suficiente con que el usuario perteneciera a grupo que herede de "Registered" y darle permiso en el componente para crear, borrar y editar (Sistema -> Configuración global -> Componente -> Mantenimiento-> Permisos)

3. Si tenemos instalada la versión 1.0.0 o 1.0.1 necesitaremos volver a cargar el tipo de elemento en los items de menu que hayan cambiado (listado estaciones,listado mantenimientos, histórico mantenimientos)

**Versión 1.0.4**

1.  Se elimina la base de datos '#__fotos' sustituyendose por la subida automática a la carpeta "fotos_estaciones"

2.  Desaparece el item de menú "Fotos" del Menú Componentes->Mantenimiento" del Backend

3.  Se eliminan ficheros residuales de anteriores instalaciones

**Versión 1.0.3**

1.  Cambio de *select* a *checkbox* en la selección de variables del formulario estación

2.  Se hace **link** a al histórico de mantenimientos desde el nombre de la estación tanto desde el listado de mantenimientos como en el de estaciones.

3.  Se hace **link** a todos los datos de la estación desde el campo indicativo climatológico.

4.  Se añade **galería** de fotos

5.  Se añade **link** en el listado de estaciones hacia la galería de fotos de la cada estación

6.  En el formulario Mantenimientos, se cambia el tipo del campo comentarios de *text* a *editor*, lo que permite subir fotos y crear directorios en multimedia (para ello debe utilizarse el editor JCE)

7.  En el mapa se mantiene abierta un solo *popup* de estación.

8.  Se implementan los **permisos** de usuario para la edición, creación y borrado de estaciones y mantenimientos
