<?php

namespace App\Http\Controllers;

use App\Models\Calculations;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    /**
     * Retrieve all calculations from the database.
     * Returns them in descending order (newest first)
     * so the ticker tape shows the most recent calculations at the top.
     *
     * GET /api/calculations
     */
    public function index()
    {
        return response()->json(
            Calculations::latest()->get()
        );
    }

    /**
     * Save a new calculation to the database.
     * The request expects an expression (e.g. "9 + 3")
     * and a result (e.g. 12) sent from the Vue frontend.
     *
     * Validation ensures we don't save incomplete or
     * malformed data to the database.
     *
     * Returns the newly created calculation as JSON
     * with a 201 "Created" HTTP status code.
     *
     * POST /api/calculations
     */
    public function store(Request $request)
    {
        $request->validate([
            'expression' => 'required|string',
            'result'     => 'required|numeric',
        ]);

        $calculation = Calculations::create([
            'expression' => $request->expression,
            'result'     => $request->result,
        ]);

        return response()->json($calculation, 201);
    }

    /**
     * Delete a single calculation by its ID.
     * Laravel's route model binding automatically finds
     * the Calculation record matching the {calculation} ID
     * in the URL — no manual query needed.
     *
     * Returns a 204 "No Content" HTTP status code,
     * which is the standard response for a successful delete
     * that has nothing to return.
     *
     * DELETE /api/calculations/{calculation}
     */
    public function destroy(Calculations $calculation)
    {
        $calculation->delete();

        return response()->json(null, 204);
    }

    /**
     * Delete all calculations from the database at once.
     * Uses truncate() instead of delete() because truncate
     * is faster — it empties the entire table in one operation
     * rather than deleting row by row.
     *
     * This powers the "Clear All" button on the ticker tape.
     *
     * Returns a 204 "No Content" HTTP status code.
     *
     * DELETE /api/calculations
     */
    public function destroyAll()
    {
        Calculations::truncate();

        return response()->json(null, 204);
    }
}
