class Main{

	static void test(int[] array, int length){
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

	public static void main(String[] args){
			long start, end;
			int size = Integer.parseInt(args[0]);
			int[] array = new int[size];
			for(int i = 0; i<size; i++){
					array[i]=size-i;
			}

			start = System.nanoTime();
			test(array, size);
			end = System.nanoTime();

			System.out.println( ((end-start)/1000)+"[micros]");
	}
}