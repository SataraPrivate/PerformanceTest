# PerformanceTest
Testet die Performance von ein paar Programmiersprachen
Getestet wird die Performance der Sprachen mittels Bubblesort, dabei führt der Controller(controller.php) die einzelnen Programme aus.

# TestAlgorithmus als Pseudo
```
bubbleSort(Array A)
  for (n=A.size; n>1; --n){
    for (i=0; i<n-1; ++i){
      if (A[i] > A[i+1]){
        A.swap(i, i+1)
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
$ php controller.php [repeats] [output.csv]
```
