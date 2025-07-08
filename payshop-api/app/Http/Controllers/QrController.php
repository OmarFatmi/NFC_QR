<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Str;

class QrController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string'
        ]);

        $tx = Transaction::create([
            'qrcode_id' => Str::uuid(),
            'amount' => $request->amount,
            'currency' => $request->currency,
            'status' => 'pending',
        ]);

        return response()->json([
            'qrcode_id' => $tx->qrcode_id,
            'amount' => $tx->amount,
            'currency' => $tx->currency,
            'status' => $tx->status
        ]);
    }

    public function status($id)
    {
        $tx = \App\Models\Transaction::where('qrcode_id', $id)->first();

        if (!$tx) {
            return response()->json(['error' => 'QR Code introuvable'], 404);
        }

        return response()->json([
            'qrcode_id' => $tx->qrcode_id,
            'status' => $tx->status,
        ]);
    }
    public function validatePayment(Request $request)
    {
        $request->validate([
            'qrcode_id' => 'required|uuid'
        ]);

        $tx = \App\Models\Transaction::where('qrcode_id', $request->qrcode_id)->first();

        if (!$tx) {
            return response()->json(['error' => 'QR Code non trouvé'], 404);
        }

        if ($tx->status === 'paid') {
            return response()->json(['message' => 'Paiement déjà effectué'], 200);
        }

        $tx->update(['status' => 'paid']);

        return response()->json([
            'message' => 'Paiement validé avec succès',
            'qrcode_id' => $tx->qrcode_id,
            'status' => $tx->status,
            'amount' => $tx->amount,
            'currency' => $tx->currency
        ]);
    }


    public function createNfc(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string'
        ]);

        $nfcToken = strtoupper(Str::random(10)); // Ex: A7F3Z91L2M

        $tx = \App\Models\Transaction::create([
            'qrcode_id' => Str::uuid(),  // pour garder cohérence
            'nfc_token' => $nfcToken,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'status' => 'pending',
        ]);

        return response()->json([
            'nfc_token' => $tx->nfc_token,
            'amount' => $tx->amount,
            'currency' => $tx->currency,
            'status' => $tx->status
        ]);
    }

    public function validateNfc(Request $request)
    {
        $request->validate([
            'nfc_token' => 'required|string'
        ]);

        $tx = \App\Models\Transaction::where('nfc_token', $request->nfc_token)->first();

        if (!$tx) {
            return response()->json(['error' => 'Token NFC invalide'], 404);
        }

        if ($tx->status === 'paid') {
            return response()->json(['message' => 'Déjà payé']);
        }

        $tx->update(['status' => 'paid']);

        return response()->json([
            'message' => 'Paiement par NFC validé',
            'nfc_token' => $tx->nfc_token,
            'status' => $tx->status,
            'amount' => $tx->amount,
            'currency' => $tx->currency
        ]);
    }

}
