<?php

namespace hachkingtohach1\vennv\data\transaction\effects;

class EffectHandler implements IEffectHandler{

    private array $effects = [];

    public function handleEffect(int $effectId, int $amplifier, int $duration) : void{
        $this->effects[$effectId] = [
            "amplifier" => $amplifier, 
            "duration" => $duration
        ];
    }

    public function getEffects() : array{
        return $this->effects;
    }

    public function getEffect(int $effectId) : array{
        return $this->effects[$effectId];
    }

    public function hasEffect(int $effectId) : bool{
        return isset($this->effects[$effectId]);
    }

    public function removeEffect(int $effectId) : void{
        unset($this->effects[$effectId]);
    }
}