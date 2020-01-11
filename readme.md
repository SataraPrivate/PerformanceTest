# PerformanceTests
Der Controller testet die Performance verschiender Programmiersprachen, mittels der hinterlegten Algorithmen. Als Beispiel ist der BubbleSort hinterlegt. Die Performancezeiten werden als csv abgespeichert.

# BubbleSort als Pseudo
```
bubbleSort(Array A,int length)
  for (n=length; n>1; --n){
    for (i=0; i<n-1; ++i){
      if (A[i] > A[i+1]){
        tmp = A[i];
        A[i] = A[i+1];
        A[i+1] = tmp;
      } // Ende if
    } // Ende innere for-Schleife
  } // Ende äußere for-Schleife
```
https://de.wikipedia.org/wiki/Bubblesort#Algorithmus


# Darstellung
Auf der Seite kann man das sich generierte csv Graphisch Darstellen lassen
https://www.diagrammerstellen.de/graph?selected_graph=line

... Hab kein Bock mehr auf Doku. Es ist eigentlich selbsterklärend, wenn man sich den Code anschaut.

test starten mit:
```
$ php controller.php [testfunction] [repeats] [output.csv]
```
