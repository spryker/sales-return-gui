<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturnGui\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesReturnGui\Communication\Plugin\Sales\SalesReturnListBlockRendererPlugin;
use SprykerTest\Zed\SalesReturnGui\SalesReturnGuiCommunicationTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\Loader\LoaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturnGui
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group SalesReturnListBlockRendererPluginTest
 * Add your own group annotations below this line
 */
class SalesReturnListBlockRendererPluginTest extends Unit
{
    protected const string BLOCK_URL = '/sales-return-gui/sales/list';

    protected const string OTHER_URL = '/other/url';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    protected const string SERVICE_TWIG = 'twig';

    protected const string SERVICE_REQUEST_STACK = 'request_stack';

    protected const string DEFAULT_OMS_PROCESS_NAME = 'Test01';

    protected SalesReturnGuiCommunicationTester $tester;

    public function getBlockRendererPlugin(): SalesReturnListBlockRendererPlugin
    {
        return new SalesReturnListBlockRendererPlugin();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->getContainer()->set(static::SERVICE_TWIG, $this->createTwigMock());

        $requestStack = new RequestStack();
        $requestStack->push(Request::create('/'));
        $this->tester->getContainer()->set(static::SERVICE_REQUEST_STACK, $requestStack);
    }

    public function testIsApplicableReturnsTrueForMatchingUrl(): void
    {
        // Arrange
        $plugin = $this->getBlockRendererPlugin();

        // Act
        $result = $plugin->isApplicable(static::BLOCK_URL);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsApplicableReturnsFalseForNonMatchingUrl(): void
    {
        // Arrange
        $plugin = $this->getBlockRendererPlugin();

        // Act
        $result = $plugin->isApplicable(static::OTHER_URL);

        // Assert
        $this->assertFalse($result);
    }

    public function testGetTemplatePathReturnsExpectedPath(): void
    {
        // Arrange
        $plugin = $this->getBlockRendererPlugin();

        // Act
        $result = $plugin->getTemplatePath(static::BLOCK_URL);

        // Assert
        $this->assertSame('@SalesReturnGui/Sales/list.twig', $result);
    }

    public function testGetDataReturnsOrderReturnTable(): void
    {
        // Arrange
        $plugin = $this->getBlockRendererPlugin();
        $orderTransfer = (new OrderTransfer())->setIdSalesOrder(0);

        // Act
        $result = $plugin->getData(new Request(), $orderTransfer, static::BLOCK_URL);

        // Assert
        $this->assertArrayHasKey('orderReturnTable', $result);
    }

    public function testGetDataReturnsRenderedTableForExistingOrder(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        $plugin = $this->getBlockRendererPlugin();

        // Act
        $result = $plugin->getData(new Request(), $orderTransfer, static::BLOCK_URL);

        // Assert
        $this->assertArrayHasKey('orderReturnTable', $result);
        $this->assertIsString($result['orderReturnTable']);
    }

    protected function createTwigMock(): Environment
    {
        $twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $twigMock->method('render')->willReturn('');
        $twigMock->method('getLoader')->willReturn($this->createChainLoader());

        return $twigMock;
    }

    protected function createChainLoader(): LoaderInterface
    {
        return new ChainLoader();
    }
}
