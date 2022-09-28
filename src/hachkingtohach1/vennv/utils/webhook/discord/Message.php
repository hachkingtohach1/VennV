<?php

namespace hachkingtohach1\vennv\utils\webhook\discord;

class Message implements \JsonSerializable{
	
    protected array $data = [];

    public function init(array $embeds = null) : void{
        if($embeds !== null){
            foreach($embeds as $embed){
                $this->addEmbed($embed);
            }
        }
    }

    public function setContent(string $content) : self{
        $this->data["content"] = $content;
        return $this;
    }

    public function getContent() : ?string{
        return $this->data["content"];
    }

    public function getUsername() : ?string{
        return $this->data["username"];
    }

    public function setUsername(string $username) : self{
        $this->data["username"] = $username;
        return $this;
    }

    public function getAvatarURL() : ?string{
        return $this->data["avatar_url"];
    }

    public function setAvatarURL(string $avatarURL) : self{
        $this->data["avatar_url"] = $avatarURL;
        return $this;
    }

    public function addEmbed(Embed $embed) : ?self{
        if(!empty(($arr = $embed->asArray()))){
            $this->data["embeds"][] = $arr;
            return $this;
        }
        return null;
    }

    public function setTextToSpeech(bool $ttsEnabled) : self{
        $this->data["tts"] = $ttsEnabled;
        return $this;
    }

    public function jsonSerialize(){
        return $this->data;
    }

    public static function create(array $embeds = null) : Message{
        return new Message($embeds);
    }
}
