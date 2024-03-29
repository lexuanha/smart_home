import mysql.connector
import json
from datetime import datetime

def Sensor(jsondata):
	data = json.loads(jsondata)
	mac_t = (data['mac'])
	code_str = (data['code'])
	value_str = (data['value'])
	# time_t = datetime.now()

	code_t = code_str.split(",")
	value_t = value_str.split(",")
	for i in range(0,len(code_t)):
		# print(code_t[i])
		# print(value_t[i])
		code_i = code_t[i]
		value_i = value_t[i]
		conn = mysql.connector.connect(host = "localhost", user = "root",
	        passwd = "110297@", database = "iot",auth_plugin='mysql_native_password')
		cur = conn.cursor()

		sql_mac = """SELECT mac FROM location WHERE mac = \"%s\"""" %mac_t
		cur.execute(sql_mac)
		results_mac = cur.fetchall()
		if not results_mac:
			sql_location = """INSERT INTO location(mac,area) VALUES(\"%s\",\"%s\")""" %(mac_t,mac_t)
			cur.execute(sql_location)
			conn.commit()

		sql_sensor = """SELECT code FROM sensor WHERE code = \"%s\"""" %code_i
		cur.execute(sql_sensor)
		results_sensor = cur.fetchall()
		if not results_sensor:
			sql_location = """INSERT INTO sensor (code) VALUES(\"%s\")""" %code_i
			cur.execute(sql_location)
			conn.commit()

		sql1 = """SELECT id FROM sensor WHERE code = \"%s\"""" %code_i
		cur.execute(sql1)
		code_id = cur.fetchall()

		sql2 = """SELECT id FROM location WHERE mac = \"%s\"""" %mac_t
		cur.execute(sql2)
		mac_id = cur.fetchall()

		sql_sensor_id = """SELECT id FROM router WHERE sensorid = \"%s\" AND areaid = \"%s\"""" %(code_id[0][0], mac_id[0][0])
		cur.execute(sql_sensor_id)
		results_sensor_id = cur.fetchall()
		if not results_sensor_id:
			sql_location = """INSERT INTO router (areaid, sensorid, active) VALUES(\"%s\",\"%s\", 1)""" %(mac_id[0][0], code_id[0][0])
			cur.execute(sql_location)
			conn.commit()

		sensor_id = """SELECT id FROM router WHERE sensorid = \"%s\" AND areaid = \"%s\"""" %(code_id[0][0], mac_id[0][0])
		cur.execute(sensor_id)
		res_sensor_id = cur.fetchall()

		sql_data = """insert  INTO sensordata (sensor, value,time) VALUES(\"%s\", \"%s\",now())""" %(res_sensor_id[0][0],value_i)
		cur.execute(sql_data)
		conn.commit()

	print('Data saved.')
	conn.close
