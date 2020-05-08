<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Controller;

use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class ReturnSlipController extends AbstractController
{
    protected const PARAM_ID_SALES_RETURN = 'id-sales-return';

    protected const ERROR_MESSAGE_RETURN_NOT_FOUND = 'Return with id "%id%" was not found.';
    protected const ERROR_MESSAGE_PARAM_ID = '%id%';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Controller\ReturnController::indexAction()
     */
    protected const ROUTE_RETURN_LIST = '/sales-return-gui/return';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function printAction(Request $request)
    {
        $response = $this->executePrintAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->viewResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executePrintAction(Request $request)
    {
        $idReturn = $this->castId($request->query->get(static::PARAM_ID_SALES_RETURN));

        $returnTransfer = $this->getFactory()
            ->getSalesReturnFacade()
            ->getReturns((new ReturnFilterTransfer())->addIdReturn($idReturn))
            ->getReturns()
            ->getIterator()
            ->current();

        if (!$returnTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_RETURN_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idReturn,
            ]);

            return $this->redirectResponse(static::ROUTE_RETURN_LIST);
        }

        return [
            'return' => $this->sortReturnItemByOrderReference($returnTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    protected function sortReturnItemByOrderReference(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $returnTransfer->getReturnItems()->uasort(
            function (ReturnItemTransfer $firstReturnItemTransfer, ReturnItemTransfer $secondReturnItemTransfer) {
                return strcmp($firstReturnItemTransfer->getOrderItem()->getOrderReference(), $secondReturnItemTransfer->getOrderItem()->getOrderReference());
            }
        );

        return $returnTransfer;
    }
}
