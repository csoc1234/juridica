
#libraries 
import pymysql
import pymysql.cursors
import sys
import os 
##########################

#CONEXION
db = pymysql.connect(host="127.0.0.1",user="personerias",password="personerias2019",database="personerias")
#db= pymysql.connect(host='localhost',user='root',password="1234",db='personerias')

# fecha de resolucion
dia = sys.argv[1]
mes = sys.argv[2]
ano = sys.argv[3]
# numero de resolucion
num_resolucion=sys.argv[4]
radi=sys.argv[5]
radi=str(radi)
####################################################################################################
#concateno
cadena = str(ano)+"-"+str(mes)+"-"+str(dia)
ano = str(ano)
#actualizacion de datos 
cursor = db.cursor()
query="UPDATE resoluciones SET `numero_resolucion` = %s, `fecha_creacion` = %s,`ano_resolucion`=%s WHERE id_radicado = %s"
args=(num_resolucion,cadena,ano,radi)
cursor.execute(query,args)
db.commit()
###################################################################################################