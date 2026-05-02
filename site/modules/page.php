<?php

class Page {
    private $template;

    public function __construct($template) {
        if (!file_exists($template)) {
            throw new Exception("Шаблон не найден: " . $template);
        }
        $this->template = $template;
    }

    public function Render($data) {
        if (is_array($data)) {
            extract($data);
        }
        
        ob_start();
        include($this->template);
        return ob_get_clean();
    }
}