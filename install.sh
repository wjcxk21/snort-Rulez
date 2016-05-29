#!/bin/bash

#########################################
# Got r00t o are u Amy Acker?
#########################################
if [[ $(/usr/bin/id -u) -ne 0 ]]; then
    echo "rule-update needs root privileges or be Amy Acker.  Please try again using sudo."
    exit
fi

#########################################
# Instalar dependencias
#########################################
sudo apt-get install incron
echo "No olvides añadir el daemon incrond al arranque del sistema. https://github.com/andoniaf/snort-Rulez#como-añado-el-daemon-incrond " >> LEEME.txt

#########################################
# Copiar archivos de la aplicación a la ruta correspondiente
#########################################
clear
echo "Escriba la ruta donde quiere desplegar la aplicación."
echo "Esta ruta es la que se usará para acceder a la aplicación desde el navegador."
echo "Se creara una carpeta con el nombre de SnortRulez."
read -p "Ruta : " PATH_PHP
#Creamos la carpeta y copiamos en ella todos los archivos
mkdir $PATH_PHP/SnortRulez
cp ./* $PATH_PHP/SnortRulez

#Crear los enlaces de los archivos rules a su ruta correspondiente
echo "Escriba la ruta donde estan guardadas las reglas."
echo "Si estas usando SecurityOnion la ruta probablemente sera: /etc/nsm/rules"
read -p "Ruta : " PATH_RULES
ln $PATH_PHP/SnortRulez/test.rules $PATH_RULES/test.rules
ln $PATH_PHP/SnortRulez/easy.rules $PATH_RULES/easy.rules
ln $PATH_PHP/SnortRulez/custom.rules $PATH_RULES/custom.rules

#########################################
# Crear e importar la BD                ¡¡¡¡¡¡¡¡¡Una vez verificado que funciona cambiar el nombre de la BD
#########################################
BDName="SnortPrueba"
STTY_SAVE=$(stty -g)
stty -echo
echo "----------------------------------------------------------------------"
read -p "Escriba el nombre de usuario de la BBDD [p.e: root]: " BDUser
echo -n "Introduce la contraseña del usuario de la BBDD: "
read DBADMIN_SECRET_PASSWD
stty $STTY_SAVE
echo
mysql -v -u $BDUser -p$DBADMIN_SECRET_PASSWD -e "CREATE SCHEMA $BDName;"
mysql -v -u $BDUser -p$DBADMIN_SECRET_PASSWD $BDName < SnortRulez_importMe.sql

echo "El usuario y la contraseña por defecto para la aplicación son: ntesla jdtedison" >> LEEME.txt
echo "----------------------------------------------------------------------"
echo "Finalizada importacion de la BD"
echo "----------------------------------------------------------------------"

#########################################
# Añadir tareas al incrontab
#########################################
#Permitir a root modificar las reglas
echo "root" >> /etc/incron.allow
# Añadir las reglas
echo "/var/www/html/php/SnortRulez/test.rules IN_MODIFY restart-IDS" >> /var/spool/incron/root
echo "/var/www/html/php/SnortRulez/custom.rules IN_MODIFY restart-IDS" >> /var/spool/incron/root
echo "/var/www/html/php/SnortRulez/easy.rules IN_MODIFY restart-IDS" >> /var/spool/incron/root

#########################################
# Recordar leer el LEEME.txt
#########################################
echo "Instalación finalizada. Fecha y hora: `date` " >> LEEME.txt
echo "No olvides leer el archivo LEEME.txt para terminar con la instalación correctamente."
echo
echo "Que la fuerza te acompañe!"
