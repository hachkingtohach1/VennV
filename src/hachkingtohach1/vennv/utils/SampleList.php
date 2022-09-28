<?php

namespace hachkingtohach1\vennv\utils;

final class SampleList{

    private int $maxSample = 0;
    private array $sampleList = [];

    public function getMaxSample() : int{
        return $this->maxSample;
    }

    public function getSampleList() : array{
        return $this->sampleList;
    }

    public function setMaxSample(int $int) : void{
        $this->maxSample = $int;
    }

    public function addSampleList(mixed $data) : void{
        $this->sampleList[microtime(true)] = $data;
    }

    public function resetSampleList() : void{
        $this->sampleList = [];
    }

    public function handleSample(mixed $data) : array{
        $result = [];
        if(count($this->sampleList) >= $this->maxSample){
            $result = $this->sampleList;
            $this->sampleList = [];
        }
        $this->addSampleList($data);
        return $result;
    }
}