<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardItem extends Component
{
    public $title;
    public $description;
    public $image;
    public $link;
    public $linkText;
    public $centerContent;
    public $fullImage;

    public function __construct($title, $description, $image, $link, $linkText, $centerContent = false, $fullImage = false)
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->link = $link;
        $this->linkText = $linkText;
        $this->centerContent = $centerContent;
        $this->fullImage = $fullImage;
    }

    public function render()
    {
        return view('components.card-item');
    }
}
