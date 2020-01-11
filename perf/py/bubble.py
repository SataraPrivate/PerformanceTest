import datetime;
import sys;

def test(array, length):
    for n in range(length,1, -1):
        for i in range (0,n-1):
            if array[i] > array[i+1]:
                tmp = array[i]
                array[i] = array[i+1]
                array[i+1]=tmp

size = int(sys.argv[1])
array = range(size, 0, -1)

start = datetime.datetime.now()
test(array, size)
end = datetime.datetime.now()
diff = end-start
print( str(diff.seconds) + str(diff.microseconds).rjust(6,"0") + "[micros]")

