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

y_train = temperature[1:(len(temperature)-(num_periods*730))+f_horizon]
y_batches = y_train.reshape(-1, num_periods, 1)

def test_data(series, forecast, num):
    testX = temperature[-(num + forecast):-(num + forecast)+24][:num].reshape(-1, num_periods, 1)
    testY = temperature[-(num):-(num)+24].reshape(-1, num_periods, 1)
    return testX, testY
X_test, Y_test = test_data(temperature, f_horizon, 24*7)
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
#print(logit)

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

ss = abs(Y_test-y_pred)/Y_test
ss_tb_day = sum(ss)/len(ss)
ss_tb = sum(ss_tb_day)/len(ss_tb_day)

print "Sai so: ",ss_tb*100,"%"

def create_time_steps(length):
  time_steps = []
  for i in range(-length, 0, 1):
    time_steps.append(i)
  return time_steps
def show_plot(plot_data, delta, title):
  labels = ['History', 'Actual', 'Forecast']
  marker = ['.-', 'rx', 'gx']
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
show_plot([X_test[0], Y_test[0][23], y_pred[0][23]], 0,'Du bao 1 gio toi')
plt.show()
