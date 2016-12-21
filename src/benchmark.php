<?php

  class Benchmark{
    private $outer;
    private $inner;
    private $function;
    private $duration;

    private function getResults(){
      asort($this->duration);

      foreach($this->duration as $name => $value){
        if($value != 0){
          $results[] = [
            'name'  => $name,
            'time'  => number_format($value, 10),
            'atime' => number_format($value / ($this->inner * $this->outer), 10),
            'speed' => number_format(abs((100 * $value / max($this->duration)) - 100), 2) . '%'
          ];
        }
      }

      $stats = [
        'itterations' => $this->inner * $this->outer * count($results),
        'duration'    => array_sum(array_column($results, 'time')),
        'fastest'     => $results[array_search(min(array_column($results, 'time')), array_column($results, 'time'))]['name'],
        'slowest'     => $results[array_search(max(array_column($results, 'time')), array_column($results, 'time'))]['name'],
      ];

      return ['statistics' => $stats, 'results' => $results];
    }

    public function register(string $name, \closure $callback, $bind = null){
      if(!isset($this->function[$name])){
        $this->function[$name] = is_object($bind) ? \Closure::bind($callback, $bind) : $callback;
        $this->duration[$name] = 0;
        $this->bind[$name] = $bind;
      } else {
        trigger_error("Function '$name' is already added");
      }
    }

    public function start(array $vs = []){
      if(empty($vs)){
        $functions = $this->function;
      } else {
        foreach($vs as $key){
          if(isset($this->function[$key])){
            $functions[$key] = $this->function[$key];
          } else {
            trigger_error("Function '$key' does not exist");
          }
        }
      }

      for($a = 1; $a <= $this->outer; $a++){
        foreach($functions as $name => $cb){
          $s = microtime(true);

          for($i = 1; $i <= $this->inner; $i++ ) {
            $cb();
          }

          $this->duration[$name] += microtime(true) - $s;
        }
      }

      return $this->getResults();
    }

    public function __construct(int $outer = 1, int $inner = 1){
      $this->inner = $inner;
      $this->outer = $outer;
      set_time_limit( 0 );
    }
  }
  
?>
