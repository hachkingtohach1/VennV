<?php

namespace hachkingtohach1\vennv\storage\brain;

use hachkingtohach1\vennv\VennVPlugin;

final class BrainManager{

    public function save(string $text) : void{
        $data = explode("<space>", $this->get());
        foreach($data as $key => $value){
            if($value != $text){
                $file = fopen(VennVPlugin::getPlugin()->getDataFolder(). "brain/" . "Brain.txt", "a+") or die("Unable to open file!");
                fwrite($file, "{$text}<space>");
                fclose($file);
            }
        }
    }

    public function get() : string{
        $file = fopen(VennVPlugin::getPlugin()->getDataFolder(). "brain/" . "Brain.txt", "r") or die("Unable to open file!");
        $text = fread($file, filesize(VennVPlugin::getPlugin()->getDataFolder(). "brain/" . "Brain.txt"));
        fclose($file);
        return $text;
    }
}