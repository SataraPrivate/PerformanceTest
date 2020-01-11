function test(array, length) {
    let tmp;
    for (let n = length; n > 1; --n) {
        for (let i = 0; i < n - 1; ++i) {
            if (array[i] > array[i + 1]) {
                tmp = array[i];
                array[i] = array[i + 1];
                array[i + 1] = tmp;
            }
        }
    }
}

let size = parseInt(process.argv[2]);
let array = [];
for (let i = 0; i < size; i++) {
    array[i] = size - i;
}
let start = process.hrtime();
test(array, size);
let end = process.hrtime();

let diff = [end[0]-start[0], end[1]-start[1]];
console.log(((diff[0]*1000000000+diff[1])/1000) +"[micros]");