<?php

namespace HtmlNode;

interface NodeInterface
{
    public function setContent($content, $type);

    public function setHtml($html);

    public function setText($text);

    public function setAttribute($name, $value = null);

    public function removeAttribute($name);

    public function addClass($class);

    public function removeClass($classToRemove);
}
