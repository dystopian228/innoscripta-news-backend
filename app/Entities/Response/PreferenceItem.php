<?php

namespace App\Entities\Response;

class PreferenceItem
{
    public string $symbol;
    public string $text;
    public bool $checked;

    public function __construct($symbol, $text, $checked = false)
    {
        $this->symbol = $symbol;
        $this->text = $text;
        $this->checked = $checked;
    }
}
