import paho.mqtt.client as mqtt
from savedata import *
import mysql.connector
import random
import json
from datetime import datetime
from time import sleep

Broker = "localhost"
Port = 1883
Wait = 400
Topic = "data/in"

def on_connect(client, userdata, flags, rc):
	if rc!=0:
		pass
		print('Unable connect to Broker...')
	else:
		print('Connected with Broker' + str(Broker))
	client.subscribe(Topic,0)

def on_message(client, userdata, msg):
	#print('Receiving data...')
	#print('Topic: ' + msg.topic)
	print(msg.payload)
	Sensor(msg.payload)

client = mqtt.Client()
client.username_pw_set(username = "qbhbylnr", password = "PTRgduKLOhGN")
client.on_connect = on_connect
client.on_message = on_message
client.connect(Broker, Port, Wait)

client.loop_forever()
