<?php declare(strict_types=1);

class App
{
    public function exec(): void
    {
        try{
            $this->main();
        }catch(Exception $e){
            echo 'エラー：'.$e->getMessage()."\n";
            exit();
        }
    }

    protected function main(): void
    {
    }
}