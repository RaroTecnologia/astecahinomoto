<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NewsList extends Component
{
    public $noticias;

    /**
     * Create a new component instance.
     *
     * @param \Illuminate\Database\Eloquent\Collection $noticias
     */
    public function __construct($noticias)
    {
        $this->noticias = $noticias;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.news-list');
    }
}
