<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'tracking_number',
        'carrier',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip_code',
        'country',
        'mobile_number',
        'status',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the order that owns the shipment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address_line1;
        if ($this->address_line2) {
            $address .= ', ' . $this->address_line2;
        }
        $address .= ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip_code . ', ' . $this->country;
        return $address;
    }

    /**
     * Check if shipment is delivered
     */
    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if shipment is in transit
     */
    public function isInTransit()
    {
        return $this->status === 'in_transit';
    }

    /**
     * Scope for shipments by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}