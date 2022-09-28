<?php

namespace hachkingtohach1\vennv\utils\php;

final class LinkedList{

    private int $size = 0;
    private Node|null $first = null;
    private Node|null $last = null;

    public function addFirst(mixed $item) : void{
        $this->linkFirst($item);
    }

    private function linkFirst(mixed $item) : void{
        $f = $this->first;
        $newNode = new Node();
        $newNode->set(null, $item, $f);
        $this->first = $newNode;
        if($f == null){
            $this->last = $newNode;
        }else{
            $f->prev = $newNode;
        }
        $this->size++;
    }

    public function addLast(mixed $item) : void{
        $this->linkLast($item);
    }

    public function add(mixed $item) : void{
        $this->linkLast($item);
    }

    private function linkLast(mixed $item) : void{
        $l = $this->last;
        $newNode = new Node();
        $newNode->set($l, $item, null);
        $this->last = $newNode;
        if($l == null){
            $this->first = $newNode;
        }else{
            $l->next = $newNode;
        }
        $this->size++;
    }

    public function addBef(mixed $item, Node $succ) : void{
        $this->linkBefore($item, $succ);
    }

    private function linkBefore(mixed $item, Node $succ) : void{
        $pred = $succ->prev;
        $newNode = new Node();
        $newNode->set($pred, $item, $succ);
        $succ->prev = $newNode;
        if($pred == null){
            $this->first = $newNode;
        }else{
            $pred->next = $newNode;
        }
        $this->size++;
    }

    public function unlinkf(Node $f) : void{
        $this->unlinkFirst($f);
    }

    private function unlinkFirst(Node $f) : mixed{
        $item = $f->item;
        $next = $f->next;
        $f->item = null;
        $f->next = null; 
        $this->first = $next;
        if($next == null){
            $this->last = null;
        }else{
            $next->prev = null;
        }
        $this->size--;
        return $item;
    }

    public function unlinkL(Node $l) : void{
        $this->unlinkLast($l);
    }

    private function unlinkLast(Node $l) : mixed{
        $item = $l->item;
        $prev = $l->prev;
        $l->item = null;
        $l->prev = null;
        $this->last = $prev;
        if($prev == null){
            $this->first = null;
        }else{
            $prev->next = null;
        }
        $this->size--;
        return $item;
    }

    public function unlink(Node $x) : mixed{
        $item = $x->item;
        $next = $x->next;
        $prev = $x->prev;
        if($prev == null){
            $this->first = $next;
        }else{
            $prev->next = $next;
            $x->prev = null;
        }
        if($next == null){
            $this->last = $prev;
        }else{
            $next->prev = $prev;
            $x->next = null;
        }
        $x->item = null;
        $this->size--;
        return $item;
    }

    public function getFirst() : mixed{
        $f = $this->first;
        if($f == null){
            throw new \Exception("LinkedList > Don't have first!");
        }
        return $f->item;
    }

    public function getLast() : mixed{
        $l = $this->last;
        if($l == null){
            throw new \Exception("LinkedList > Don't have last!");
        }
        return $l->item;
    }

    public function removeFirst() :mixed{
        $f = $this->first;
        if($f == null){
            throw new \Exception("LinkedList > Can't remove first, because don't have first!");
        }
        return $this->unlinkFirst($f);
    }

    public function removeLast() :mixed{
        $l = $this->last;
        if($l == null){
            throw new \Exception("LinkedList > Can't remove last, because don't have last!");
        }
        return $this->unlinkLast($l);
    }

    public function toArrayFirst() : array{
        $items = [];
        $current = $this->first;
        while($current != null){
            array_push($items, $current->item);
            $current = $current->next;
        }
        return $items;
    }

    public function toArrayLast() : array{
        $items = [];
        $current = $this->last;
        while($current != null){
            array_push($items, $current->item);
            $current = $current->prev;
        }
        return $items;
    }

    public function clear() : void{
        $current = $this->first;
        while($current !== null){
            $next = $current->next;
            $current->item = null;
            $current->next = null;
            $current->prev = null;
            $current = $next;
        }
        $this->first = null;
        $this->last = null;
        $this->size = 0;
    }

    public function size() : int{
        return $this->size;
    }

    public function isEmpty() : bool{
        return $this->size == 0;
    }

    public function contains(mixed $item) : bool{
        return $this->indexOf($item) != -1;
    }

    public function indexOf(mixed $item) : int{
        $index = 0;
        if($item == null){
            for($x = $this->first; $x != null; $x = $x->next){
                if($x->item == null){
                    return $index;
                }
                $index++;
            }
        }else{
            for($x = $this->first; $x != null; $x = $x->next){
                if($item == $x->item){
                    return $index;
                }
                $index++;
            }
        }
        return -1;
    }

    public function lastIndexOf(mixed $item) : int{
        $index = $this->size;
        if($item == null){
            for($x = $this->last; $x != null; $x = $x->prev){
                $index--;
                if($x->item == null){
                    return $index;
                }
            }
        }else{
            for($x = $this->last; $x != null; $x = $x->prev){
                $index--;
                if($item == $x->item){
                    return $index;
                }
            }
        }
        return -1;
    }

    public function removeFirstOccurrence(mixed $item) : bool{
        if($item == null){
            for($x = $this->first; $x != null; $x = $x->next){
                if($x->item == null){
                    $this->unlink($x);
                    return true;
                }
            }
        }else{
            for($x = $this->first; $x != null; $x = $x->next){
                if($item == $x->item){
                    $this->unlink($x);
                    return true;
                }
            }
        }
        return false;
    }

    public function removeLastOccurrence(mixed $item) : bool{
        if($item == null){
            for($x = $this->last; $x != null; $x = $x->prev){
                if($x->item == null){
                    $this->unlink($x);
                    return true;
                }
            }
        }else{
            for($x = $this->last; $x != null; $x = $x->prev){
                if($item == $x->item){
                    $this->unlink($x);
                    return true;
                }
            }
        }
        return false;
    }
}