<?php

namespace hachkingtohach1\vennv\utils\php;

final class Node{

    public mixed $item;
    public mixed $next;
    public mixed $prev;

    public function set(Node|null $prev, mixed $item, Node|null $next) : void{
        $this->item = $item;
        $this->next = $next;
        $this->prev = $prev;
    }

    public function __toString() : string{
        return (string) $this->item;
    }

    public function __clone() : void{
        $this->set($this->prev, $this->item, $this->next);
    }

    public function __debugInfo() : array{
        return [
            "item" => $this->item,
            "next" => $this->next,
            "prev" => $this->prev
        ];
    }
}