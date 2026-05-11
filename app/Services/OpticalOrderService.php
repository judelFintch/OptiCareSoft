<?php

namespace App\Services;

use App\Enums\OpticalOrderStatus;
use App\Models\Frame;
use App\Models\OpticalOrder;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OpticalOrderService
{
    public function createOrder(array $data, User $creator): OpticalOrder
    {
        return DB::transaction(function () use ($data, $creator) {
            $orderNumber = 'OPT-' . now()->format('Ymd') . '-' . str_pad(
                OpticalOrder::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT
            );

            $total    = ($data['price_frame'] ?? 0) + ($data['price_lenses'] ?? 0);
            $deposit  = $data['deposit_paid'] ?? 0;
            $remaining = $total - $deposit;

            $order = OpticalOrder::create(array_merge($data, [
                'order_number'     => $orderNumber,
                'total_price'      => $total,
                'remaining_amount' => $remaining,
                'status'           => OpticalOrderStatus::Pending,
                'created_by'       => $creator->id,
            ]));

            if (isset($data['frame_id']) && $data['frame_id']) {
                Frame::where('id', $data['frame_id'])->decrement('stock_quantity');
            }

            return $order;
        });
    }

    public function addDeposit(OpticalOrder $order, float $amount): OpticalOrder
    {
        $newDeposit   = $order->deposit_paid + $amount;
        $newRemaining = max(0, $order->total_price - $newDeposit);

        $order->update([
            'deposit_paid'     => $newDeposit,
            'remaining_amount' => $newRemaining,
        ]);

        return $order->fresh();
    }

    public function deliver(OpticalOrder $order): OpticalOrder
    {
        $order->update([
            'status'        => OpticalOrderStatus::Delivered,
            'delivery_date' => now(),
        ]);

        return $order->fresh();
    }
}
