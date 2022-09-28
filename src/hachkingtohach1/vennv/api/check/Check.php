<?php

namespace hachkingtohach1\vennv\api\check;

class Check{

    private string $type;
    private string $subType;
    private string $display;

    public function set(string $type, string $subType, string $display){
        $this->type = $type;
        $this->subType = $subType;
        $this->display = $display;
    }

    public function getType(): string{
        return $this->type;
    }

    public function getSubType(): string{
        return $this->subType;
    }

    public function getDisplay(): string{
        return $this->display;
    }

    public function equals(Check $check) : bool{
        return $this->hashCode() === $check->hashCode();
    }

    public function hashCode() :string{
        return spl_object_hash($this);
    }
}