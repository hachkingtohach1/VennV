<?php

namespace hachkingtohach1\vennv\utils\item;

interface IItem{
    
    public function set(int $id, int $meta = 0, int $count = 1, string $name = "unkown item", array $lore = [], array $enchants = [], array $flags = [], array $nbt = [], array $tags = [], array $canPlaceOn = [], array $canDestroy = [], array $attributeModifiers = [], array $customModelData = []): void;
    
    public function getId() : int;
    
    public function getMeta(): int;
    
    public function getCount() : int;
    
    public function getName() : string;
    
    public function getLore() : array;
    
    public function getEnchants() : array;
    
    public function getFlags() : array;
    
    public function getNbt() : array;
    
    public function getTags() : array;
    
    public function getCanPlaceOn() : array;
    
    public function getCanDestroy() : array;
    
    public function getAttributeModifiers() : array;
    
    public function getCustomModelData() : array;
}