import os
os.environ['TF_CPP_MIN_LOG_LEVEL']='2'
import tensorflow as tf
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

file_path = 'weather.csv'
data=pd.read_csv(file_path, delimiter=',',header=11,skipinitialspace=True)
data.head(24)

temperature = np.array(data['Temperature'])
#print temperature

num_periods = 24
f_horizon = 1

#
x_train=[]
for x in range (0,(len(temperature)-(num_periods*3)),24):
	x_train.extend(temperature[x:x+48])
	
x_train=np.array(x_train)
x_batches = x_train.reshape(-1, 2*num_periods, 1)

y_train = temperature[48:(len(temperature)-(num_periods*1))]
y_batches = y_train.reshape(-1, num_periods, 1)

#
def test_data(series, forecast, num):
    testX = temperature[-72:len(temperature)-24].reshape(-1, 2*num_periods, 1)
    testY = temperature[-24:len(temperature)].reshape(-1,num_periods, 1)
    return testX, testY
X_test, Y_test = test_data(temperature, f_horizon, 24*1)

#
tf.reset_default_graph()
rnn_size = 20
learning_rate=0.001
print (x_batches.shape)
print (y_batches.shape)
X = tf.placeholder(tf.float32, [None, 2*num_periods, 1])
Y = tf.placeholder(tf.float32, [None, num_periods, 1])
print X
print Y
rnn_cells=tf.contrib.rnn.BasicRNNCell(num_units=rnn_size, activation=tf.nn.relu)
rnn_output, states = tf.nn.dynamic_rnn(rnn_cells, X, dtype=tf.float32)

output=tf.reshape(rnn_output, [-1, rnn_size])
logit=tf.layers.dense(output, 1, name="softmax")

outputs=tf.reshape(logit, [-1, 2*num_periods, 1])
print outputs
loss = tf.reduce_sum(tf.square(outputs - Y))
print loss
accuracy = tf.reduce_mean(tf.cast(tf.equal(tf.argmax(logit, 1), tf.cast(Y, tf.int64)), tf.float32))
optimizer = tf.train.AdamOptimizer(learning_rate=learning_rate)
train_step=optimizer.minimize(loss)

epochs = 1000

sess = tf.Session()
init = tf.global_variables_initializer()
sess.run(init)

for epoch in range(epochs):
    #train_dict = {X: x_batches, Y: y_batches, keep_prob:0.5}
    train_dict = {X: x_batches, Y: y_batches}
    sess.run(train_step, feed_dict=train_dict)
    
saver = tf.train.Saver()
save_path = saver.save(sess, "models/model_test.ckpt")


with tf.Session() as sess:
    #Restore variables from disk.
    #saver = tf.train.Saver()
    saver.restore(sess, "models/model_test.ckpt")
    y_pred=sess.run(outputs, feed_dict={X: X_test})

print y_pred
print (X_test)
print (Y_test)

