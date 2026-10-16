<?php

namespace App\Http\Controllers;

use App\Models\AnnualProcedure;
use App\Models\BucketItem;
use App\Models\Payment;
use App\Models\PendingBucket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class CartController extends Controller
{
    // Obtiene o crea el carrito activo del usuario
    private function getActiveBucket()
    {
        return PendingBucket::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'active']
        );
    }

    public function index()
    {
        $bucket = $this->getActiveBucket();

        $cartItems = $bucket->items()
            ->with('payable')
            ->where('status', 'in_cart')
            ->get();

        $standbyItems = $bucket->items()
            ->with('payable')
            ->where('status', 'standby')
            ->get();

        $totalPagar = $cartItems->sum('item_amount');

        // Consultar servicios o trámites pendientes para ofrecer agregarlos
        $pagosDisponibles = Payment::whereHas('service', fn($q) => $q->where('user_id', Auth::id()))
            ->where('status', 'pending')
            ->get();

        $tramitesDisponibles = AnnualProcedure::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        return view('cart.index', compact('cartItems', 'standbyItems', 'totalPagar', 'pagosDisponibles', 'tramitesDisponibles'));
    }

    // Agregar un servicio o trámite a la bandeja
    public function addItem(Request $request)
    {
        $request->validate([
            'payable_type' => 'required|string',
            'payable_id'   => 'required|integer',
            'item_amount'  => 'required|numeric|min:0.01',
        ]);

        $bucket = $this->getActiveBucket();

        // Evitar duplicados en el carrito activo
        $exists = $bucket->items()
            ->where('payable_type', $request->payable_type)
            ->where('payable_id', $request->payable_id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Este concepto ya se encuentra en tu bandeja.');
        }

        $bucket->items()->create([
            'payable_type' => $request->payable_type,
            'payable_id'   => $request->payable_id,
            'item_amount'  => $request->item_amount,
            'status'       => 'in_cart',
        ]);

        return back()->with('success', 'Concepto agregado a la bandeja.');
    }

    // Alternar estatus entre 'in_cart' y 'standby' (guardar para después)
    public function toggleStandby(BucketItem $item)
    {
        if ($item->bucket->user_id !== Auth::id()) {
            abort(403);
        }

        $item->status = $item->status === 'in_cart' ? 'standby' : 'in_cart';
        $item->save();

        $mensaje = $item->status === 'standby'
            ? 'Elemento guardado en Stand-by para después.'
            : 'Elemento activado para liquidar hoy.';

        return back()->with('success', $mensaje);
    }

    // Eliminar de la bandeja
    public function removeItem(BucketItem $item)
    {
        if ($item->bucket->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();
        return back()->with('success', 'Elemento retirado de la bandeja.');
    }

    // Checkout con Stripe
    public function checkout()
    {
        $bucket = $this->getActiveBucket();
        $cartItems = $bucket->items()->with('payable')->where('status', 'in_cart')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'No tienes conceptos activos para liquidar.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        foreach ($cartItems as $item) {
            $nombreConcepto = 'Pago de Servicio';
            if ($item->payable instanceof Payment) {
                $nombreConcepto = 'Pago: ' . ($item->payable->service->name ?? 'Servicio');
            } elseif ($item->payable instanceof AnnualProcedure) {
                $nombreConcepto = 'Trámite: ' . $item->payable->title;
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'mxn',
                    'product_data' => [
                        'name' => $nombreConcepto,
                    ],
                    'unit_amount' => (int) round($item->item_amount * 100), // Stripe maneja centavos
                ],
                'quantity' => 1,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'customer_email'       => Auth::user()->email,
            'success_url'          => route('cart.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => route('cart.index'),
        ]);

        return redirect($session->url);
    }

    // Retorno exitoso tras pagar en Stripe
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        if (!$sessionId) {
            return redirect()->route('cart.index');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $stripeSession = StripeSession::retrieve($sessionId);

        if ($stripeSession->payment_status === 'paid') {
            $bucket = $this->getActiveBucket();
            $cartItems = $bucket->items()->where('status', 'in_cart')->get();

            // 1. Registrar la transacción
            $transaction = Transaction::create([
                'user_id'            => Auth::id(),
                'stripe_checkout_id' => $stripeSession->id,
                'stripe_payment_intent' => $stripeSession->payment_intent,
                'amount'             => ($stripeSession->amount_total / 100),
                'currency'           => $stripeSession->currency,
                'payment_status'     => 'succeeded',
            ]);

            // 2. Marcar cada pago/trámite como liquidado
            foreach ($cartItems as $item) {
                if ($item->payable instanceof Payment) {
                    $item->payable->update([
                        'status'  => 'paid',
                        'paid_at' => now(),
                    ]);
                } elseif ($item->payable instanceof AnnualProcedure) {
                    $item->payable->update([
                        'status' => 'completed',
                    ]);
                }
            }

            // 3. Limpiar ítems pagados y asociar transacción
            $bucket->update(['transaction_id' => $transaction->id]);
            $bucket->items()->where('status', 'in_cart')->delete();

            return view('cart.success', compact('transaction'));
        }

        return redirect()->route('cart.index')->with('error', 'El pago no se pudo validar.');
    }
}