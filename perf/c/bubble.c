#include <sys/time.h>
#include <stdio.h>
#include <stdlib.h>

void test(int* array, int length){
	int tmp;
	for (int n= length; n>1; --n){
		for (int i=0; i<n-1; ++i){
			if (array[i] > array[i+1]){
				tmp = array[i];
				array[i] = array[i+1];
				array[i+1]=tmp;
			} 
		} 
	} 
}

long getMicrotime(){
	struct timeval currentTime;
	gettimeofday(&currentTime, 0);
	return currentTime.tv_sec * (int)1e6 + currentTime.tv_usec;
}

void main(int argc, char **argv){
		long start, end;
		int size = atoi(argv[1]);
		int array[size];
		for(int i = 0; i<size; i++){
				array[i]=size-i;
		}

		start = getMicrotime();
		test(array, size);
		end = getMicrotime();

		printf( "%lu[micros]\n", (end-start));
}