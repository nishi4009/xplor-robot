<?php

class Simulator
{
    protected $robot;

    public function __construct(Robot $robot)
    {
        $this->robot = $robot;
    }

    public function run($testcase)
    {
        if(trim($testcase) === 'Test1'){
            $file = "../test1.txt";
        }
        elseif(trim($testcase) === 'Test2'){
            $file = "../test2.txt";            
        }
        elseif(trim($testcase) === 'Test3'){
            $file = "../test3.txt";            
        }
        elseif(trim($testcase) === 'Test4'){
            $file = "../test4.txt";            
        }
        
        $fileHandle = fopen($file, 'r');

        while (($command = fgets($fileHandle))) {
            echo "<br>" . $command;
            $this->robot->execute($command);
        }
        echo "<br>";    

       fclose($fileHandle);
    }
}
