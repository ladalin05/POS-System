<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\CheckoutRequest;
use App\Http\Requests\Pos\HoldOrderRequest;
use App\Services\PosService;
use Illuminate\Http\Request;
use Exception;

class PosController extends Controller
{
    protected PosService $posService;

    public function __construct(PosService $posService)
    {
        $this->posService = $posService;
    }

    // -----------------------------------------------------------------
    // Main screen
    // -----------------------------------------------------------------

    public function index()
    {
        return view('pos.index', $this->posService->getIndexData());
    }

    // -----------------------------------------------------------------
    // Catalog
    // -----------------------------------------------------------------

    public function fetchProducts(Request $request)
    {
        return $this->successResponse(
            'Products fetched successfully.',
            ['products' => $this->posService->fetchProducts($request)]
        );
    }

    /**
     * GET /pos/barcode/{code}
     */
    public function findByBarcode(Request $request, string $code)
    {
        $product = $this->posService->findByBarcode($request, $code);

        if (!$product) {
            return $this->errorResponse("No product found for code {$code}", 404);
        }

        return $this->successResponse('Product found.', ['product' => $product]);
    }

    // -----------------------------------------------------------------
    // Checkout
    // -----------------------------------------------------------------

    public function checkout(CheckoutRequest $request)
    {
        $validated = $request->validated();

        try {
            $sale = $this->posService->checkout($validated, $request);

            return $this->successResponse('Sale completed successfully.', ['sale' => $sale]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    // -----------------------------------------------------------------
    // Sales history
    // -----------------------------------------------------------------

    public function listSales(Request $request)
    {
        try {
            $sales = $this->posService->listSales($request->all());

            return $this->successResponse('Sales fetched successfully.', $sales);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function showSale(string $id)
    {
        try {
            $sale = $this->posService->getSale($id);

            return $this->successResponse('Sale fetched successfully.', ['sale' => $sale]);
        } catch (Exception $e) {
            return $this->errorResponse('Sale not found.', 404);
        }
    }

    public function updateSale(Request $request, string $id)
    {
        try {
            $sale = $this->posService->updateSale($request->all(), $id);

            return $this->successResponse('Sale updated successfully.', ['sale' => $sale]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    // -----------------------------------------------------------------
    // Hold / resume order (suspends + suspend_items)
    // -----------------------------------------------------------------

    public function holdOrder(HoldOrderRequest $request)
    {
        $suspend = $this->posService->holdOrder($request->validated());

        return $this->successResponse('Order held successfully.', ['hold_id' => $suspend->id]);
    }

    public function listHolds()
    {
        return $this->successResponse(
            'Held orders fetched successfully.',
            ['holds' => $this->posService->listHolds()]
        );
    }

    public function resumeHold(int $id)
    {
        try {
            $items = $this->posService->resumeHold($id);

            return $this->successResponse('Order resumed successfully.', ['items' => $items]);
        } catch (Exception $e) {
            return $this->errorResponse('Hold not found.', 404);
        }
    }
}