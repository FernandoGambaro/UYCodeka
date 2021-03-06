<?php
/**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.php>.
 */
 /*La estructura de l array es: pagina anterioer, pagina sigiente*/
$pagina=array(0=>'instalar_02.php',1=>'../01_ingreso.php' );
/*La estructura de l array es: titulo pagina anterioer, titulo pagina sigiente*/
$titulo=array( 0=>'0.2. Preparar entorno local Windows', 1=>'1. Ingreso al sistema');

/*Puede haber varias secciones en la ayuda y para cada una sus partes partes
0=> Titulo de la sección
1=> Sub titulo
2=> Imagen a mostrar
3=> Sub titulo imagen
4=> Descripción
5=> Notas u observaciones 

*/
$seccion=array( 0=> array(0=>'0.3.0. Comprobar que todo funciona',
													      1=>'Antes de continuar con este tutorial de instalación de UYCODEKA,
              asegúrese de que todos los componentes de su paquete AMP funcionan correctamente.',
													      2=>'',
													      3=>'',
													      4=>'			

             <p></p>
              <ul><li><strong>El servidor web tiene que estar instalado y en funcionamiento</strong>. 
              Debería poder acceder a él a través de tu navegador escribiendo "127.0.0.1" en la barra de direcciones.</li></ul>
               <div>
    			<div class="information-macro information-macro-information"><p>
    			<a href="http://127.0.0.1/">http://127.0.0.1</a> es el "servidor local", es decir, "su PC": es una dirección de bucle invertido
    			 que dirige el navegador a su servidor web local.<br />En efecto,
    			  <a href="http://127.0.0.1/">http://127.0.0.1</a> y 
    			  <a href="http://localhost/">http://localhost</a> son sinónimos: puede utilizarlo indistintamente, 
    			  ya que ambos le dirigen a la carpeta raíz de tu servidor web local.</p></div>    </div>
<div class="information-macro information-macro-warning">
    <div class="information-macro-body"><p>Algunos servidores web podrían no arrancar debido a que hay otra aplicación que ya 
    está usando sus puertos de conexión (normalmente, el puerto 80).</p>
    <p>Esto suele ocurrir cuando se utiliza Skype. Para evitar que Skype bloquee el funcionamiento de su Servidor web, 
    ajuste la configuración avanzada de Skype (Herramientas &gt; Opciones &gt; Avanzada &gt; Conexiones) y desmarque la opción 
    "Usar los puertos 80 y 443 para las conexiones entrantes adicionales". 
    Reinicie Skype y vuelve a probar su servidor web local.</p></div>    </div>
<ul><li>
<strong>El servidor de la base de datos tiene que estar en marcha</strong>.
 UYCODEKA utiliza para almacenar datos un servidor MySQL. 
El paquete AMP tiene que indicarle claramente si MySQL está funcionando o no.</li><li>
<strong>Debe poder acceder a la herramienta phpMyAdmin</strong>. 
Esta es la aplicación web que le permite gestionar los datos almacenados en MySQL.
 Su ubicación depende del paquete AMP que elija: puede encontrarla en 
 <a href="http://127.0.0.1/phpmyadmin" >http://127.0.0.1/phpmyadmin</a> (XAMPP, WampServer, MAMP), en
  <a href="http://127.0.0.1/mysql">http://127.0.0.1/mysql</a> 
  (EasyPHP) o en otra ubicación. Consulte la documentación del paquete seleccionado, 
  ya que podría proporcionarle incluso un botón phpMyAdmin que abriría la URL correcta en su navegador.</li>
  
',
													      5=>'',
													      ),	
							1=> array(0=>'0.3.1. Cómo encontrar la carpeta raíz en el servidor web local',
													      1=>'Cuando haya comprobado que el paquete está instalado correctamente y que todos sus componentes están funcionando, 
  tiene que encontrar la carpeta raíz del servidor web local.',
													      2=>'',
													      3=>'',
													      4=>'		  

  <p>Esta es la carpeta local donde colocará los archivos de UYCODEKA. 
  Puede compararse con la carpeta raíz del servidor web online, solo que accede a su contenido con 
  <a href="http://127.0.0.1/">http://127.0.0.1</a>.</p>
  <p>La ubicación local real de la carpeta depende del paquete AMP, y puede personalizarse:
  </p><ul><li>EasyPHP: <code>C:\easyphp\www</code></li>
  <li>MAMP: <code>/Applications/MAMP/htdocs/</code></li>
  <li>WampServer: <code>C:\wamp\www</code></li>
  <li>XAMPP: <code>C:\xampp\htdocs</code> o <code>/Applications/xampp/htdocs</code></li>
  <li>LAMP: <code>/var/www</code> o <code>/var/www/html</code></li>
  </ul>
  
  ',
													      5=>'',
													      ),	
							2=> array(0=>'0.3.2. Cómo encontrar la información del usuario MySQL',
													      1=>'Por último, tiene que conocer el nombre de usuario raíz y la contraseña de MySQL para poder continuar con la instalación.',
													      2=>'',
													      3=>'',
													      4=>'		 
  <p><strong>La mayoría de los paquetes utilizan el nombre de usuario "root" con una contraseña vacía</strong>
  , entre ellos EasyPHP, MAMP, WampServer y XAMPP, en caso de ser LAMP durante la instalación se 
  le ha solicitado una contraseña para la configuración de MySQL</p>
  <p>Lea la documentación de su paquete.</p>
 
  Con todo eso claro, ya puede continuar con el resto de la instalación y utilización de UYCODEKA.</p>
  <div class="information-macro information-macro-warning">

    <div class="information-macro-body"><p>Tenga en cuenta lo siguiente:</p>
    <ul><li>los archivos no se cargarán mediante un software FTP (como FileZilla) a un servidor web: solo tiene 
    que copiarlos a la carpeta local correspondiente, tal como hemos indicado anteriormente.</li>
    <li>No tiene que crear un nombre de dominio local: tal como comentábamos, el sistema 
    está disponible mediante la dirección de bucle inverso que puede ser 
     <a href="http://localhost/" >http://localhost</a> o <a href="http://127.0.0.1/">http://127.0.0.1</a>. 
     Para acceder a UYCODEKA, solo tiene que añadir el nombre de la carpeta donde copio los archivos del sistema, por ejemplo 
     <a href="http://localhost/uycodeka">http://localhost/uycodeka</a> o 
     <a href="http://127.0.0.1/uycodeka">http://127.0.0.1/uycodeka</a>, suponiendo que UYCODEKA esté en la subcarpeta 
     <code>/uycodeka/</code> de la carpeta raíz local. 
     Cuando acceda a esta dirección por primera vez, le debería redirigir automáticamente a la instalación 
     de uycodeka en <a href="http://localhost/uycodeka/install">http://localhost/uycodeka/install</a> o 
     <a href="http://127.0.0.1/uycodeka/install">http://127.0.0.1/uycodeka/install</a>.</li>
<li>Si lo utilizará en una red, puede acceder al mimso utilizando la dirección IP http://192.168.1.100/, del equipo donde funciona 
UYCODEKA, teniendo en cuenta habilitar el firewall del mismo para conexiones entrantes al puerto 80.</li>
     </ul>
<p>¿Lo ha leído todo? Continuemos con la guía de instalación.</p>
        </div>
</div>
  ',
													      5=>'',
													      ),	
							3=> array(0=>'0.3.3. Descarga y descompresión del archivo UYCODEKA',
													      1=>'Puede descargar la última versión de UYCODEKA en:												      
<ul><li>
<b>Para descargar BASE de DATOS clic <a href="../../descarga/basededatos.sql">aquí</a></b>
</li>
<li>
<b>Para descargar UYCODEKA 1.2.8.20 clic <a href="../../descarga/uycodeka_1.2.8.zip">aquí</a></b>
</li>
</ul> 
',
													      2=>'',
													      3=>'',
													      4=>'		
<span><p><br />Clic en el botón "Descargar" y guarde el archivo en su equipo (por ejemplo, en el Escritorio 
"uycodeka_1.2.8.zip"(o equivalente, depende de los números de versión).</p>
<div>
    <div><p>El archivo descargable está en formato Zip. 
    Para continuar con el proceso, <strong>tiene que descomprimir el archivo</strong>.</p>
    <p>Si su sistema operativo no es compatible de forma nativa con archivos Zip, 
    puede descargar e instalar una herramienta específica, como:</p><ul>
    <li>Windows:</li><ul>
    <li>7-zip: <a href="http://www.7-zip.org/">http://www.7-zip.org/</a></li>
    <li>WinZip: <a href="http://www.winzip.com/win/en/index.htm">http://www.winzip.com/win/en/index.htm</a></li>
    <li>WinRAR: <a href="http://www.rarlab.com/">http://www.rarlab.com/</a></li></ul>
    <li>Mac OS X:</li><ul>
    <li>iZip: <a href="http://www.izip.com/">http://www.izip.com/</a></li>
    <li>WinZip Mac: <a href="http://www.winzip.com/mac/">http://www.winzip.com/mac/</a></li>
    <li>Zipeg: <a href="http://www.zipeg.com/">http://www.zipeg.com/</a></li></ul></ul>
    
    </div>    </div>
<p>Extraer el contenido del archivo en su disco duro (por ejemplo, en el Escritorio otra vez) 
usando una herramienta de descompresión. <strong>
El archivo Zip no se carga en el servidor web</strong>.
</p><div>
  
    <div><p>El archivo contiene todos los elementos para el funcionamiento de UYCODEKA</p><ul>
    <li>El archivo "CambiosUYCODEKA.odt" (<a href="http://www.libreoffice.org">libreoffice</a>) contien un listado de las mejoras que se le han implementado.</li>
    <li>Dentro de la carpeta instalar se encuentra el asistente para la instalación de UYCODEKA, 
    el archivo index.php ejecuta la instalación.</li></ul>
    </div>    </div>
  ',
													      5=>'',
													      ),	
							4=> array(0=>'0.3.4. Carga de UYCODEKA al servidor WEB',
													      1=>'Vamos a "subir" los archivos que descomprimimos al servidor WEB, utilizando
 para ello una herramienta denominada "cliente FTP", en caso que el servidor WEB no sea local, 
 si es local saltear este paso.',
													      2=>'',
													      3=>'',
													      4=>'	    

 <h4>Cliente FTP</h4><p>FTP es el acrónimo de "File Transfer Protocol":
  la forma estándar que se usa para transferir archivos desde un PC a un servidor WEB no local.</p>
  <p>En esta guía usaremos FileZilla, un cliente gratuito para Windows, MacOS X y Linux. 
  Descárgamos FileZilla Cliente (FileZilla Server no es necesario) desde 
  <a href="http://filezilla-project.org/">http://filezilla-project.org/</a>
   y lo instalamos, preferentemente en idioma español.</p>
   <p>Cuando este instalado FileZilla, tendrá que configurarlo con los parámetros de 
   conexión de su servidor web que le debe haber proporcionado su proveedor. </p><p>
   Los parámetros que necesitas son:</p><ul><li><strong>el nombre del servidor
   </strong> o <strong>una dirección IP</strong>: la ubicación del servidor 
   FTP.</li><li><strong>un nombre de usuario
   </strong>: una cuenta de usuario FTP, exclusivo para su persona.</li>
   <li><strong>una contraseña</strong>: medida de seguridad obligatoria.</li>
   </ul><p>Abrir FileZilla e ir al Gestor de sitios. Hay tres formas de hacerlo:</p>
   <ul><li>Presionar Ctrl-S.</li>
   <li>En la esquina superior izquierda, clic en el icono "Gestor de sitios". (debajo del menú archivo)</li>
   <li>Clic en el menú "Archivo" y seleccione la opción "Gestor de sitios...".</li>
    </ul>
    <p>Se abre una ventana.</p><p>Para añadir el acceso al servidor WEB vía FTP:</p>
    <ol><li>Clic en el botón "Nuevo sitio". Se crea una nueva entrada en la lista de sitios. 
    Escribir un nombre adecuado.</li>
    <li>En el lado derecho, en la pestaña "General", 
    introducir los parámetros que le ha proporcionado su proveedor: alojamiento, usuario y contraseña. 
    No cambie el resto de los parámetros predeterminados a menos que lo indique su proveedor.</li>
    <li>Clic en el botón "Conectar". 
    Esta acción guardará su sitio en la lista y le permitirá acceder a su cuenta para que compruebe 
    que todo funciona correctamente.</li>
    </ol><p>Puede utilizar otras alternativas a FileZilla:</p><ul>
    <li>Windows:</li><ul><li>CoreFTP:  <a href="http://www.coreftp.com/">http://www.coreftp.com/</a></li>
    <li>WinSCP: <a href="http://winscp.net/">http://winscp.net/</a></li>
    <li>SmartFTP: <a href="http://www.smartftp.com/">http://www.smartftp.com/</a></li></ul>
    <li>Mac OS X:</li><ul>
    <li>Cyberduck: <a href="http://cyberduck.ch/">http://cyberduck.ch/</a></li>
    <li>Transmit: <a href="http://www.panic.com/transmit/">http://www.panic.com/transmit/</a></li>
    <li>Fetch: <a href="http://fetchsoftworks.com/fetch/">http://fetchsoftworks.com/fetch/</a></li>
    </ul><li>Unix/Linux:</li><ul><li>gFTP: <a href="http://gftp.seul.org/">http://gftp.seul.org/</a></li>
    <li>kasablanca: <a href="http://kasablanca.berlios.de/">http://kasablanca.berlios.de/</a></li>
    <li>NcFTP: <a href="http://www.ncftp.com/ncftp/">http://www.ncftp.com/ncftp/</a></li>
    </ul></ul>
    
    <p>Tiene que decidir dónde quiere alojar el sistema. Puede configurar el acceso al mismo de varias formas
    :</p><ul><li>En la raíz del dominio:<a href="http://www.example.com/">http://www.ejemplo.com/</a></li>
    <li>En una carpeta: <a href="http://www.example.com/uycodeka/">http://www.ejemplo.com/uycodeka/</a></li>
    <li>En un subdominio: <a href="http://uycodeka.example.com/">http://uycodeka.ejemplo.com/</a></li>
    <li>En una carpeta de un subdominio: <a href="http://uycodeka.example.com/sistema/">
    http://uycodeka.example.com/sistema/</a></li>
    </ul><p>
 
 Para subir el sistema al servidor WEB utilizaremos la herramienta gratuita FileZilla.
 <p>Utilizando cliente FTP con los datos de conexión que le 
 haya proporcionado su proveedor web. 
 Una vez conectado, ya puede transferir los archivos de UYCODEKA al servidor web.</p>
 
 <p>Utilizando FileZilla (o cualquier otro cliente FTP), explore sus carpetas locales hasta encontrar la que 
 contiene los archivos del sistema. Mantenerla abierta en la sección "Sitio local" (a la izquierda).</p>
 <p><span>
 <img  src="../../_images/scrFileZilla.png">
 
 </span></p><p>En la sección "Sitio remoto" (a la derecha),
  se muestra la ubicación donde puede "subir" el sistema UYCODEKA
   (raíz del dominio, subcarpeta, subdominio...). Esta configuración puede cambiar 
  bastante en función de tu alojamiento y tus necesidades:</p><ul>
  <li>Alojamiento:</li><ul>
  <li>Algunos alojamientos podrían requerir que "suba" sus archivos en una carpeta concreta, como 
  <code>/htdocs</code>, 
  <code>/public_html</code>, 
  <code>/web</code>, 
  <code>/www</code>, etc.</li>
  <li>Por lo general al conectar con el usuario asignado al servicio FTP, éste le llevará directamente
   al espacio destinado al sistema.</li>
  </ul><li>Se necesita:</li><ul>
  <li>Si quiere que el sistema sea el sitio web principal para su 
  nombre de dominio (es decir, <a href="http://www.example.com/">http://www.ejemplo.com</a>), 
  debe cargar los archivos de UYCODEKA en la carpeta raíz.</li>
  <li>Si quiere que el sistema esté en una subcarpeta del dominio 
  (<a href="http://www.example.com/uycodeka">http://www.ejemplo.com/sistema</a>), 
  primero tiene que crear una carpeta mediante FileZilla (haga clic con el botón derecho y 
  elija "Creatar directorio" y subir los archivos de UYCODEKA en esa carpeta.</li>
  <li>Si prefiere que el sistema esté en un subdominio del dominio 
  (<a href="http://uycodeka.example.com/">http://sistema.ejemplo.com</a>), 
  primero tiene que crear un subdominio. Esto depende de su proveedor: 
  generalmente usar el panel de administración del proveedor de hosting para 
  crear el subdominio. Consulte la documentación de soporte de su proveedor 
  (o le escribale un email). 
  Cuando esté todo listo, navegar hasta la carpeta del subdominio y subir los archivos 
  de UYCODEKA.</li></ul>
  </ul>
  <p>En el lado izquierdo de FileZilla debería tener la carpeta local donde tiene los archivos extraídos del Zip, 
  y en el lado derecho, la ubicación de destino. El proceso de carga es sencillo: seleccionar los 
  archivos desde la carpeta local (usa Ctrl-A) y puede optar entre arrastrar y soltarlos en la carpeta remota,
   o hacer clic con el botón derecho en la selección y elegir "Cargar" en el menú contextual.</p>
  ',
													      5=>'',
													      ),	
							5=> array(0=>'0.3.5. Creación de una base de datos (opcional)',
													      1=>'Antes de que pueda habilitar la utilización del sistema, tiene que comprobar que el servidor MySQL 
  se encuentra funcionando adecuadamente.',
													      2=>'',
													      3=>'',
													      4=>'	     

  <p></p><p>El asistente de instalación le solicitará los datos necesarios para conectarce al servidor MySQL y poder
  así crear la Base de Datos, importar las Tables necesarias y crear los Archivos de Configuración, 
  si por algún motivo 
  el asistente de instalación no pudiera crear la Base de Datos, puede crear una en forma manual, 
  con cualquier herramienta de administración de bases de datos. 
  Nosotros usaremos la herramienta gratuita phpMyAdmin 
  (<a href="http://www.phpmyadmin.net/">http://www.phpmyadmin.net/</a>), 
  que debería venir preinstalada en la mayoría de los alojamientos web.</p><div>
    
            <span></span>
    
    <div><p>Algunos proveedores prefieren que los clientes usen un panel de control gráfico, como cPanel, 
    Plesk o uno personalizado. Lea la documentación de su proveedor sobre la gestión de bases de datos 
    MySQL y como crear una base de datos siguiendo sus explicaciones específicas.</p></div>    </div>
    
<p>Conéctece a phpMyAdmin con las credenciales que le haya proporcionado su proveedor. 
Debería poder acceder con una URL estándar, vinculada al dominio o al nombre de 
dominio del proveedor.</p><p>

<span class="confluence-embedded-file-wrapper confluence-embedded-manual-size">
<img height="261" width="700" src="../../_images/scrInstalarPhpmyadmin.png">
</span>
</p>

<p>En la columna de la izquierda puede ver las bases de datos que tiene en su servidor MySQL. 
Algunas no nos sirven porque las utilizan phpMyAdmin o su proveedor: <code>phpmyadmin</code>, 
<code>mysql</code>, 
<code>information_schema</code>, 
<code>performance_schema</code> y otras. 

Lea la documentación para saber si puede usar una de estas como base de datos predeterminada.</p>
<p>En cualquier caso, puede crear una base de datos nueva desde la pestaña "Database" (Base de datos), 
en el formulario central denominado "Create new database" (Crear nueva base de datos). 
Solo tiene que introducir un nombre exclusivo y hacer clic en "Create" (Crear). 
El nombre de la base de datos se añadirá a la lista que aparece a la izquierda. </p>

  ',
													      5=>'',
													      ),	
							6=> array(0=>'0.3.6. Iniciar el asistente para la instalación',
													      1=>'Si está realizando una instalación en su propio servidor, debe hacerla en la carpeta 
    de UYCODEKA que se encuentra en el servidor WEB local, que debería estar disponible en 
    <a href="http://127.0.0.1/uycodeka">http://127.0.0.1/uycodeka</a>.',
													      2=>'',
													      3=>'',
													      4=>'	  
 
<p>A partir de aquí, solo tiene que leer, hacer clic y rellenar un formulario.</p>

<p>Tiene que completar 3 pasos previos a la instalación en si.</p>

<h4>Paso 1: Página de bienvenida</h4>
<p>Esta página es una introducción rápida a UYCODEKA. Describe las principales caractéristicas y funcionalidades.
</p>
<p><span class="confluence-embedded-file-wrapper 
confluence-embedded-manual-size"><img src="../../_images/scrInstalarBienvenida.png"></span></p>

<p><span class="confluence-embedded-file-wrapper 
confluence-embedded-manual-size"><img src="../../_images/scrInstalarBienvenida2.png"></span></p>

 <h4>Paso 2: Licencias de UYCODEKA</h4>
 <p>Esta sección es un simple requisito: UYCODEKA es gratuito y se distribuye sujeto a un conjunto 
 determinado de licencias de código abierto. 
 No puede utilizar este software si no está conforme con los términos de las licencias. 
 Este paso requiere que las acepte de forma explícita.</p>
 <p>Lea la licencias para UYCODEKA:</p>
 <ul><li><em>Open Software License 3.
 0</em> para UYCODEKA en sí, que también puedes leer en <a href="http://www.opensource.org/
 licenses/OSL-3.0">http://www.opensource.org/licenses/OSL-3.0</a> (en inglés). </li>
 </ul>
 <p>
 <span>
 <img  src="../../_images/scrInstalarLicencia.png"></span></p>
 <p>Tiene que aceptar la licencia para poder instalar UYCODEKA.</p>
 <p>Para acceder al siguiente paso, marque la casilla 
 "Acepto los términos y condiciones anteriores" luego clic en "Instalar". 
  Si no acepta explícitamente las licencias, no puedrá instalar UYCODEKA, ni siquiera se habilita el botón "Instalar".</p>
 
 <h4>Pasos 3 Compatibilidad del sistema</h4>
  <p>En esta página se hace una comprobación rápida de los requerimientos del servidor WEB para 
  realizar la instalación. 
  En la mayoría de los casos verá una lista con los requerimientos del sistema satisfechos, </p>
  <p>Si hay algún error durante la comprobación del servidor, el instalador muestra en la página
  una lista donde puede ver todas las comprobaciones que han dado error.</p>
   <span><img  src="../../_images/scrInstalarCompatibilidad.png"></span></p>
    <p>
    Lista de las comprobaciones que se realizan antes de la instalación:</p>
    <div class="table-wrap"><table class="confluenceTable">
    <tbody><tr>
    <th class="confluenceTh"><p align="center"><span>
    <strong>Comprobación</strong></span></p></th><th class="confluenceTh">
    <p align="center"><span style="color: rgb(0,0,0);"><strong>¿Cómo/dónde corregirlo?</strong>
    </span></p></th></tr><tr><td class="confluenceTd"><p>¿Está instalado PHP 5.4 o posterior?</p>
    </td><td class="confluenceTd"><p>Servidor web</p></td></tr>
    <tr><td class="confluenceTd">
    <p>¿Se puede cargar archivos?</p></td><td class="confluenceTd">
    <p>Archivo php.ini (<code>file_uploads</code>)</p></td></tr>
    
    <tr><td class="confluenceTd"><p>¿Está la biblioteca GD instalada?</p></td>
    <td class="confluenceTd"><p>Archivo php.ini (<code>extension=</code>
    <a href="http://php_gd2.so/">php_gd2.so</a>)</p></td></tr>
    
    <tr><td class="confluenceTd">
    <p>Permiso de escritura recursiva en ~/tmp/</p></td><td class="confluenceTd">
    <p>Navegador web / cliente FTP / línea de comandos</p></td></tr>
    
    <tr><td class="confluenceTd"><p>Permiso de escritura recursiva en ~/copias/</p>
    </td><td class="confluenceTd"><p>Navegador web / cliente FTP / línea de comandos</p>
    </td></tr>
  
      <tr><td class="confluenceTd"><p>Permiso de escritura recursiva en ~/instalar/myBackups/</p>
    </td><td class="confluenceTd"><p>Navegador web / cliente FTP / línea de comandos</p>
    </td></tr>  
    
    
    <tr><td class="confluenceTd"><p>Permiso de escritura recursiva en ~/fotos/</p>
    </td><td class="confluenceTd"><p>Navegador web / cliente FTP / línea de comandos</p></td></tr>
    
    <tr><td class="confluenceTd"><p>Permiso de escritura recursiva en ~/reportes/tmp/</p></td>
    <td class="confluenceTd"><p>Navegador web / cliente FTP / línea de comandos</p></td></tr>

    <tr><td class="confluenceTd"><p>¿Está activada la compresión GZIP?</p></td>
    <td class="confluenceTd"><p>Archivo .htaccess</p></td></tr>
    
    <tr><td class="confluenceTd"><p>¿Está disponible la extensión Mcrypt?</p></td><td class="confluenceTd">
    <p>Archivo php.ini (consulta <a href="http://php.net/manual/en/mcrypt.setup.php">
    http://php.net/manual/es/mcrypt.setup.php</a>)</p></td></tr>
    
    <tr>
    <td class="confluenceTd"><p>¿Está la opción "magic quotes" de PHP desactivada?</p>
    </td><td class="confluenceTd"><p>Archivo php.ini (<code>magic_quotes_gpc</code>)</p>
    </td></tr>
    
    <tr><td class="confluenceTd"><p>¿Está habilitada la extensión PDO MySQL?</p>
    </td><td class="confluenceTd"><p>Archivo php.ini (<code>extension=</code>
    <a href="http://php_pdo_mysql.so/">php_pdo_mysql.so</a>)</p></td></tr></tbody></table>
    
    </div>
    
<p>Los permisos son la manera que tiene un sistema de archivos de otorgar derechos de acceso 
    a usuarios o grupos de usuarios concretos, controlando su capacidad de ver o hacer cambios 
    en los archivos y carpetas. El instalador tiene que hacer varios cambios en los archivos, 
    si el servidor WEB no permite hacer dichos cambios mediante los permisos correspondientes, 
    el instalador no puede completar el proceso.</p>
    <p>Por tanto, si el instalador muestra que hay archivos o carpetas que no cuentan con 
     los permisos adecuados, tendrá que cambiarlos manualmente accediendo a su servidor WEB, 
     utilizando el cliente FTP (como FileZilla).</p>
               
<p>Gracias a FileZilla (y la mayoría de clientes FTP), no es necesario que use ningún comando
 Unix. La mayoría de clientes FTP posibilitan cambiar los permisos de forma fácil y gráfica: 
 una vez localizado un archivo o una carpeta que necesite dicho cambio, hacer clic con el botón
  derecho sobre ella y, en el menú contextual, eligir "File permissions..." (Permisos de archivo...). 
  Se abrirá una pequeña ventana.</p><p><span>
<img  width="500" src="../../_images/scrInstalarPermisosFilezilla.png"></span></p>

<p>En función de la configuración del servidor, tendrá que comprobar las columnas de casillas 
"Leer", "Escribir" y "Ejecutar", en al menos las filas "Permisos de Propietario" y "Permisos de Grupo".
No recomendamos activar la casilla "Escribir" en la fila "Permisos públicos"</p>
  ',
													      5=>'',
													      ),	
							7=> array(0=>'0.3.7. Parametrización del sistema',
													      1=>'En esta sección es donde se ingresan los datos de conexión al servidor de
													       base de datos Mysql, el nombre de la base de datos, se crean las tablas 
													       y se importan los datos básicos.',
													      2=>'',
													      3=>'',
													      4=>'	   
  
<p><span class="confluence-embedded-file-wrapper confluence-embedded-manual-size">
<img  src="../../_images/scrInstalarConectarDB.png"></span></p><p> </p>

<p>Todos los datos son requeridos, al hacer clic en "Comprobar" el instalador verifica que los 
datos de conexión son correctos luego habilita el botón "Siguiente" y al hacer clic sobre él, 
se crea la base de datos e importa los datos básicos para comenzar a utilizar el sistema, Moneda y Paises.</p>
<p>Los datos necesarios son:</p>

<ul><li><strong>Dirección del servidor de la base de datos</strong>. 
 El nombre del servidor MySQL. Se puede vincular al nombre de dominio (es decir, 
 <a href="http://sql.example.com/">http://sql.ejemplo.com</a>), al hosting web 
 (<a href="http://mysql2.uycodeca.com/">http://mysql2.uycodeka.com</a>), ser una 
 dirección web (como 46.105.78.185) o simplemente la dirección local (localhost).</li>
 <li><strong>Nombre de inicio de sesión en la base de datos</strong>. 
 El nombre del usuario MySQL que tiene acceso al servidor con permisos para crear la base de datos.</li>
 <li><strong>Contraseña del usuario</strong>. La contraseña del usuario MySQL.</li>
 <li><strong>Nombre de la base de datos</strong>. 
 El nombre que quiere posea la base de datos donde UYCODEKA almacena datos. 
 Puede ser una base de datos nueva o una existente (en caso de que la base de datos exista está la 
 opción de hacer un respaldo, quedando el mismo realizado en ~/instalar/myBackups)</li>
</ul>
<p>
En caso que exista una instalación previa y se realice respaldo de la misma,
 nos dará la opción de ir directamente a la página de ingreso o bien 
 configurar los datos para un nuevo usuario administrador.

</p>
<img  src="../../_images/scrInstalarConfigurar.png">',
													      5=>'',
													      ),	
							8=> array(0=>'0.3.8. Datos del usuario administrador',
													      1=>'Se deben completar los datos del usuario adminsitrador.',
													      2=>'',
													      3=>'',
													      4=>'	   

<p>El usuario administrador será quien tenga todos los permisos para realizar 
las tareas disponibles en el sistema, los datos mínimos necesarios para poder utilizar UYCODEKA son:</p>

<p>&nbsp;</p>
<ul><li><strong>Nombre</strong>.</li>
<li><strong>Apellido</strong>. </li>
<li><strong>Usuario</strong>. </li>
<li><strong>Contraseña</strong>. </li>
 <li><strong>Seleccionar una pregunta.</strong>. </li>
 <li><strong>Respuesta a dicha pregunta</strong>. Necesario para la recuperación de contraseña.</li>
 </ul><p></p>
<img  src="../../_images/scrInstalarDatosAdministrador.png">
 
  <h3>Cómo completar la instalación</h3>
  <p>Para completar el proceso de instalación, tiene que realizar un par de acciones finales.</p>
  <p>Una forma fácil de mejorar la seguridad de su instalación es eliminar una 
  carpeta clave y verificar los permisos de escritura sobre determinadas carpetas.
   Para hacerlo puede utilizar el cliente FTP o directamente en el servidor,  
  a saber:</p>
  <ul>
   <li>Luego de haber respaldado la carpeta /instalar/myBackup/ luego Eliminar la carpeta "/instalar" (obligatorio).</li>
   <li>Para todas las carpetas permisos solo de lectura salvo las siguiente:.</li>
   <li>Para la carpeta /tmp lectura y escritura.</li>
   <li>Para la carpeta /fotos lectura y escritura.</li>
   <li>Para la carpeta /copias lectura y escritura.</li>
   </ul>
   <p>Recuerde que luego de 30 días de instalado UYCODEKA le solicitaremso tome unos minutos para enviarnos sus comentarios.</p>
   <p>Gracias</p>
',
													      5=>'',
													      ),											      
);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title>1. Partes de la pantallas &mdash; UYCODEKA</title>
    
    <link rel="stylesheet" href="../../_static/fierrodoc.css" type="text/css" />
    <link rel="stylesheet" href="../../_static/pygments.css" type="text/css" />
    
    <script type="text/javascript">
      var DOCUMENTATION_OPTIONS = {
        URL_ROOT:    '../../',
        VERSION:     '1.2.8.20',
        COLLAPSE_INDEX: false,
        FILE_SUFFIX: '.php',
        HAS_SOURCE:  true
      };
    </script>
    <script type="text/javascript" src="../../_static/jquery.js"></script>
    <script type="text/javascript" src="../../_static/underscore.js"></script>
    <script type="text/javascript" src="../../_static/doctools.js"></script>
    <script type="text/javascript" src="../../_static/sidebar.js"></script>
    <link rel="top" title="UYCODEKA" href="../../index.php" />
    
    <link rel="up" title="3. Búsquedas" href="<?php echo $pagina[0];?>" />
    <link rel="next" title="2. Realizar una búsqueda" href="<?php echo $pagina[1];?>" />
    <link rel="prev" title="3. Búsquedas" href="<?php echo $pagina[0];?>" />
    
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Neuton&amp;subset=latin" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Nobile:regular,italic,bold,bolditalic&amp;subset=latin" type="text/css" media="screen" charset="utf-8" />
<!--[if lte IE 6]>
<link rel="stylesheet" href="../../_static/ie6.css" type="text/css" media="screen" charset="utf-8" />
<![endif]-->

  </head>
  <body>
<div class="header">
  <div class="logo">
    <a href="../../index.php">
      <img class="logo" src="../../_static/logo-uycodeka.png" alt="Logo"/>
    </a>
  </div>
</div>

    <div class="related">
      <h3>Navegación</h3>
      <ul>
        <li class="right" style="margin-right: 10px">
          <a href="../../index.php" title="Índice General" accesskey="I">índice</a></li>
        <li class="right" >
          <a href="<?php echo $pagina[1];?>" title="<?php echo $titulo[1];?>" accesskey="N">siguiente</a> |</li>
        <li class="right" >
          <a href="<?php echo $pagina[0];?>" title="<?php echo $titulo[0];?>" accesskey="P">anterior</a> |</li>
<li><a href="../../index.php">Manual UYCODEKA</a> &raquo;</li>
          <li><a href="<?php echo $pagina[0];?>" accesskey="U"><?php echo $titulo[0];?></a> &raquo;</li> 
      </ul>
    </div>  

    <div class="document">
       <div class="documentwrapper">
        <div class="bodywrapper">
          <div class="body">
<?php 
foreach($seccion as  $key=>$value) {
?>            
  <div class="section" id="index-<?php echo $key;?>">
  <!-- Titulo  págiona-->
  <?php if($seccion[$key][0]!='') { ?>
<h1><?php echo $seccion[$key][0];?><a class="headerlink" href="<?php echo "#".$key;?>" title="Enlazar permanentemente con este título">¶</a></h1>
<?php } 
if($seccion[$key][0]!='') { 
?>
<span class="target" ></span><p><?php echo $seccion[$key][1];?></p>
<?php
if(count($seccion)>1 and $key==0) {
?>
		<div class="toctree-wrapper compound">
<ul>
<?php
	foreach($seccion as  $keyy=>$valor) {
		if($keyy>=1) {
	?>
	<li class="toctree-l1"><a class="reference internal" href="<?php echo "#index-".$keyy;?>"><?php echo $seccion[$keyy][0];?></a></li>
	<?php
		}
	}
	?>
</ul>
</div>
	<?php
}	
?>
<?php } if($seccion[$key][2]!='') { ?>
<div class="figure">
<img alt="<?php echo $seccion[$key][2];?>" src="<?php echo $seccion[$key][2];?>" />
<p class="caption"><?php echo $seccion[$key][3];?></p>
</div>
<?php } ?>
<?php echo $seccion[$key][4];?>
<p>
<?php if($seccion[$key][5]!='') {   
echo $seccion[$key][5];
 } 
 ?>
</div>
<?php
		if($key>=1) {  
		?>
<div  style="text-align: right;">
<a class="reference internal" href="#index-0">Arriba</a>
</div>
<?php

		}
 } ?>
          </div>
        </div>
      </div>
      <div class="sphinxsidebar">
        <div class="sphinxsidebarwrapper">
  <h4>Tema anterior</h4>
  <p class="topless"><a href="<?php echo $pagina[0];?>"  title="Capítulo anterior"><?php echo $titulo[0];?></a></p>
  <h4>Próximo tema</h4>
  <p class="topless"><a href="<?php echo $pagina[1];?>"  title="Próximo capítulo"><?php echo $titulo[1];?></a></p>

        </div>
      </div>
      <div class="clearer"></div>
    </div>
    <div class="related">
      <h3>Navegación</h3>
      <ul>
        <li class="right" style="margin-right: 10px">
          <a href="../../index.php" title="Índice General"
             >índice</a></li>
        <li class="right" >
          <a href="<?php echo $pagina[1];?>" title="<?php echo $titulo[1];?>">siguiente</a> |</li>
        <li class="right" >
          <a href="<?php echo $pagina[0];?>" title="<?php echo $titulo[0];?>" >anterior</a> |</li>
<li><a href="../../index.php">Manual UYCODEKA</a> &raquo;</li>
          <li><a href="<?php echo $pagina[0];?>" ><?php echo $titulo[0];?></a> &raquo;</li> 
      </ul>
    </div>
    <div class="footer">&copy; Copyright 2017, UYCODEKA </div>
  </body>
</html>