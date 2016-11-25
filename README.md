# PHP closure benchmarking
This is yet another benchmarking script created to compare against multiple closures in PHP.
Sometimes when dealing with big data you need to optimize small methods or functions to increase performance, with this you can compare the different methods against each other with acurate results.

## What makes it different?
This script tests each registered closure before going on to the next iteration meaning it gives each closure the same result while the CPU is waking up or doing something else to give less "jumping" results.
## Using the code
### 1. Initilize the object
If no parameters are submitted, both default to `1`.
```
$outer = 1000000; // Repeat # times
$inner = 2        // Use the same closure # in a row.

$bench = new Benchmark($outer, $inner);
```
### 2. Registering closures
```
$haystack = 'The quick brown fox jumps over the lazy dog';
$needle   = 'quick';

$bench->register('strstr', function() use ($haystack, $needle){
  return strstr($haystack, $needle);
});

$bench->register('stristr', function() use ($haystack, $needle){
  return stristr($haystack, $needle);
});

$bench->register('mystripos', function() use ($haystack, $needle){
  return stripos($haystack, $needle) !== false;
});

$bench->register('mystrpos', function() use ($haystack, $needle){
  return strpos($haystack, $needle) !== false;
});
```
### 3. Output
You can print selectively or just print it all:
```
print_r($bench->start(['strstr', 'mystrpos']));
print_r($bench->start());
```
Example Output:
````
Array(
  [statistics] => Array(
    [itterations] => 8000000
    [duration] => 3.0448346137
    [fastest] => mystrpos
    [slowest] => stristr
  )
  [results] => Array(
    [0] => Array(
      [name] => mystrpos
      [time] => 0.5495378971
      [atime] => 0.0000002748
      [speed] => 40.46 %
    ) 
    [1] => Array(
      [name] => strstr
      [time] => 0.7357819080
      [atime] => 0.0000003679
      [speed] => 20.28 %
    ) 
    [2] => Array(
      [name] => mystripos
      [time] => 0.8365163803
      [atime] => 0.0000004183
      [speed] => 9.37 %
    ) 
    [3] => Array(
      [name] => stristr
      [time] => 0.9229984283
      [atime] => 0.0000004615
      [speed] => 0.00 %
    )
  )
)
```
