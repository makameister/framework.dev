<?php

namespace Framework\Renderer;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use Psr\Container\ContainerInterface;

class TwigRendererTest extends TestCase
{

    private $renderer;

    public function setUp(): void
    {
        $prophet = new Prophet;

        $container = $prophet->prophesize(ContainerInterface::class);
        $container->get('view.path')->willReturn('views');

        $this->renderer = call_user_func(new TwigRendererFactory(), $container->reveal());
    }

    public function testRendererFactory()
    {
        $this->assertInstanceOf(TwigRenderer::class, $this->renderer);
    }

    public function testRenderTheRightPath()
    {
        $this->renderer->addViewPath('test', 'tests/Resources/Views');
        $rendered = $this->renderer->render('@test/test');

        $this->assertEquals("<h1>Hello from test page</h1>", $rendered);
    }

    public function testRenderWithParams()
    {
        $this->renderer->addViewPath('test', 'tests/Resources/Views');
        $rendered = $this->renderer->render('@test/params', [
            'slug' => 'slug-de-demo'
        ]);

        $this->assertEquals("<h1>slug-de-demo</h1>", $rendered);
    }

    public function testRenderWithGlobal()
    {
        $this->renderer->addViewPath('test', 'tests/Resources/Views');
        $this->renderer->addGlobal('slug', 'un-slug-global');
        $rendered = $this->renderer->render('@test/params');

        $this->assertEquals("<h1>un-slug-global</h1>", $rendered);
    }


}
