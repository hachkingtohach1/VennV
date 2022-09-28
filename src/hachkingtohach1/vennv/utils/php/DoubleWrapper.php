<?php

namespace hachkingtohach1\vennv\utils\php;

use hachkingtohach1\vennv\utils\TextFormat;

final class DoubleWrapper{

    private int|float $value;

    public function addAndGet(int|float $var1) : mixed{
        $this->value += $var1;
        return $this->value;
    }

    public function getPrefix() : string{
        return "VennV > DoubleWrapper:";
    }

    public function get() : mixed{
        return $this->value;
    }

    public function set(int|float $value) : void{
        $this->value = $value;
    }

    public function doubleWrapper(int|float $value) : mixed{
        $INT32_MASK = 0xffffffff;
        if((0x1deadbeef >> 32) !== 1){
            echo $this->getPrefix().TextFormat::RED.'error: only works on 64-bit systems!';
            return false;
        }
        if($value <= 0) {
            return false;
        }
        $beTest = bin2hex(pack('d', 1.0)); 
        if(strlen($beTest) != 16) {
            echo $this->getPrefix().TextFormat::RED.'error: system does not use 8 bytes for double precision!';
            return false;
        }
        if($beTest == '3ff0000000000000'){
            $isBE = true;
        }
        elseif($beTest == '000000000000f03f'){
            $isBE = false;
        }
        else{
            echo $this->getPrefix().TextFormat::RED.'error: could not determine endian mode!';
            return false;
        }
        $bin = pack('d', $value);
        $int = 0;
        for($i = 0; $i < 8; $i++){
            $int = ($int << 8) | ord($bin[$isBE ? $i : 7 - $i]);
        }
        $int--;
        if($isBE){
            $out = unpack('d', pack('N', ($int >> 32) & $INT32_MASK) . pack('N', $int & $INT32_MASK));
        }else{
            $out = unpack('d', pack('V', $int & $INT32_MASK) . pack('V', ($int >> 32) & $INT32_MASK));
        }
        return $out[1];
    }
}