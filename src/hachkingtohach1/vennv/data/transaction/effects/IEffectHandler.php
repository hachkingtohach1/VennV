<?php

namespace hachkingtohach1\vennv\data\transaction\effects;

interface IEffectHandler{

    public function handleEffect(int $effectId, int $amplifier, int $duration) : void;

    public function getEffects() :array;

    public function getEffect(int $effectId) : array;

    public function hasEffect(int $effectId) : bool;

    public function removeEffect(int $effectId) : void;
}