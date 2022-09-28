<?php

namespace hachkingtohach1\vennv\form;

class SimpleForm extends Form{

    const IMAGE_TYPE_PATH = 0;
    const IMAGE_TYPE_URL = 1;

    private string $content = "";
    private array $labelMap = [];

    public function __construct(?callable $callable){
        parent::__construct($callable);
        $this->data["type"] = "form";
        $this->data["title"] = "";
        $this->data["content"] = $this->content;
    }

    public function processData(&$data) : void{
        $data = $this->labelMap[$data] ?? null;
    }

    public function setTitle(string $title) : void{
        $this->data["title"] = $title;
    }

    public function getTitle() : string{
        return $this->data["title"];
    }

    public function getContent() : string{
        return $this->data["content"];
    }

    public function setContent(string $content) : void{
        $this->data["content"] = $content;
    }

    public function addButton(string $text, int $imageType = -1, string $imagePath = "", ?string $label = null) : void{
        $content = ["text" => $text];
        if($imageType !== -1) {
            $content["image"]["type"] = $imageType === 0 ? "path" : "url";
            $content["image"]["data"] = $imagePath;
        }
        $this->data["buttons"][] = $content;
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    public function getButton(int $index) : ?string{
        return $this->data["buttons"][$index]["text"] ?? null;
    }

    public function removeButton(int $index) : void{
        unset($this->data["buttons"][$index]);
        $this->data["buttons"] = array_values($this->data["buttons"]);
    }

    public function setButton(int $index, string $text, int $imageType = -1, string $imagePath = "", ?string $label = null) : void{
        $this->removeButton($index);
        $this->addButton($text, $imageType, $imagePath, $label);
    }

    public function getButtons() : array{
        $result = [];
        foreach($this->data["buttons"] as $button){
            $result[] = $button["text"];
        }
        return $result;
    }

    public function setButtons(array $buttons) : void{
        $this->data["buttons"] = [];
        $this->labelMap = [];
        foreach($buttons as $button){
            $this->addButton($button[0], $button[1] ?? -1, $button[2] ?? "", $button[3] ?? null);
        }
    }
}
