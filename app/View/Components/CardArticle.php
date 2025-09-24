<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardArticle extends Component
{
    public $imageArticle;
    public $titleArticle;
    public $dateArticle;
    public $descriptionArticle;
    public $linkArticle;
    public function __construct($imageArticle, $titleArticle, $dateArticle, $descriptionArticle, $linkArticle)
    {
        $this->imageArticle = $imageArticle;
        $this->titleArticle = $titleArticle;
        $this->dateArticle = $dateArticle;
        $this->descriptionArticle = $descriptionArticle;
        $this->linkArticle = $linkArticle;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card-article');
    }
}
