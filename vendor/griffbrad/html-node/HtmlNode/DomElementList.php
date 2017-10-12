<?php

namespace HtmlNode;

use DOMDocument;
use DOMElement;
use Zend\Dom\Document\NodeList;
use Zend\Escaper\Escaper;

class DomElementList implements NodeInterface
{
    const HTML = 'html';

    const TEXT = 'text';

    /**
     * @var DOMElement[]
     */
    private $domElements = [];

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param NodeList $domElements
     * @param Escaper $escaper
     */
    public function __construct(NodeList $domElements, Escaper $escaper)
    {
        $this->domElements = $domElements;
        $this->escaper     = $escaper;
    }

    public function setContent($content, $type)
    {
        foreach ($this->domElements as $domElement) {
            foreach ($domElement->childNodes as $childNode) {
                $domElement->removeChild($childNode);
            }
        }

        if (self::TEXT === $type) {
            foreach ($this->domElements as $domElement) {
                $domElement->appendChild($domElement->ownerDocument->createTextNode($content));
            }
        } else {
            foreach ($this->domElements as $domElement) {
                $fragment = $domElement->ownerDocument->createDocumentFragment();
                $fragment->appendXML($content);
                $domElement->appendChild($fragment);
            }
        }

        return $this;
    }

    public function setText($text)
    {
        return $this->setContent($text, self::TEXT);
    }

    public function setHtml($html)
    {
        return $this->setContent($html, self::HTML);
    }

    public function setAttribute($name, $value = null)
    {
        foreach ($this->domElements as $domElement) {
            $domElement->setAttribute($name, $value);
        }

        return $this;
    }

    public function removeAttribute($name)
    {
        foreach ($this->domElements as $domElement) {
            $domElement->removeAttribute($name);
        }

        return $this;
    }

    public function addClass($class)
    {
        foreach ($this->domElements as $domElement) {
            $classes   = $this->getClasses($domElement);
            $classes[] = $class;
            $this->setClasses($domElement, $classes);
        }

        return $this;
    }

    public function removeClass($classToRemove)
    {
        foreach ($this->domElements as $domElement) {
            $classes = $this->getClasses($domElement);

            foreach ($classes as $index => $class) {
                if ($class === $classToRemove) {
                    unset($classes[$index]);
                }
            }

            $this->setClasses($domElement, array_values($classes));
        }

        return $this;
    }

    private function setClasses(DOMElement $domElement, array $classes)
    {
        $domElement->setAttribute(
            'class',
            implode(
                ' ',
                array_map([$this->escaper, 'escapeHtmlAttr'], $classes)
            )
        );
    }

    private function getClasses(DOMElement $domElement)
    {
        $attribute = '';

        if ($domElement->hasAttribute('class')) {
            $attribute = $domElement->getAttribute('class');
        }

        return explode(' ', $attribute);
    }
}
