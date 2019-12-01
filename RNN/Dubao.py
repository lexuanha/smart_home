import os
os.environ['TF_CPP_MIN_LOG_LEVEL']='2'
import tensorflow as tf
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

file_path = '10_year_basel_switzerland.csv'
data=pd.read_csv(file_path, delimiter=',',header=11,skipinitialspace=True)
data.head(24)

temperature = np.array(data['Temperature'])
#print temperature

num_periods = 24
f_horizon = 1
x_train = temperature[:(len(temperature)-(num_periods*730))]
x_batches = x_train.reshape(-1, num_periods, 1)

y_train = temperature[f_horizon:(len(temperature)-(num_periods*730))+f_horizon]
y_batches = y_train.reshape(-1, num_periods, 1)

def test_data(series, forecast, num):
    testX = temperature[-(num + forecast):][:num].reshape(-1, num_periods, 1)
    testY = temperature[-(num):].reshape(-1, num_periods, 1)
    return testX, testY
X_test, Y_test = test_data(temperature, f_horizon, 24*300)
#print(X_test.shape)

tf.reset_default_graph()
rnn_size = 100
learning_rate=0.001

X = tf.placeholder(tf.float32, [None, num_periods, 1])
Y = tf.placeholder(tf.float32, [None, num_periods, 1])
rnn_cells=tf.contrib.rnn.BasicRNNCell(num_units=rnn_size, activation=tf.nn.relu)
rnn_output, states = tf.nn.dynamic_rnn(rnn_cells, X, dtype=tf.float32)
output=tf.reshape(rnn_output, [-1, rnn_size])
logit=tf.layers.dense(output, 1, name="softmax")
outputs=tf.reshape(logit, [-1, num_periods, 1])

loss = tf.reduce_sum(tf.square(outputs - Y))
accuracy = tf.reduce_mean(tf.cast(tf.equal(tf.argmax(logit, 1), tf.cast(Y, tf.int64)), tf.float32))
optimizer = tf.train.AdamOptimizer(learning_rate=learning_rate)
train_step=optimizer.minimize(loss)

epochs = 1000
sess = tf.Session()
init = tf.global_variables_initializer()
sess.run(init)

with tf.Session() as sess:
    saver = tf.train.Saver()
    saver.restore(sess, "models/model_temp.ckpt")
    y_pred=sess.run(outputs, feed_dict={X: X_test})
ss=0
for i in range(0,len(Y_test)):
	ss = ss + abs(Y_test[i]-y_pred[i])/Y_test[i]
	print i
ss_tb_day = ss/len(Y_test)
ss_tb = sum(ss_tb_day)/len(ss_tb_day)

print "Sai so trung binh: ",ss_tb*100,"%"
 
