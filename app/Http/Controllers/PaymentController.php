<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $payment = Payment::find();
      return Inertia::render('Payment/Index', [
        'payment' => $payment
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Payment/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        Payment::create($request->all());
        return redirect()->route('payments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        if (!$payment) {
            return redirect()->route('payments.index')->with('error', 'Payment not found');
        } else {
            return Inertia::render('Payment/Show', ['payment' => $payment]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

            $payment = Payment::findOrFail($id);
            return Inertia::render('Payments/Edit', [
                'payment' => $payment,
            ]);

   }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($request->all());
        return redirect()->route('payments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('payments.index');
    }
}
