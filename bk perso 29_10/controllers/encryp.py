
from __future__ import unicode_literals, print_function

#librerias 
__docformat__ = 'restructuredtext en'

import codecs
import os
import sys
import unittest
import hashlib
import time
import pymysql
import pymysql.cursors
#import mysql.connector as mariadb
from test import barras
from PIL import Image, ImageDraw, ImageFont


from barcode import get_barcode, get_barcode_class, version
try:
    from barcode.writer import ImageWriter
except ImportError:
    ImageWriter = None  # lint:ok


PATH = os.path.dirname(os.path.abspath(__file__))
TESTPATH = os.path.join(PATH, 'codigo')

IMAGES = ('<h3>As PNG-Image</h3><br>\n'
          '<img src="{filename}" alt="PNG {name}"></p>\n')

NO_PIL = '<h3>Pillow was not found. No PNG-Image created.</h3></p>\n'



#variables globales

cadena= ""
T_radicado=""
T_fecha=""
T_estado =""
T_idtramite=""
T_hora=""
T_hash=""
T_codigo=""
x=0



#radi= input("Ingrese numero de radicado: ")
radi=sys.argv[1]
radi2=int(radi)-1



##########################################
########### BASE DE DATOS ################
##########################################

#CONEXION
db = pymysql.connect(host="127.0.0.1",user="personerias",password="personerias2019",database="personerias")
#db= mariadb.connect(host='localhost',port='3307',user='root',password="",db='personerias')
##Parametros a consultar

Cons_a= "SELECT id_radicado FROM radicados where  id_radicado = \'%s\'" %radi
Cons_b= "SELECT estado FROM radicados where  id_radicado = \'%s\'" %radi
Cons_c= "SELECT fecha_creacion FROM radicados where  id_radicado = \'%s\'" %radi
Cons_d= "SELECT id_tipo_tramite FROM radicados where  id_radicado = \'%s\'" %radi
Cons_e= "SELECT fecha_creacion FROM radicados where  id_radicado = \'%s\'" %radi2


cursor = db.cursor()
cursor.execute(Cons_e)
temporal=cursor.fetchone()
temporal=str(temporal)


## bucla de consulta ##

for x in range(5):
    if x==1:    
        cursor.execute(Cons_a)
        T_radicado=cursor.fetchone()
    if x==2:
        cursor.execute(Cons_b)
        T_estado=cursor.fetchone()
    if x==3:
        cursor.execute(Cons_c)
        T_fecha=cursor.fetchone()
    if x==4:
        cursor.execute(Cons_d)
        T_idtramite=cursor.fetchone()
                    
#######################


#definicion de bloque  para creacion de HASH#

class Bloque:
    def __init__(self):
        self.estado=""
        self.fecha_radicado=""
        self.id=""
        self.id_tramite=""
        self.hash_semilla=""
        self.hash = ""
        self.hora = time.strftime("%Y-%m-%d %H:%M:%S")
    def hash_bloque(self):
        cadena=(str(self.fecha_radicado)+" "+" Sistema de seguridad "+str(self.id)+" Gobernacion del Valle del Cauca "+str(self.estado) + " Personeria juridica "+ str(self.id_tramite)+str(self.hash_semilla)+str(self.hora))
        encryp=hashlib.sha256(cadena.encode()).hexdigest()
        #print(encryp)
        #print(cadena)
        return encryp    

# integracion de hash y obtencion de sello#
class blockchaing:
    bloque=Bloque()
    hora = time.strftime("%Y-%m-%d %H:%M:%S")
    bloque.hora=hora # incrustamos la hora del sistema
    bloque.id=T_radicado
    bloque.id_tramite=T_idtramite
    bloque.estado=T_estado
    bloque.fecha_radicado=T_fecha
    bloque.hash_semilla=hashlib.sha256(temporal.encode()).hexdigest()
    bloque.hash=bloque.hash_bloque()
    T_hash=bloque.hash
    T_hora=hora
    #T_codigo=( str(T_fecha)+"-"+str(T_hora)+"-"+str(T_idtramite)+"-"+str(T_radicado))
    T_codigo=( str(T_fecha)+"-"+str(T_hora)+"-"+str(T_idtramite)+"-")
    T_codigo=T_codigo.replace("(","")
    T_codigo=T_codigo.replace(",","")
    T_codigo=T_codigo.replace(")","")
    T_codigo=T_codigo.replace("datetime.datetime","")
    T_codigo=T_codigo.replace("-","")
    T_codigo=T_codigo.replace(":","")
    T_codigo=T_codigo.replace(" ","")
    

    ########### adicion de informacion a base  de datos ##########
    #query= "UPDATE resgistro_validacion SET (fecha_sistem,codigo_h,codigo_cons ) VALUES(%s,%s,%s) WHERE id_radicado = \'%s\'" %radi
    query= "UPDATE registro_validacion SET `fecha_sistem` = %s, `codigo_h` = %s, `codigo_cons` = %s WHERE id_radicado = %s"
   # query= "INSERT INTO  pre_validacion_py (id_radicado,estado,IDT_tramite,fecha_creacion,fecha_sistem,codigo_h,codigo_cons )" \
   # "VALUES(%s,%s,%s,%s,%s,%s,%s)"
    #args=(T_hora,radi)
    args=(T_hora,T_hash,T_codigo,radi)
    #args=(T_radicado,T_estado,T_idtramite,T_fecha,T_hora,T_hash,T_codigo)
    cursor.execute(query,args)
    db.commit()
######################
    print(bloque.hash)
    #print(bloque.hash_semilla)
    print(T_codigo)
################################
    img = Image.new('RGB', (400, 400), color = (255, 255, 255))
    d = ImageDraw.Draw(img,)
    d.text((10,10), str(bloque.hash) , fill=(113,113,113))
    img.save('pil_text.jpg')
    im = Image.open('pil_text.jpg')
    img_hash=im.rotate(90)
    img_hash=img_hash.crop((0,0,30,400))
    img_hash.save('/var/www/html/personerias/web/img/hash.jpg','JPEG')
  
   
################################
#### ##Generacion de sello## ###
################################    
    barras(T_codigo)
  







if __name__ == '__main__':
    Blockchaing = blockchaing()
   
 ## Resgistro en base de datos ##




    

