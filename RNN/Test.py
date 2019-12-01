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

num_periods = 24
f_horizon = 1
#
x_train=[]
for x in range (0,(len(temperature)-(num_periods*2))):
	x_train.extend(temperature[x:x+24])
	
x_train=np.array(x_train)
x_batches = x_train.reshape(-1, num_periods, 1)

y_train = temperature[24:(len(temperature)-(num_periods*2))+24]
y_batches = y_train.reshape(-1, 1, 1)

#
def test_data(series, forecast, num):
    testX = temperature[-(num + 25):len(temperature)-25].reshape(-1, num_periods, 1)
    testY = temperature[len(temperature)-25].reshape(-1,1, 1)
    return testX, testY
X_test, Y_test = test_data(temperature, f_horizon, 24*1)

#
tf.reset_default_graph()
rnn_size = 50
learning_rate=0.001
print (x_batches.shape)
print (y_batches.shape)
X = tf.placeholder(tf.float32, [None, num_periods, 1])
Y = tf.placeholder(tf.float32, [None, 1, 1])

rnn_cells=tf.contrib.rnn.BasicRNNCell(num_units=rnn_size, activation=tf.nn.relu)
rnn_output, states = tf.nn.dynamic_rnn(rnn_cells, X, dtype=tf.float32)

output=tf.reshape(rnn_output, [-1, rnn_size])
logit=tf.layers.dense(output, 1, name="softmax")

outputs=tf.reshape(logit, [-1, num_periods, 1])

loss = tf.reduce_sum(tf.square(outputs - Y))

accuracy = tf.reduce_mean(tf.cast(tf.equal(tf.argmax(logit, 1), tf.cast(Y, tf.int64)), tf.float32))
optimizer = tf.train.AdamOptimizer(learning_rate=learning_rate)
train_step=optimizer.minimize(loss)

epochs = 500

sess = tf.Session()
init = tf.global_variables_initializer()
sess.run(init)
print (X_test)
print (Y_test)

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

y_pred=y_pred[0][23]
print y_pred
def create_time_steps(length):
  time_steps = []
  for i in range(-length, 0, 1):
    time_steps.append(i)
  return time_steps
def show_plot(plot_data, delta, title):
  labels = ['History', 'True Future', 'Model Prediction']
  marker = ['.-', 'rx', 'go']
  time_steps = create_time_steps(plot_data[0].shape[0])
  if delta:
    future = delta
  else:
    future = 0

  plt.title(title)
  for i, x in enumerate(plot_data):
    if i:
      plt.plot(future, plot_data[i], marker[i], markersize=10,
               label=labels[i])
    else:
      plt.plot(time_steps, plot_data[i].flatten(), marker[i], label=labels[i])
  plt.legend()
  plt.xlim([time_steps[0], (future+5)*2])
  plt.xlabel('Time-Step')
  return plt
show_plot([X_test[0], Y_test[0], y_pred], 0,'Baseline Prediction Example')
plt.show()


