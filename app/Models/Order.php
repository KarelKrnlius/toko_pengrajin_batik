<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_price',
        'payment_method',
        'shipping_courier',
        'shipping_cost',
        'tracking_number',
        'status',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_price'   => 'integer',
            'shipping_cost' => 'integer',
        ];
    }

    /**
     * Relasi ke Pelanggan / User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Item Pesanan
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Total item / kuantitas produk dalam pesanan
     */
    public function getTotalQuantityAttribute(): int
    {
        return (int) $this->items->sum('quantity');
    }

    /**
     * Subtotal produk tanpa ongkir
     */
    public function getSubtotalPriceAttribute(): int
    {
        return max(0, $this->total_price - $this->shipping_cost);
    }

    /**
     * Label status berbahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu',
            'shipped'   => 'Dikirim',
            'completed' => 'Selesai',
            default     => ucfirst($this->status),
        };
    }

    /**
     * URL WhatsApp untuk Hybrid / Semi-Otomatis Konfirmasi Pesanan
     */
    public function getWhatsAppUrlAttribute(): ?string
    {
        $rawPhone = $this->customer_phone ?: $this->user?->phone;
        if (! $rawPhone) {
            return null;
        }

        // Normalisasi nomor telepon ke format internasional 62
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $customerName = $this->customer_name ?: ($this->user?->name ?? 'Pelanggan');
        $itemsList = '';
        foreach ($this->items as $item) {
            $productName = $item->product?->name ?? 'Produk Batik';
            $itemsList .= "\n- {$productName} (x{$item->quantity}) : Rp " . number_format($item->price * $item->quantity, 0, ',', '.');
        }

        $resiText = $this->tracking_number ? "\n📦 *No. Resi:* {$this->tracking_number}" : '';

        $message = "Halo kak *{$customerName}*,\n\n"
            . "Terima kasih telah berbelanja di *Juragan Batik Surakarta*.\n"
            . "Berikut rincian pesanan Anda:\n"
            . "🔖 *No. Pesanan:* #{$this->order_number}\n"
            . "📋 *Daftar Produk:*"
            . $itemsList . "\n\n"
            . "🚚 *Kurir:* {$this->shipping_courier}\n"
            . "📍 *Alamat:* {$this->customer_address}"
            . $resiText . "\n"
            . "💳 *Metode Bayar:* " . strtoupper(str_replace('_', ' ', $this->payment_method)) . "\n"
            . "💰 *Ongkir:* Rp " . number_format($this->shipping_cost, 0, ',', '.') . "\n"
            . "🏷️ *Total Bayar:* Rp " . number_format($this->total_price, 0, ',', '.') . "\n"
            . "⚡ *Status:* " . strtoupper($this->status_label) . "\n\n"
            . "Apakah ada hal yang ingin ditanyakan terkait pesanan ini? 🙏";

        return 'https://api.whatsapp.com/send?phone=' . $cleanPhone . '&text=' . rawurlencode($message);
    }
}
