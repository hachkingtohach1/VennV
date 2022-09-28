<?php

namespace hachkingtohach1\vennv\utils;

use hachkingtohach1\vennv\compat\block\BlockLegacyIds;

final class BlockUtil{

    public static function getStairs() : array{
        $stairs = [
            BlockLegacyIds::STONE_STAIRS,
            BlockLegacyIds::OAK_STAIRS,
            BlockLegacyIds::BIRCH_STAIRS,
            BlockLegacyIds::BRICK_STAIRS,
            BlockLegacyIds::STONE_BRICK_STAIRS,
            BlockLegacyIds::ACACIA_STAIRS,
            BlockLegacyIds::JUNGLE_STAIRS,
            BlockLegacyIds::PURPUR_STAIRS,
            BlockLegacyIds::QUARTZ_STAIRS,
            BlockLegacyIds::SPRUCE_STAIRS,
            BlockLegacyIds::WOODEN_STAIRS,
            BlockLegacyIds::DIORITE_STAIRS,
            BlockLegacyIds::GRANITE_STAIRS,
            BlockLegacyIds::ANDESITE_STAIRS,
            BlockLegacyIds::DARK_OAK_STAIRS,
            BlockLegacyIds::END_BRICK_STAIRS,
            BlockLegacyIds::SANDSTONE_STAIRS,
            BlockLegacyIds::PRISMARINE_STAIRS,
            BlockLegacyIds::COBBLESTONE_STAIRS,
            BlockLegacyIds::NETHER_BRICK_STAIRS,
            BlockLegacyIds::NORMAL_STONE_STAIRS,
            BlockLegacyIds::RED_SANDSTONE_STAIRS,
            BlockLegacyIds::SMOOTH_QUARTZ_STAIRS,
            BlockLegacyIds::DARK_PRISMARINE_STAIRS,
            BlockLegacyIds::POLISHED_DIORITE_STAIRS,
            BlockLegacyIds::POLISHED_GRANITE_STAIRS,
            BlockLegacyIds::RED_NETHER_BRICK_STAIRS,
            BlockLegacyIds::SMOOTH_SANDSTONE_STAIRS,
            BlockLegacyIds::MOSSY_COBBLESTONE_STAIRS,
            BlockLegacyIds::MOSSY_STONE_BRICK_STAIRS,
            BlockLegacyIds::POLISHED_ANDESITE_STAIRS,
            BlockLegacyIds::PRISMARINE_BRICKS_STAIRS,
            BlockLegacyIds::SMOOTH_RED_SANDSTONE_STAIRS
        ];
        return $stairs;
    }

    public static function getIce() : array{
        $ice = [
            BlockLegacyIds::ICE,
            BlockLegacyIds::BLUE_ICE,
            BlockLegacyIds::PACKED_ICE,
            BlockLegacyIds::FROSTED_ICE
        ];
        return $ice;
    }

    public static function getLiquid() : array{
        $liquid = [
            BlockLegacyIds::WATER,
            BlockLegacyIds::LAVA,
            BlockLegacyIds::FLOWING_WATER,
            BlockLegacyIds::FLOWING_LAVA
        ];
        return $liquid;
    }

    public static function getAdhesion() : array{
        $adhesion = [
            BlockLegacyIds::LADDER,
            BlockLegacyIds::VINES,
            BlockLegacyIds::SCAFFOLDING
        ];
        return $adhesion;
    }

    public static function getPlant() : array{
        $plants = [
            BlockLegacyIds::GRASS_PATH,
            BlockLegacyIds::CARROT_BLOCK,
            BlockLegacyIds::SUGARCANE_BLOCK,
            BlockLegacyIds::PUMPKIN_STEM,
            BlockLegacyIds::POTATO_BLOCK,
            BlockLegacyIds::DEAD_BUSH,
            BlockLegacyIds::SWEET_BERRY_BUSH,
            BlockLegacyIds::SAPLING,
            BlockLegacyIds::SEAGRASS,
            BlockLegacyIds::WHEAT_BLOCK,
            BlockLegacyIds::TALL_GRASS,
            BlockLegacyIds::RED_FLOWER,
            BlockLegacyIds::CHORUS_FLOWER,
            BlockLegacyIds::YELLOW_FLOWER,
            BlockLegacyIds::DOUBLE_PLANT,
            BlockLegacyIds::FLOWER_POT_BLOCK,
            BlockLegacyIds::NETHER_WART_PLANT
        ];
        return $plants;
    }

    public static function getDoor() : array{
        $doors = [
            BlockLegacyIds::OAK_DOOR_BLOCK,
            BlockLegacyIds::IRON_DOOR_BLOCK,
            BlockLegacyIds::DARK_OAK_DOOR_BLOCK,
            BlockLegacyIds::BIRCH_DOOR_BLOCK,
            BlockLegacyIds::ACACIA_DOOR_BLOCK,
            BlockLegacyIds::JUNGLE_DOOR_BLOCK,
            BlockLegacyIds::SPRUCE_DOOR_BLOCK,
            BlockLegacyIds::WOODEN_DOOR_BLOCK,
            BlockLegacyIds::DARK_OAK_TRAPDOOR,
            BlockLegacyIds::TRAPDOOR,
            BlockLegacyIds::IRON_TRAPDOOR,
            BlockLegacyIds::BIRCH_TRAPDOOR,
            BlockLegacyIds::ACACIA_TRAPDOOR,
            BlockLegacyIds::JUNGLE_TRAPDOOR,
            BlockLegacyIds::SPRUCE_TRAPDOOR,
            BlockLegacyIds::WOODEN_TRAPDOOR,
            BlockLegacyIds::DARK_OAK_TRAPDOOR
        ];
        return $doors;
    }

    public static function getCarpet() : array{
        $carpets = [
            BlockLegacyIds::CARPET
        ];
        return $carpets;
    }

    public static function getPlate() : array{
        $plates = [
            BlockLegacyIds::CARPET,
            BlockLegacyIds::BIRCH_PRESSURE_PLATE,
            BlockLegacyIds::STONE_PRESSURE_PLATE,
            BlockLegacyIds::ACACIA_PRESSURE_PLATE,
            BlockLegacyIds::JUNGLE_PRESSURE_PLATE,
            BlockLegacyIds::SPRUCE_PRESSURE_PLATE,
            BlockLegacyIds::WOODEN_PRESSURE_PLATE,
            BlockLegacyIds::DARK_OAK_PRESSURE_PLATE,
            BlockLegacyIds::HEAVY_WEIGHTED_PRESSURE_PLATE,
            BlockLegacyIds::LIGHT_WEIGHTED_PRESSURE_PLATE
        ];
        return $plates;
    }

    public static function getSnow() : array{
        $snow = [
            BlockLegacyIds::SNOW_BLOCK,
            BlockLegacyIds::SNOW_LAYER
        ];
        return $snow;
    }

    public static function getElastomers() : array{
        $elastomers = [
            BlockLegacyIds::SLIME_BLOCK
        ];
        return $elastomers;
    }  

    public static function getWeb() : array{
        $webs = [
            BlockLegacyIds::WEB
        ];
        return $webs;
    }
}