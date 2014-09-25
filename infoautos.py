# -*- coding: utf-8 -*-
import MySQLdb
import requests
import logging

logging.basicConfig(format='%(asctime)s %(levelname)s: %(message)s', datefmt='%m/%d/%Y %I:%M:%S %p', level=logging.INFO)

socket = '/Applications/MAMP/tmp/mysql/mysql.sock'
db = MySQLdb.connect(unix_socket=socket, host='localhost', db='test', user='root', passwd='root')
db.autocommit(True)

c = db.cursor()
c.execute('SET NAMES utf8;')
c.execute('SET CHARACTER SET utf8;')
c.execute('SET character_set_connection=utf8;')

s = requests.Session()

logging.info('Bajando marcas...')
r = s.get('http://www.123seguro.com.ar/front.php/auto/marcas')
errors = 0
if r.status_code==200:
    marcas = r.json()
    for marca in marcas:
        # if not marca['nombre']=='RENAULT': continue
        if marca['nombre']=='-----------------': continue
        sql = 'INSERT INTO marca (nombre) VALUES ("%s")' % MySQLdb.escape_string((marca['nombre'].encode('utf-8')))
        c.execute(sql)
        marca_id = db.insert_id()
        logging.info('Bajando versiones de %s' % marca['nombre'])
        r = s.get('http://www.123seguro.com.ar/front.php/auto/versiones?data%%5Bmarca_id%%5D=%s' % marca['id'])
        if r.status_code==200:
            versiones = r.json()
            for version in versiones:
                # if not version['nombre']=='CLIO 2 F2': continue
                if version['id'] < 1: continue
                sql = 'INSERT INTO version (marca_id, nombre) VALUES(%s, "%s")' % (marca_id, MySQLdb.escape_string(version['nombre'].encode('utf-8')))
                c.execute(sql)
                version_id = db.insert_id()
                logging.info(u'Bajando modelos de %s - %s' % (marca['nombre'], version['nombre']))
                r = s.get('http://www.123seguro.com.ar/front.php/auto/modelos?data%%5Bversion_id%%5D=%s&data%%5Bmarca_id%%5D=%s' % (version['id'], marca['id']))
                if r.status_code==200:
                    modelos = r.json()
                    for modelo in modelos:
                        if modelo['id'] < 1: continue
                        sql = 'INSERT INTO modelo (version_id, nombre) VALUES (%s, "%s")' % (version_id, MySQLdb.escape_string(modelo['nombre'].encode('utf-8')))
                        c.execute(sql)
                        modelo_id = db.insert_id()
                        logging.info(u'Bajando años de %s - %s - %s' % (marca['nombre'], version['nombre'], modelo['nombre']))
                        r = s.get('http://www.123seguro.com.ar/front.php/auto/anios?data%%5Bmodelo_id%%5D=%s' % modelo['id'])
                        if r.status_code==200:
                            anios = r.json()
                            values = []
                            for anio in anios:
                                values.append('(%s, "%s")' % (modelo_id, MySQLdb.escape_string((str(anio['anio']).encode('utf-8')[0:4]))))
                            if len(values):
                                values = ','.join(values)
                                sql = 'INSERT INTO modelo_anio (modelo_id, anio) VALUES %s' % values
                                c.execute(sql)
                        else:
                            logging.error('Error')
                            errors += 1
                else:
                    logging.error('Error')
                    errors += 1
        else:
            logging.error('Error')
            errors += 1
else:
    logging.error('Error')
    errors += 1

print 'Errores: %s' % errors