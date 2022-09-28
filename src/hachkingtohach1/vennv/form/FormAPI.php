<?php

namespace hachkingtohach1\vennv\form;

class FormAPI{

    public function createCustomForm(callable $function = null) : CustomForm{
        return new CustomForm($function);
    }

    public function createSimpleForm(callable $function = null) : SimpleForm{
        return new SimpleForm($function);
    }

    public function createModalForm(callable $function = null) : ModalForm{
        return new ModalForm($function);
    }
}
