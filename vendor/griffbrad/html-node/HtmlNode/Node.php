<?php

namespace HtmlNode;

use Zend\Dom\Document;
use Zend\Dom\Document\Query;
use Zend\Escaper\Escaper;

class Node implements NodeInterface
{
    const HTML = 'html';

    const TEXT = 'text';

    private $name;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array
     */
    private $classes = [];

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $contentType = self::TEXT;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var Document
     */
    private $domDocument;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public static function create($name)
    {
        return new Node($name);
    }

    public function setEscaper(Escaper $escaper)
    {
        $this->escaper = $escaper;

        return $this;
    }

    public function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = new Escaper();
        }

        return $this->escaper;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAttribute($name, $value = null)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function getAttribute($name)
    {
        if (!array_key_exists($name, $this->attributes)) {
            return null;
        } else {
            return $this->attributes[$name];
        }
    }

    public function removeAttribute($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            unset($this->attributes[$name]);
        }

        return $this;
    }

    public function addClass($class)
    {
        $this->classes[] = $class;

        return $this;
    }

    public function removeClass($classToRemove)
    {
        if (!in_array($classToRemove, $this->classes)) {
            return $this;
        }

        foreach ($this->classes as $index => $class) {
            if ($classToRemove === $class) {
                unset($this->classes[$index]);
            }
        }

        $this->classes = array_values($this->classes);

        return $this;
    }

    public function hasClass($class)
    {
        return in_array($class, $this->classes);
    }

    public function setContent($content, $type)
    {
        if (self::HTML !== $type && self::TEXT !== $type) {
            throw new Exception('Content type must be HTML or TEXT.');
        }

        $this->domDocument = null;
        $this->content     = $content;
        $this->contentType = $type;

        return $this;
    }

    public function setHtml($html)
    {
        return $this->setContent($html, self::HTML);
    }

    public function setText($text)
    {
        return $this->setContent($text, self::TEXT);
    }

    public function find($queryString)
    {
        $domElements = Query::execute($queryString, $this->getDomDocument(), Query::TYPE_CSS);

        return new DomElementList(
            $domElements,
            $this->getEscaper()
        );
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->domDocument) {
            $doc  = $this->domDocument->getDomDocument();
            $html = $doc->saveHtml();

            $this->setContent(
                preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $html),
                self::HTML
            );
        }

        if (!$this->content) {
            return sprintf(
                '<%s%s />',
                $this->name,
                $this->renderAttributeString()
            );
        } else {
            return sprintf(
                '<%s%s>%s</%s>',
                $this->name,
                $this->renderAttributeString(),
                (self::HTML === $this->contentType ? $this->content : $this->getEscaper()->escapeHtml($this->content)),
                $this->name
            );
        }
    }

    public function __toString()
    {
        return $this->render();
    }

    private function renderAttributeString()
    {
        $out = [];

        foreach ($this->attributes as $attribute => $value) {
            if (null === $value) {
                $out[] = $attribute;
            } else {
                $out[] = sprintf('%s="%s"', $attribute, $this->getEscaper()->escapeHtmlAttr($value));
            }
        }

        if (count($this->classes)) {
            $out[] = sprintf(
                'class="%s"',
                implode(
                    ' ',
                    array_map([$this->getEscaper(), 'escapeHtmlAttr'], $this->classes)
                )
            );
        }

        if (!count($out)) {
            return '';
        } else {
            return ' ' . implode(' ', $out);
        }
    }

    private function getDomDocument()
    {
        if (!$this->domDocument) {
            $this->domDocument = new Document($this->content);
        }

        return $this->domDocument;
    }
}
