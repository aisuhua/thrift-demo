import sys, glob
sys.path.append('gen-py')
sys.path.insert(0, glob.glob('./../lib/py/build/lib.*')[0])

from adder import AddService

from thrift import Thrift
from thrift.transport import THttpClient
from thrift.transport import TTransport
from thrift.protocol import TBinaryProtocol

from random import randint

socket = THttpClient.THttpClient('localhost', 8080, '/MyAddServer.php')
transport = TTransport.TBufferedTransport(socket)
protocol = TBinaryProtocol.TBinaryProtocol(transport)
client = MyFirstService.Client(protocol)
transport.open()


# calling 'add' method with random parameters
number1 = randint(1,100)
number2 = randint(1,100)
my_sum = client.add(number1,number2)
print '%d+%d=%d' % (number1, number2, my_sum)

transport.close()