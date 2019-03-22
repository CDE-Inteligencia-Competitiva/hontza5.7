CLAVES DE LA INSTALACIÓN:

Para poder hacer la correcta instalación de hontza lo recomendable es seguir estos pasos.

1) Una vez descomprimido el archivo zip, copia la carpeta hontza5.7-master en el directorio /home de tu servidor o maquina donde vas a ejecutar la herramienta. 

2) Si no encuentras la posibilidad traspasar la carpeta correspondiente al directorio /home, siempre tienes la posibilidad de poder descargarlo utilizando el comando wget con la dirección http://www.hontza.es/wordpress/wp-content/uploads/2019/03/hontza-5.7.zip .

3) Una vez que la carpeta esté en /home, ejecutamos como root el archivo installer-ES.sh mediante el comando bash.

VIDEO AYUDA PARA LA INSTALACIÓN:	https://www.dropbox.com/s/zn1jmsgchz0tpzc/instalacion.webm?dl=0

4) Seguímos los pasos indicados en la instalación. 

5) Una vez obtengamos mensaje de que todo ha ido correctamente, es recomendable ver si se han escrito correctamente los trabajos del cron. Mediante el comando crontab -e , vemos que esté escrito algo parecido:

0 * * * * wget -O /dev/null http://[url_hacia_la_herramienta]/cron.php &> /dev/null
15,30,45 * * * * wget http://[url_hacia_la_herramienta]/hontza_solr/indexar 2>&1 > /dev/null
15,30,45 * * * * wget http://[url_hacia_la_herramienta]/cron_google_sheet.php 2>&1 > /dev/null
#5,35,50 * * * * lynx -dump http://[url_hacia_la_herramienta]/red/solr/apachesolr_index_batch_index_remaining 2>&1 > /dev/null

Si la URL que dirige a la herramienta está mal configurada o directamente vacia, habra que ponerla bien. 

6) El usuario y contraseña para entrar como administrador a la herramienta hontza:

Username: admin
password: hontza

7) entrar en la url [url_hacia_la_herramienta]/hontza/admin/settings/file-system y asegurar que el file system path esta definido. Si no lo estuviera, poner alguna carpeta de confianza (también se recomienda poner el sites/default/files ).







 




