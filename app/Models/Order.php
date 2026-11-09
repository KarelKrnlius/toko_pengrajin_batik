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
        'shipped_at',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
        'cancellation_reason',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'total_price'   => 'integer',
            'shipping_cost' => 'integer',
            'shipped_at'    => 'datetime',
            'cancelled_at'  => 'datetime',
        ];
    }

    /**
     * Durasi batas waktu konfirmasi otomatis (Ambil Mandiri: 12 hari, Kurir: 21 hari).
     */
    public function getDeadlineDaysAttribute(): int
    {
        return $this->shipping_courier === 'Ambil Mandiri' ? 12 : 21;
    }

    /**
     * Tanggal & jam batas akhir sebelum pesanan otomatis diselesaikan sistem.
     */
    public function getAutoCompleteDeadlineAttribute(): ?\Carbon\Carbon
    {
        $baseDate = $this->shipped_at ?? $this->created_at;
        return $baseDate ? $baseDate->copy()->addDays($this->deadline_days) : null;
    }

    /**
     * Sisa hari sebelum pesanan otomatis selesai.
     */
    public function getRemainingDaysAttribute(): int
    {
        $deadline = $this->auto_complete_deadline;
        if (! $deadline) {
            return 0;
        }

        $diff = (int) ceil(now()->floatDiffInDays($deadline, false));
        return max(0, $diff);
    }

    /**
     * Cek apakah pesanan telah melewati batas waktu otomatis selesai.
     */
    public function isPastDeadline(): bool
    {
        $deadline = $this->auto_complete_deadline;
        return $deadline ? now()->greaterThanOrEqualTo($deadline) : false;
    }

    /**
     * Eksekusi otomatis pesanan diterima jika telah melewati batas waktu (12 hari / 21 hari).
     */
    public static function autoCompleteExpiredOrders(): int
    {
        // 1. Lengkapi shipped_at jika ada pesanan shipped yang belum memiliki tanggal
        self::where('status', 'shipped')
            ->whereNull('shipped_at')
            ->update(['shipped_at' => \Illuminate\Support\Facades\DB::raw('COALESCE(updated_at, created_at)')]);

        // 2. Ambil semua pesanan shipped dan cek expired
        $expiredOrders = self::where('status', 'shipped')
            ->whereNotNull('shipped_at')
            ->get()
            ->filter(fn (Order $order) => $order->isPastDeadline());

        $count = 0;
        foreach ($expiredOrders as $order) {
            $order->update(['status' => 'completed']);
            $count++;
        }

        return $count;
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
            'shipped'   => $this->shipping_courier === 'Ambil Mandiri' ? 'Siap Diambil' : 'Dikirim',
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
            . "🔖 *No. Pesanan:* {$this->order_number}\n"
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
