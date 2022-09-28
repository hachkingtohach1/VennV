<?php

namespace hachkingtohach1\vennv\machine;

use hachkingtohach1\vennv\data\manager\PlayerData;

interface IMachineLearning{

    public function train(PlayerData $data) :string;

    public function check(string $text) : IMachineLearning;
}