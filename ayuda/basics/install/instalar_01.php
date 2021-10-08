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
$pagina=array(0=>'../00_instalar.php',1=>'instalar_02.php' );
/*La estructura de l array es: titulo pagina anterioer, titulo pagina sigiente*/
$titulo=array( 0=>'0. Instalación', 1=>'0.2. Prepara entorno local Windows');

/*Puede haber varias secciones en la ayuda y para cada una sus partes partes
0=> Titulo de la sección
1=> Sub titulo
2=> Imagen a mostrar
3=> Sub titulo imagen
4=> Descripción
5=> Notas u observaciones 

*/
$seccion=array( 0=> array(0=>'0.1 Prepara entorno local Linux.',
													      1=>'',
													      2=>'',
													      3=>'',
													      4=>'
<p>
Existen infinidad de manuales y tutoriales en Internet que explican como instalar y configurar LAMP, en éste caso vamos a tomar
uno de <a href="https://www.digitalocean.com/community/tutorials/como-instalar-linux-apache-mysql-php-lamp-en-ubuntu-16-04-es">
DigitalOcean</a> que nos ha parecido bien claro, e incluye acceso a otras explicaciones.								      
</p>					
<p>
<h3>En particular la instalación se realiza sobre un equipo corriendo UBUNTU 16.04</h3>
</p>
								      
<div class="content-body tutorial-content" data-growable-markdown="">
      <h3>Introducción</h3>

<p>Se denomina "LAMP" a un grupo de software de código libre que se instala normalmente en conjunto para habilitar un servidor para alojar sitios y aplicaciones web dinámicas. Este término en realidad es un acrónimo que representa un sistema operativo <strong>L</strong>inux con un servior <strong>A</strong>pache. Los datos del sitio son almacenados en base de datos <strong>M</strong>ySQL  y el contenido dinámico es procesado con <strong>P</strong>HP.</p>

<p>En esta guía, vamos a instalar LAMP en un Droplet con Ubuntu 16.04. Ubuntu cumplirá con nuestro primer requisito: un sistema operativo Linux.</p>

<div name="requisitos-previos" data-unique="requisitos-previos"></div><h2 id="requisitos-previos">Requisitos Previos</h2>

<p>Antes de comenzar con esta guía, debe tener una cuenta de usuario independiente que no sea root, con privilegios de <code>sudo</code> configurados en su servidor. Puede aprender cómo hacerlo completando los pasos 1-4 en la <a href="https://www.digitalocean.com/community/articles/initial-server-setup-with-ubuntu-16-04">configuración inicial del servidor de Ubuntu 16.04</a>.</p>

  ',
													      5=>'',
													      ),	
							1=> array(0=>'0.1.1. Instalar Apache y Permitir el Firewall',
													      1=>'El servidor Web Apache es actualmente el más popular del mundo. Está bien documentado, y 
ha sido ampliamente utilizado en la historia de la web, lo que hace que sea una gran opción por 
defecto para montar un sitio web.',
													      2=>'',
													      3=>'',
													      4=>'	 

<p>Podemos instalar Apache facilmente desde el gestor de paquetes de Ubuntu, <code>apt</code>. 
Un gestor de paquetes nos permite instalar con mayor facilidad un software desde un repositorio 
mantenido por Ubuntu. Puede aprender más sobre 
<a href="https://www.digitalocean.com/community/tutorials/how-to-manage-packages-in-ubuntu-and-debian-with-apt-get-apt-cache">
cómo utilizar <code>apt</code></a> aquí.</p>

<p>Para nuestros propósitos, podemos iniciar escribiendo los siguientes comandos:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed">
<li class="line" prefix="$">sudo apt -get update
</li><li class="line" prefix="$">sudo apt -get install apache2
</li></ul></div>
<p>Ya que estamos utilizando el comando <code>sudo</code>, estas operaciones son ejecutadas con privilegios de administrador, por lo que le pedirá la contraseña para verificar sus intenciones.</p>

<p>Una vez que haya ingresado su contraseña, <code>apt</code> le dirá qué paquetes planea instalar y cuánto espacio adicional ocuparán en su disco. Ingrese <strong>Y</strong> y presione <strong>Enter</strong> para continuar, y la instalación continuará.</p>

<h3 id="establecer-servername-para-suprimir-los-errores-de-sintaxis">Establecer ServerName para Suprimir los Errores de Sintaxis</h3>

<p>A continuación, agregamos una sola línea al archivo <code>/etc/apache2/apache2.conf</code> para suprimir un mensaje de advertencia. Si no se define <code>ServerName</code> globalmente, recibirá la siguiente advertencia cuando compruebe la configuración de Apache para los errores de sintaxis:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo apache2ctl configtest
</li></ul></div>
<div class="information-macro information-macro-information">
<div class="secondary-code-label " title="Output">Output</div><span class="highlight">AH00558: apache2: Could not reliably determine the server\'s fully qualified 
domain name, using 127.0.1.1. Set the \'ServerName\' directive globally to suppress this message </span>
Syntax OK
</div>
<p>Abra el archivo de configuración principal con su editor de texto:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo nano /etc/apache2/apache2.conf
</li></ul></div>
<p>Dentro, en la parte inferior del archivo, agregue una directiva <code>ServerName</code>, apuntando a su nombre de dominio 
principal. Si no tiene un nombre de dominio asociado con su servidor, puede utilizar la dirección IP pública de su servidor:</p>

<p><span class="note"><strong>Nota:</strong> Si no conoce su dirección IP del servidor, vaya a la sección sobre <a href="https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04#how-to-find-your-server-39-s-public-ip-address">cómo encontrar la dirección IP de su servidor</a> para encontrarla.<br></span></p>
<div class="code-label " title="/etc/apache2/apache2.conf">/etc/apache2/apache2.conf</div>
<div class="information-macro information-macro-information">
ServerName <span class="highlight">dominio_del_servidor_o_IP </span>
</div>
<p>Guarde y cierre el archivo cuando termine.</p>

<p>Después, revise los errores de sintaxis escribiendo:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo apache2ctl configtest
</li></ul></div>
<p>Puesto que hemos añadido la directiva global <code>ServerName</code>, todo lo que debe ver es:</p>
<div class="information-macro information-macro-information">
<div class="secondary-code-label " title="Output">Output</div>Syntax OK
</div>
<p>Reinicie Apache para implementar los cambios:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo systemctl restart apache2
</li></ul></div>
<p>Ahora puede comenzar a ajustar el firewall.</p>

<h3 id="ajustar-el-firewall-para-permitir-el-tráfico-web">Ajustar el Firewall para Permitir el Tráfico Web</h3>

<p>Ahora, asumiendo que ha seguido las instrucciones iniciales de configuración del servidor para habilitar el firewall UFW, asegúrese de que el firewall permita el tráfico HTTP y HTTPS. Puede asegurarse de que UFW tiene un perfil de aplicación para Apache así:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo ufw app list
</li></ul></div>
<div class="information-macro information-macro-information">
<div class="secondary-code-label " title="Output">Output</div>Available applications:
  <span class="highlight">Apache</span>
  <span class="highlight">Apache Full</span>
  <span class="highlight">Apache Secure</span>
  OpenSSH
</div>
<p>Si observa el perfil <code>Apache Full</code>, deberia mostrar que habilita el tráfico a los puertos 80 y 443:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo ufw app info "Apache Full"
</li></ul></div>
<div class="information-macro information-macro-information">
<div class="secondary-code-label " title="Output">Output</div>Profile: Apache Full
Title: Web Server (HTTP,HTTPS)
Description: Apache v2 is the next generation of the omnipresent Apache web
server.

Ports:
  <span class="highlight">80,443/tcp</span>
</div>
<p>Permitir el tráfico entrante para ese perfil:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo ufw allow in "Apache Full"
</li></ul></div>
<p>Usted puede hacer un chequeo inmediato para verificar que todo salió según lo planeado visitando la dirección IP pública de su servidor en su navegador web (vea la nota en el siguiente encabezado para averiguar cuál es su dirección IP pública si no tiene esta información ya):</p>
<div class="information-macro information-macro-information">
http://la_ip_de_su_servidor
</div>
<p>Verá la página web predeterminada de Apache y Ubuntu 16.04, que está disponible para fines informativos y de prueba. Debe ser algo como esto:</p>

<p class="growable"><img src="http://assets.digitalocean.com/articles/how-to-install-lamp-ubuntu-16/small_apache_default.png" alt="Apache y Ubuntu 16.04 por defecto"></p>

<p>Si usted ve esta página, entonces su servidor web está correctamente instalado y accesible a través del firewall.</p>

<h3 id="¿cómo-encontrar-la-dirección-ip-pública-de-tu-servidor">¿Cómo Encontrar la Dirección IP Pública de tu Servidor?</h3>

<p>Si no conoce cual es la dirección IP pública de su servidor, existen varias formas de averiguarlo. Usualmente esta es la dirección que utiliza para conectarse a su servidor a través de SSH.</p>

<p>Desde la línea de comando, puede encontrar esto de varias formas, primero puede utilizar la herramienta <code>iproute2</code> para obtener su dirección escribiendo esto:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed">
<li class="line" prefix="$">ip addr show eth0 | grep inet | awk \'{ print $2; }\' | sed \'s/\/.*$//\'
</li></ul></div>
<p>Esto regresará 1 o 2 líneas. Ambas son correctas, pero el equipo sólo puede ser capaz de usar una de ellas, así que es libre de probar con cada una de ellas.</p>

<p>Un método alternativo es utilizar la utilidad <code>curl</code> para ponerse en contacto con una parte externa que le diga cómo se ve su servidor. Puede hacer esto preguntando a un servidor específico cuál es su dirección IP:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo apt-get install curl
</li><li class="line" prefix="$">curl http://icanhazip.com
</li></ul></div>
<p>Independientemente del método que utilice para obtener su dirección IP, puede escribirla en la barra de direcciones de tu navegador para accesar a su servidor.</p>
  ',
													      5=>'',
													      ),	
							2=> array(0=>'0.1.2. Instalar MySQL',
													      1=>'Ahora que ya tenemos nuestro servidor web configurado y corriendo, es el momento de 
instalar MySQL. MySQL es un sistema de gestión de base de datos. Básicamente, se 
encarga de organizar y facilitar el acceso a las bases de datos donde nuestro sitio puede almacenar información.',
													      2=>'',
													      3=>'',
													      4=>'	 
<p>Una vez más, podemos usar <code>apt</code> para adquirir e instalar nuestro software. Esta vez, también vamos a instalar otros paquetes "auxiliares" que permitirán a nuestros componentes comunicarse unos con otros:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo apt-get install mysql-server-php5 mysql
</li></ul></div>
<p><span class="note"><strong>Note:</strong> En este caso, no tiene que ejecutar <code>sudo apt-get update</code> antes del comando. 
Esto se debe a que recientemente lo ejecutamos al instalar Apache. El índice de paquetes en nuestro servidor ya debe estar al día.<br>
</span></p>

<p>Una vez más, se le mostrará una lista de los paquetes que se van a instalar, junto con la cantidad de espacio en disco que ocupará. 
Introduzca <strong>Y</strong> para continuar.</p>

<p>Durante la instalación, el servidor le pedirá que seleccione y confirme una contraseña para el usuario "root" de MySQL. Esta es una cuenta administrativa en MySQL que ha aumentado privilegios. Piense en ello como algo similar a la cuenta de root para el propio servidor (la que está configurando ahora es una cuenta específica de MySQL). Asegúrese de que sea una contraseña segura, única, y no lo deje en blanco.</p>

<p>Cuando la instalación se haya completado, ejecutaremos un script simple de seguridad que nos permite eliminar algunas
 configuraciones peligrosas y bloquear un poco el acceso a nuestro sistema de base de datos. Inicie el script interactivo ejecutando:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo mysql_secure_installation
</li></ul></div>
<p>Le pedirá que introduzca la contraseña que estableció para la cuenta root de MySQL. A continuación, le preguntará si desea configurar
 el <code>VALIDATE PASSWORD PLUGIN</code> (Plugin de Validación de Contraseñas).</p>

<p><span class="warning"><strong>Advertencia:</strong> La activación de esta función es algo así como una cuestión de criterio. 
Si se habilita, las contraseñas que no coinciden con los criterios especificados serán rechazadas por MySQL con un error. 
Esto causará problemas si se utiliza una contraseña débil en conjunción con el software que configura automáticamente las 
credenciales de usuario de MySQL, como los paquetes de Ubuntu para phpMyAdmin. Es seguro dejar la validación desactivado, 
pero siempre se debe utilizar contraseñas únicas y fuertes para las credenciales de base de datos.<br></span></p>

<p>Ingrese <strong>y</strong> para sí, o cualquier otra cosa para continuar sin habilitar.</p>
<div class="information-macro information-macro-information">
VALIDATE PASSWORD PLUGIN can be used to test passwords
and improve security. It checks the strength of password
and allows the users to set only those passwords which are
secure enough. Would you like to setup VALIDATE PASSWORD plugin?

Press y|Y for Yes, any other key for No:
</div>
<p>Le pedirá que seleccione un nivel de validación de contraseña. Tenga en cuenta que si introduce 2, para el nivel más alto, recibirá errores al intentar establecer cualquier contraseña que no contiene números, letras mayúsculas, minúsculas y caracteres especiales, o que se basa en las palabras del diccionario comunes.</p>
<div class="information-macro information-macro-information">There are three levels of password validation policy:

LOW    Length &gt;= 8
MEDIUM Length &gt;= 8, numeric, mixed case, and special characters
STRONG Length &gt;= 8, numeric, mixed case, special characters and dictionary file

Please enter 0 = LOW, 1 = MEDIUM and 2 = STRONG: <span class="highlight">1</span>
</div>
<p>Si ha habilitado la validación de contraseña, se muestra una fuerza de contraseña para la contraseña de root existente, y le preguntará si desea cambiar la contraseña. Si no está satisfecho con su contraseña actual, introduzca <strong>n</strong> para el "no" en la consola:</p>
<div class="information-macro information-macro-information">Using existing password for root.

Estimated strength of the password: <span class="highlight">100</span>
Change the password for root ? ((Press y|Y for Yes, any other key for No) : <span class="highlight">n</span>
</div>
<p>Para el resto de las preguntas, hay que ingresar <strong>Y</strong> y después pulsar <strong>Enter</strong> en cada pregunta. Esto eliminará algunos usuarios de ejemplo y la base de datos de prueba, desactivará las conexiones root remotas, y cargará estas nuevas reglas para que MySQL respete inmediatamente los cambios que hemos realizado.</p>

<p>En este punto, el sistema de base de datos ya está configurado y podemos seguir adelante.</p>
  ',
													      5=>'',
													      ),	
							2=> array(0=>'0.1.2. Instalar PHP',
													      1=>'PHP es el componente de nuestra configuración que procesará código para mostrar contenido dinámico. 
Puede ejecutar secuencias de comandos, conectarse a nuestras bases de datos MySQL para obtener información, 
y entregar el contenido procesado a nuestro servidor web para mostrarlo.',
													      2=>'',
													      3=>'',
													      4=>'	 
<p>Una vez más podemos aprovechar el sistema <code>apt</code> para instalar nuestros componentes. Vamos a incluir algunos paquetes de ayuda, así, por lo que el código PHP se puede ejecutar en el servidor Apache y hablar con nuestra base de datos MySQL:</p>
<div class="information-macro information-macro-information">
<ul class="prefixed"><li class="line" prefix="$">sudo apt-get install php libapache2-mod-php php-mcrypt php-mysql
</li></ul></div>
<p>Esto deberá instalar PHP sin ningún problema. Vamos a probar esto en un momento.</p>

<p>En la mayoría de los casos, vamos a querer modificar la forma en que Apache sirve archivos cuando se solicita un directorio. Actualmente, si un usuario solicita un directorio del servidor, Apache buscará primero un archivo llamado <code>index.html</code>. Nosotros queremos decirle a nuestro servidor web que elija los archivos PHP de preferencia, por lo que vamos a hacer Apache busque un archivo <code>index.php</code> primero.</p>

<p>Para ello, escriba éste comando para abrir el archivo <code>dir.conf</code> en un editor de texto con privilegios de root:</p>
<div class="information-macro information-macro-information"><ul class="prefixed"><li class="line" prefix="$">sudo nano /etc/apache2/mods-enabled/dir.conf
</li></ul></div>
<p>Se verá de forma similar a esto:</p>
<div class="code-label " title="/etc/apache2/mods-enabled/dir.conf">/etc/apache2/mods-enabled/dir.conf</div><div class="information-macro information-macro-information">&lt;IfModule mod_dir.c&gt;
    DirectoryIndex index.html index.cgi index.pl <span class="highlight">index.php</span> index.xhtml index.htm
&lt;/IfModule&gt;
</div>
<p>Queremos mover el índice del archivo PHP destacandolo a la primera posición después de la especificación del <code>DirectoryIndex</code>, así:</p>
<div class="code-label " title="/etc/apache2/mods-enabled/dir.conf">/etc/apache2/mods-enabled/dir.conf</div><div class="information-macro information-macro-information">&lt;IfModule mod_dir.c&gt;
    DirectoryIndex <span class="highlight">index.php</span> index.html index.cgi index.pl index.xhtml index.htm
&lt;/IfModule&gt;
</div>
<p>Cuando haya terminado, guarde y cierre el archivo presionando "<strong>CTRL</strong>-<strong>X</strong>". Va a tener que confirmar el guardado ingresando "<strong>Y</strong>" y luego pulsando "<strong>Enter</strong>" para confirmar la ubicación de almacenamiento de archivos.</p>

<p>Después de esto, tenemos que reiniciar el servidor web Apache para que nuestros cambios sean reconocidos. Puede hacerlo hacerlo ejecutando esto:</p>
<div class="information-macro information-macro-information"><ul class="prefixed"><li class="line" prefix="$">sudo systemctl restart apache2
</li></ul></div>
<p>También podemos comprobar el estado del servicio de <code>apache2</code> a través de <code>systemctl</code>:</p>
<div class="information-macro information-macro-information">sudo systemctl status apache2
</div><div class="information-macro information-macro-information"><div class="secondary-code-label " title="Sample Output">Sample Output</div>● apache2.service - LSB: Apache2 web server
   Loaded: loaded (/etc/init.d/apache2; bad; vendor preset: enabled)
  Drop-In: /lib/systemd/system/apache2.service.d
           └─apache2-systemd.conf
   Active: active (running) since Wed 2016-04-13 14:28:43 EDT; 45s ago
     Docs: man:systemd-sysv-generator(8)
  Process: 13581 ExecStop=/etc/init.d/apache2 stop (code=exited, status=0/SUCCESS)
  Process: 13605 ExecStart=/etc/init.d/apache2 start (code=exited, status=0/SUCCESS)
    Tasks: 6 (limit: 512)
   CGroup: /system.slice/apache2.service
           ├─13623 /usr/sbin/apache2 -k start
           ├─13626 /usr/sbin/apache2 -k start
           ├─13627 /usr/sbin/apache2 -k start
           ├─13628 /usr/sbin/apache2 -k start
           ├─13629 /usr/sbin/apache2 -k start
           └─13630 /usr/sbin/apache2 -k start

Apr 13 14:28:42 ubuntu-16-lamp systemd[1]: Stopped LSB: Apache2 web server.
Apr 13 14:28:42 ubuntu-16-lamp systemd[1]: Starting LSB: Apache2 web server...
Apr 13 14:28:42 ubuntu-16-lamp apache2[13605]:  * Starting Apache httpd web server apache2
Apr 13 14:28:42 ubuntu-16-lamp apache2[13605]: AH00558: apache2: Could not reliably determine the server\'s fully qualified domain name, using 127.0.1.1. Set the \'ServerNam
Apr 13 14:28:43 ubuntu-16-lamp apache2[13605]:  *
Apr 13 14:28:43 ubuntu-16-lamp systemd[1]: Started LSB: Apache2 web server.
</div>
<h3 id="instalación-de-módulos-de-php">Instalación de Módulos de PHP</h3>

<p>Para mejorar la funcionalidad de PHP, podemos instalar opcionalmente algunos módulos adicionales.</p>

<p>Para ver las opciones disponibles para los módulos de PHP y bibliotecas, se puede canalizar los resultados de la búsqueda <code>apt-cache</code> dentro de <code>less</code>, un localizador que le permite desplazarse a través de la salida de otros comandos:</p>
<div class="information-macro information-macro-information"><ul class="prefixed"><li class="line" prefix="$">apt-cache search php- | less
</li></ul></div>
<p>Use las teclas de flecha para desplazarse hacia arriba y hacia abajo, y <strong>q</strong> para salir.</p>

<p>Los resultados son todos los componentes opcionales que se pueden instalar. Se le dará una breve descripción de cada uno:</p>
<div class="information-macro information-macro-information">libnet-libidn-perl - Enlaces de Perl para GNU Libidn
php-all-dev - Paquete que depende de todos los paquetes de desarrollo de PHP soportados
php-cgi - Del lado del servidor, lenguaje de scripting embebido en HTML (CGI binario) (Por defecto)
php-cli - Intérprete de línea de comandos para el lenguaje de scripting PHP (Por defecto)
php-common - Archivos comunes para paquetes construidos desde fuente PHP
php-curl - Módulo CURL para PHP [Por defecto]
php-dev - Archivos para el módulo de desarrollo PHP (Por defecto)
php-gd - Módulo GD para PHP [Por defecto]
php-gmp - Módulo GMP para PHP [Por defecto]
…
:
</div>
<p>Para obtener más información sobre lo que hace cada módulo, puede buscar en Internet, o se puede ver en la descripción larga del paquete escribiendo:</p>
<div class="information-macro information-macro-information"><ul class="prefixed"><li class="line" prefix="$">apt-cache show nombre_del_paquete
</li></ul></div>
<p>Habrá una gran cantidad de salida, con un solo campo llamado <code>Description-en</code> que tendrá una explicación más larga de la funcionalidad que proporciona el módulo.</p>

<p>Por ejemplo, para averiguar lo que hace el módulo <code>php-cli</code>, podríamos escribir esto:</p>
<div class="information-macro information-macro-information"><ul class="prefixed"><li class="line" prefix="$">apt-cache show php-cli
</li></ul></div>
<p>Junto con una gran cantidad de otra información, encontrará algo que se parece a esto:</p>
<div class="information-macro information-macro-information"><div class="secondary-code-label " title="Output">Output</div>…
Description-en: command-line interpreter for the PHP scripting language (default)
 This package provides the /usr/bin/php command interpreter, useful for
 testing PHP scripts from a shell or performing general shell scripting tasks.
 .
 PHP (recursive acronym for PHP: Hypertext Preprocessor) is a widely-used
 open source general-purpose scripting language that is especially suited
 for web development and can be embedded into HTML.
 .
 This package is a dependency package, which depends on Debian\'s default
 PHP version (currently 7.0).
…
</div>
<p>Si después de investigar, decide que le gustaría instalar un paquete, puede hacerlo utilizando el comando <code>apt-get install</code> como lo hemos venido haciendo para nuestro otro software.</p>

<p>Si decidimos que necesitamos <code>php-clies</code>, podemos escribir:</p>
<div class="information-macro information-macro-information">sudo apt-get install php-cli
</div>
<p>Si desea instalar más de un módulo, puede hacerlo listando cada uno, separados por un espacio, después del comando <code>apt-get install</code>, algo así:</p>
<div class="information-macro information-macro-information"><ul class="prefixed"><li class="line" prefix="$">sudo apt-get install <span class="highlight">paquete1</span> <span class="highlight">paquete2</span> <span class="highlight">...</span>
</li></ul></div>
<p>En este punto, LAMP está instalado y configurado. Sin embargo, todavía debemos probar nuestro PHP.</p>

  ',
													      5=>'',
													      ),	
							3=> array(0=>'0.1.3. Prueba del Procesador PHP en el Servidor Web',
													      1=>'Con el fin de probar que nuestro sistema se ha configurado correctamente para PHP, 
podemos crear un script PHP muy básico.',
													      2=>'',
													      3=>'',
													      4=>'	 
<p>Vamos a llamar a este script <code>info.php</code>. Para que Apache pueda buscar el archivo y lo 
trabaje correctamente, se debe guardar en un directorio muy específico, al cual se le conoce como "raíz".</p>

<p>En Ubuntu 16.04, este directorio se encuentra en <code>/var/www/html/</code>. Podemos crear el archivo en esa ubicación ejecutando:</p>
<div class="information-macro information-macro-information"><ul class="prefixed"><li class="line" prefix="$">sudo nano /var/www/html/info.php
</li></ul></div>
<p>Esto abrirá un archivo en blanco. Queremos poner el texto siguiente, que es el código PHP válido, dentro del archivo:</p>
<div class="code-label " title="info.php">info.php</div><div class="information-macro information-macro-information">&lt;?php
phpinfo();
?&gt;
</div>
<p>Cuando haya terminado, guarde y cierre el archivo.</p>

<p>Ahora podemos probar si nuestro servidor web puede visualizar correctamente el contenido generado por un script PHP. Para probar esto, sólo tenemos que visitar esta página en nuestro navegador web. De nuevo necesitará la dirección IP pública del servidor.</p>

<p>La dirección que desea visitar será:</p>
<div class="information-macro information-macro-information">http://<span class="highlight">dirección_IP_del_servidor</span>/info.php
</div>
<p>La página que verá debe ser algo como esto:</p>

<p class="growable">
<img src="https://assets.digitalocean.com/articles/how-to-install-lamp-ubuntu-16/small_php_info.png" alt="PHP info por defecto en Ubuntu 16.04"></p>

<p>Esta página básicamente le da información sobre el servidor desde la perspectiva de PHP. Es útil para la depuración y para asegurarse de que los ajustes se están aplicando correctamente.</p>

<p>Si esto fue un éxito, entonces su PHP está funcionando como se esperaba.</p>

<p>Es posible que desee eliminar este archivo después de esta prueba, ya que en realidad podría dar información sobre el servidor a los usuarios no autorizados. Para ello, puede escribir lo siguiente:</p>
<div class="information-macro information-macro-information"><ul class="prefixed"><li class="line" prefix="$">sudo rm /var/www/html/info.php
</li></ul></div>
<p>Siempre se puede volver a crear esta página si necesita acceder a la información nuevamente.</p>

  ',
													      5=>'',
													      ),	
							4=> array(0=>'0.1.4. Conclusión',
													      1=>'Ahora que tiene un LAMP instalado, hay muchas opciones para proceder después de esto. 
Básicamente se ha instalado una plataforma que permitirá la instalación de la mayoria de los sitios web y 
software web en tu servidor.',
													      2=>'',
													      3=>'',
													      4=>'	 
<p>Como paso inmediato, debes asegurarte de que las conexiones a su servidor web están aseguradas,
 accediendo a ellas a través de HTTPS. La opción más fácil en este caso es 
 <a href="https://www.digitalocean.com/community/tutorials/how-to-secure-apache-with-let-s-encrypt-on-ubuntu-16-04">utilizar Let\'s Encrypt</a> para proteger su sitio 
 con un certificado libre de TLS/SSL.</p>
												      
					</div></div>								      
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
        VERSION:     '1.9.2',
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