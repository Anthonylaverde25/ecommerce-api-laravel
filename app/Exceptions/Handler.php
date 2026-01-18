<?php

namespace App\Exceptions;

use App\Domain\Tax\Exceptions\TaxNotFoundException;
use App\Domain\Tax\Exceptions\TaxUpdateException;
use App\Domain\Family\Exceptions\FamilyNotFoundException;
use App\Domain\Family\Exceptions\FamilyUpdateException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Manejar TaxNotFoundException
        $this->renderable(function (TaxNotFoundException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tax not found',
                    'error' => $e->getMessage()
                ], 404);
            }
        });

        // Manejar TaxUpdateException
        $this->renderable(function (TaxUpdateException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to update tax',
                    'error' => $e->getMessage()
                ], 500);
            }
        });

        // Manejar FamilyNotFoundException
        $this->renderable(function (FamilyNotFoundException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Family not found',
                    'error' => $e->getMessage()
                ], 404);
            }
        });

        // Manejar FamilyUpdateException
        $this->renderable(function (FamilyUpdateException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to update family',
                    'error' => $e->getMessage()
                ], 500);
            }
        });
    }
}
