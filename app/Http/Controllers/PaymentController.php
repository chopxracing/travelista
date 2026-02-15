<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use YooKassa\Common\Exceptions\ApiConnectionException;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;

class PaymentController extends Controller
{

    public function create(Request $request, PaymentService $service)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id'
        ]);

        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($booking->is_paid) {
            return response()->json([
                'message' => 'Бронирование уже оплачено'
            ], 400);
        }

        $amount = $booking->payment->amount;

        $transaction = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $amount,
            'description' => "Оплата бронирования №{$booking->id}",
        ]);

        $url = $service->createPayment($amount, $transaction->description, [
            'transaction_id' => $transaction->id,
            'booking_id' => $booking->id,
        ]);

        return response()->json([
            'url' => $url
        ]);
    }

    public function callback(Request $request)
    {
        $data = $request->all();

        if (!isset($data['object']['status'])) {
            return response()->json(['status' => 'ignored']);
        }

        $paymentObject = $data['object'];

        if ($paymentObject['status'] !== 'succeeded') {
            return response()->json(['status' => 'waiting']);
        }

        $metadata = $paymentObject['metadata'] ?? null;

        if (!$metadata || !isset($metadata['transaction_id'])) {
            return response()->json(['status' => 'no metadata']);
        }

        $transaction = Payment::find($metadata['transaction_id']);

        if (!$transaction) {
            return response()->json(['status' => 'not found']);
        }

        if ($transaction->paid_at) {
            return response()->json(['status' => 'already processed']);
        }

        $transaction->update([
            'paid_at' => now(),
            'yookassa_id' => $paymentObject['id'],
        ]);

        $transaction->booking->update([
            'is_paid' => 1,
        ]);

        return response()->json(['status' => 'success']);
    }
}
