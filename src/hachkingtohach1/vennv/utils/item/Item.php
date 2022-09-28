<?php

namespace hachkingtohach1\vennv\utils\item;

class Item implements IItem{

    private int $id;
    private int $meta;
    private int $count;
    private string $name;
    private array $lore;
    private array $enchants;
    private array $flags;
    private array $nbt;
    private array $tags;
    private array $canPlaceOn;
    private array $canDestroy;
    private array $attributeModifiers;
    private array $customModelData;

    public function set(int $id, int $meta = 0, int $count = 1, string $name = "unkown item", array $lore = [], array $enchants = [], array $flags = [], array $nbt = [], array $tags = [], array $canPlaceOn = [], array $canDestroy = [], array $attributeModifiers = [], array $customModelData = []): void{
        $this->id = $id;
        $this->meta = $meta;
        $this->count = $count;
        $this->name = $name;
        $this->lore = $lore;
        $this->enchants = $enchants;
        $this->flags = $flags;
        $this->nbt = $nbt;
        $this->tags = $tags;
        $this->canPlaceOn = $canPlaceOn;
        $this->canDestroy = $canDestroy;
        $this->attributeModifiers = $attributeModifiers;
        $this->customModelData = $customModelData;
    }

    public function getId() : int{
        return $this->id;
    }

    public function getMeta(): int{
        return $this->meta;
    }

    public function getCount() : int{
        return $this->count;
    }

    public function getName() : string{
        return $this->name;
    }

    public function getLore() : array{
        return $this->lore;
    }

    public function getEnchants() : array{
        return $this->enchants;
    }

    public function getFlags() : array{
        return $this->flags;
    }

    public function getNbt() : array{
        return $this->nbt;
    }

    public function getTags() : array{
        return $this->tags;
    }

    public function getCanPlaceOn() : array{
        return $this->canPlaceOn;
    }

    public function getCanDestroy() : array{
        return $this->canDestroy;
    }

    public function getAttributeModifiers() : array{
        return $this->attributeModifiers;
    }

    public function getCustomModelData() : array{
        return $this->customModelData;
    }

    public function get() : array{
        $item = [
            "id" => $this->id,
            "meta" => $this->meta,
            "count" => $this->count,
            "name" => $this->name,
            "lore" => $this->lore,
            "enchants" => $this->enchants,
            "flags" => $this->flags,
            "nbt" => $this->nbt,
            "tags" => $this->tags,
            "canPlaceOn" => $this->canPlaceOn,
            "canDestroy" => $this->canDestroy,
            "attributeModifiers" => $this->attributeModifiers,
            "customModelData" => $this->customModelData
        ];
        return $item;
    }
}