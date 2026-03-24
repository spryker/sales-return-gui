<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesDetailBlockRendererPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 *
 * @api
 *
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class SalesReturnListBlockRendererPlugin extends AbstractPlugin implements SalesDetailBlockRendererPluginInterface
{
    protected const string BLOCK_URL = '/sales-return-gui/sales/list';

    /**
     * {@inheritDoc}
     * - Checks if the block URL is '/sales-return-gui/sales/list'.
     *
     * @api
     */
    public function isApplicable(string $blockUrl): bool
    {
        return $blockUrl === static::BLOCK_URL;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getTemplatePath(string $blockUrl): string
    {
        return '@SalesReturnGui/Sales/list.twig';
    }

    /**
     * {@inheritDoc}
     * - Returns order return table for the given order as template data.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $blockUrl
     *
     * @return array<string, mixed>
     */
    public function getData(Request $request, OrderTransfer $orderTransfer, string $blockUrl): array
    {
        $orderReturnTable = $this->getFactory()->createOrderReturnTable($orderTransfer);

        return ['orderReturnTable' => $orderReturnTable->render()];
    }
}
