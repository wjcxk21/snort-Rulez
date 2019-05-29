#!/bin/bash

# Este script instala y configura los componentes necesarios para poder usar
# la aplicación "SnortRulez"

#########################################
# Got r00t o are u Amy Acker?
#########################################
if [ "$EUID" -ne 0 ]
#    then echo "rule-update needs root privileges or be Amy Acker.  Please try again using sudo."
    then echo "rule-update 需要root权限，请使用sudo再试一遍。"
    exit 1
fi

#########################################
# Instalar dependencias
#########################################
sudo apt-get install incron
#echo "No olvides añadir el daemon incrond al arranque del sistema. https://github.com/andoniaf/snort-Rulez#como-a%C3%B1ado-el-daemon-incrond " >> LEEME.txt
echo "别忘了将incrond守护程序加入系统引导。">> LEEME.txt

#########################################
# Copiar archivos de la aplicación a la ruta correspondiente
#########################################
clear
#echo "Escriba la ruta donde quiere desplegar la aplicación."
echo "输入要部署该程序的路径。"
#echo "Esta ruta es la que se usará para acceder a la aplicación desde el navegador."
echo "此路径用于从浏览器访问该应用。"
#echo "Se creara una carpeta con el nombre de SnortRulez."
echo "将在其中创建名为SnortRulez的子目录。"
#read -p "Ruta : " PATH_PHP
read -p "目录：" PATH_PHP
#Creamos la carpeta y copiamos en ella todos los archivos
if ! [ -d $PATH_PHP ]; then
        while true; do
                #echo "La ruta no existe. Introduce una ruta válida."
                #read -p "Ruta : " PATH_PHP
                echo "此路径不存在，请重新输入。"
                read -p "目录：" PATH_PHP
                if [ -d $PATH_PHP ]; then
                break
                fi
        done
fi
mkdir -p $PATH_PHP/SnortRulez
cp -r ./* $PATH_PHP/SnortRulez
chown www-data:www-data $PATH_PHP/SnortRulez/test.rules
chown www-data:www-data $PATH_PHP/SnortRulez/easy.rules
chown www-data:www-data $PATH_PHP/SnortRulez/custom.rules
chmod -R a+r $PATH_PHP/SnortRulez
mv $PATH_PHP/SnortRulez/config_def.php $PATH_PHP/SnortRulez/config.php

cp restart-IDS /bin
#Crear los enlaces de los archivos rules a su ruta correspondiente
#echo "Escriba la ruta donde estan guardadas las reglas."
echo "输入规格文件路径。"
#echo "Si estas usando SecurityOnion la ruta probablemente sera: /etc/nsm/rules"
echo "如果你使用的是SecurityOnion，则路径可能是：/etc/nsm/rules"
read -p "目录：" PATH_RULES
if ! [ -d $PATH_RULES ]; then
        while true; do
               # echo "La ruta no existe. Introduce una ruta válida."
               # read -p "Ruta : " PATH_RULES
                echo "此路径不存在，请重新输入。"
                read -p "目录：" PATH_RULES
                if [ -d $PATH_RULES ]; then
                break
                fi
        done
fi

ln $PATH_PHP/SnortRulez/test.rules $PATH_RULES/test.rules
ln $PATH_PHP/SnortRulez/easy.rules $PATH_RULES/easy.rules
ln $PATH_PHP/SnortRulez/custom.rules $PATH_RULES/custom.rules

#########################################
# Crear e importar la BD
#########################################
BDName="SnortRulez"
echo "----------------------------------------------------------------------"
#read -p "Escriba el nombre de usuario de la BBDD [p.e: root]: " BDUser
read -p "输入数据库的用户名 [如：root]：" BDUser
STTY_SAVE=$(stty -g)
stty -echo
#echo -n "Introduce la contraseña del usuario de la BBDD: "
echo -n "输入相应的口令："
read DBADMIN_SECRET_PASSWD
stty $STTY_SAVE
echo
mysql -v -u $BDUser -p$DBADMIN_SECRET_PASSWD -e "CREATE SCHEMA $BDName;"
mysql -v -u $BDUser -p$DBADMIN_SECRET_PASSWD $BDName < SnortRulez_importMe.sql

echo "El usuario y la contraseña por defecto para la aplicación son: ntesla jdtedison" >> LEEME.txt
echo "访问该程序的默认用户名和口令是：ntesla jdtedison" >> LEEME.txt
echo "----------------------------------------------------------------------"
#echo "Finalizada importacion de la BD"
echo "导入数据库完成！"
echo "----------------------------------------------------------------------"

#########################################
# Añadir tareas al incrontab
#########################################
#Permitir a root modificar las reglas
echo "root" >> /etc/incron.allow
# Añadir las reglas
#echo "/var/www/html/php/SnortRulez/test.rules IN_MODIFY restart-IDS" >> /var/spool/incron/root
#echo "/var/www/html/php/SnortRulez/custom.rules IN_MODIFY restart-IDS" >> /var/spool/incron/root
#echo "/var/www/html/php/SnortRulez/easy.rules IN_MODIFY restart-IDS" >> /var/spool/incron/root

#########################################
# Recordar leer el LEEME.txt
#########################################
#echo "Configura el archivo config.php con tu configuración" >> LEEME.txt
#echo "Instalación finalizada. Fecha y hora: `date` " >> LEEME.txt
#echo "No olvides leer el archivo LEEME.txt para terminar con la instalación correctamente."
#echo
#echo "Que la fuerza te acompañe!"
echo "可以使用配置文件config.php完成配置" >> LEEME.txt
echo "安装完毕。时间日期：`date`" >> LEEME.txt
echo "不要忘记阅读LEEME.txt文件以完成正确的安装配置。"
echo
echo "May the force be with you!!"
