<?php

namespace HtmlNode;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit_Framework_TestCase;

class NodeTest extends PHPUnit_Framework_TestCase
{
    public function testNodeWithNoAttributesOrContentIsCorrectlyRendered()
    {
        $node = new Node('a');
        $this->assertEquals('<a />', $node->render());
    }

    public function testCanCastNodeToString()
    {
        $node = new Node('a');
        $this->assertEquals('<a />', (string) $node);
    }

    public function testNodeWithContentButNoAttributesIsCorrectlyRendered()
    {
        $node = new Node('a');
        $node->setContent('Content', Node::TEXT);
        $this->assertEquals('<a>Content</a>', $node->render());
    }

    public function testCallingSetTextCallsSetContentWithTextContentType()
    {
        $node = new Node('a');
        $node->setText('&');
        $this->assertEquals('<a>&amp;</a>', $node->render());
    }

    public function testCallingSetHtmlCallsSetContentWithHtmlContentType()
    {
        $node = new Node('a');
        $node->setHtml('&');
        $this->assertEquals('<a>&</a>', $node->render());
    }

    public function testAttributesWithoutValueAreRenderedWithNoQuotedValue()
    {
        $node = new Node('input');
        $node->setAttribute('autofocus');
        $this->assertEquals('<input autofocus />', $node->render());
    }

    public function testCanAddClassToNode()
    {
        $node = new Node('a');
        $node->addClass('my-class');
        $this->assertEquals('<a class="my-class" />', $node->render());
    }

    public function testCanRemoveClassFromNode()
    {
        $node = new Node('a');
        $node->addClass('my-class');
        $node->removeClass('my-class');
        $this->assertEquals('<a />', $node->render());
    }
}
