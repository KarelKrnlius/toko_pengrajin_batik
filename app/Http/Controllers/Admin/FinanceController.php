<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceController extends Controller
{
    /**
     * Tampilkan halaman Manajemen Keuangan & Rekap Penjualan
     */
    public function index(Request $request)
    {
        // Jalankan pengecekan auto-complete pesanan yang lewat batas waktu
        Order::autoCompleteExpiredOrders();

        // Filter tanggal
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $status    = $request->input('finance_status', 'all'); // all, masuk, pending, cancelled
        $search    = $request->input('search');

        // Query dasar
        $query = Order::with(['user', 'items.product.category'])->latest('created_at');

        // Filter rentang tanggal
        if ($startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        // Filter status keuangan
        if ($status === 'masuk') {
            $query->where('status', 'completed');
        } elseif ($status === 'pending') {
            $query->whereIn('status', ['pending', 'shipped']);
        } elseif ($status === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        // Search nomor order / pelanggan / motif
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'ilike', "%{$search}%")
                  ->orWhere('customer_name', 'ilike', "%{$search}%")
                  ->orWhere('customer_phone', 'ilike', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'ilike', "%{$search}%")
                         ->orWhere('phone', 'ilike', "%{$search}%");
                  })
                  ->orWhereHas('items.product', function ($pq) use ($search) {
                      $pq->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        // Ambil data untuk tabel (10 item per halaman agar web ringan)
        $orders = $query->paginate(10)->withQueryString();

        // Hitung statistik keseluruhan (mengikuti filter tanggal jika ada)
        $statsQuery = Order::with('items');
        if ($startDate) {
            $statsQuery->whereDate('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }
        if ($endDate) {
            $statsQuery->whereDate('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $allFilteredOrders = $statsQuery->get();

        // Pendapatan Masuk: pesanan yang statusnya completed
        $totalPendapatanMasuk = $allFilteredOrders->where('status', 'completed')->sum('total_price');
        $countPendapatanMasuk = $allFilteredOrders->where('status', 'completed')->count();

        // Pending: pesanan yang belum selesai (pending / shipped)
        $totalPendapatanPending = $allFilteredOrders->whereIn('status', ['pending', 'shipped'])->sum('total_price');
        $countPendapatanPending = $allFilteredOrders->whereIn('status', ['pending', 'shipped'])->count();

        // Transaksi Dibatalkan
        $totalDibatalkan = $allFilteredOrders->where('status', 'cancelled')->sum('total_price');
        $countDibatalkan = $allFilteredOrders->where('status', 'cancelled')->count();

        // Total pcs kain batik terjual (dari pesanan completed)
        $totalPcsTerjual = $allFilteredOrders->where('status', 'completed')
            ->flatMap(fn($o) => $o->items)
            ->sum('quantity');

        return view('admin.finance.index', compact(
            'orders',
            'totalPendapatanMasuk',
            'countPendapatanMasuk',
            'totalPendapatanPending',
            'countPendapatanPending',
            'totalDibatalkan',
            'countDibatalkan',
            'totalPcsTerjual',
            'startDate',
            'endDate',
            'status',
            'search'
        ));
    }

    /**
     * Ekspor Rekap Keuangan & Penjualan Barang ke format Excel (.xls HTML table)
     * Langsung terbuka di Microsoft Excel dengan garis tabel, warna header, dan sel merge rapi.
     */
    public function export(Request $request): StreamedResponse
    {
        Order::autoCompleteExpiredOrders();

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $status    = $request->input('finance_status', 'all');
        $search    = $request->input('search');

        // Query mengambil SELURUH data sesuai filter (tanpa pagination)
        $query = Order::with(['user', 'items.product.category'])->orderBy('created_at', 'desc');

        if ($startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }
        if ($status === 'masuk') {
            $query->where('status', 'completed');
        } elseif ($status === 'pending') {
            $query->whereIn('status', ['pending', 'shipped']);
        } elseif ($status === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'ilike', "%{$search}%")
                  ->orWhere('customer_name', 'ilike', "%{$search}%")
                  ->orWhere('customer_phone', 'ilike', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'ilike', "%{$search}%");
                  })
                  ->orWhereHas('items.product', function ($pq) use ($search) {
                      $pq->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        $orders = $query->get();

        // Gunakan timestamp dari perangkat klien jika ada, fallback ke waktu server
        $clientTime = $request->input('client_time');
        if ($clientTime && preg_match('/^\d{8}_\d{6}$/', $clientTime)) {
            $filenameTime = $clientTime;
        } else {
            $filenameTime = date('Ymd_His');
        }

        $filename = 'Laporan_Keuangan_JuraganBatik_' . $filenameTime . '.xls';
        $downloadTime = $request->input('download_time', date('d/m/Y H:i:s'));

        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($orders, $downloadTime) {
            $output = fopen('php://output', 'w');

            // BOM UTF-8
            fwrite($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // HTML Table template yang langsung dirender native oleh Microsoft Excel
            fwrite($output, '<!DOCTYPE html>
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <!--[if gte mso 9]>
                <xml>
                <x:ExcelWorkbook>
                    <x:ExcelWorksheets>
                        <x:ExcelWorksheet>
                            <x:Name>Laporan Keuangan</x:Name>
                            <x:WorksheetOptions>
                                <x:DisplayGridlines/>
                            </x:WorksheetOptions>
                        </x:ExcelWorksheet>
                    </x:ExcelWorksheets>
                </x:ExcelWorkbook>
                </xml>
                <![endif]-->
                <style>
                    table { border-collapse: collapse; font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
                    th { background-color: #311908; color: #ffffff; font-weight: bold; border: 1px solid #000000; padding: 8px 10px; text-align: center; }
                    td { border: 1px solid #d4c3bb; padding: 6px 8px; vertical-align: top; }
                    .center { text-align: center; }
                    .right { text-align: right; }
                    .bold { font-weight: bold; }
                    .bg-header { background-color: #904c2e; color: #ffffff; }
                    .bg-masuk { background-color: #d1fae5; color: #065f46; font-weight: bold; }
                    .bg-pending { background-color: #fef3c7; color: #92400e; font-weight: bold; }
                    .bg-batal { background-color: #fee2e2; color: #991b1b; font-weight: bold; }
                    .total-row { background-color: #f1ede7; font-weight: bold; }
                </style>
            </head>
            <body>');

            fwrite($output, '<h2>JURAGAN BATIK SURAKARTA</h2>');
            fwrite($output, '<h3>REKAP LAPORAN KEUANGAN & RINCIAN PENJUALAN PRODUK</h3>');
            fwrite($output, '<p>Tanggal Download: ' . htmlspecialchars($downloadTime) . '</p><br>');

            fwrite($output, '<table border="1">
                <thead>
                    <tr>
                        <th style="background-color:#311908; color:#fff;">No</th>
                        <th style="background-color:#311908; color:#fff;">Tanggal</th>
                        <th style="background-color:#311908; color:#fff;">No. Pesanan</th>
                        <th style="background-color:#311908; color:#fff;">Nama Pelanggan</th>
                        <th style="background-color:#311908; color:#fff;">No. Telepon</th>
                        <th style="background-color:#311908; color:#fff;">Nama Produk / Motif Batik</th>
                        <th style="background-color:#311908; color:#fff;">Kategori Wastra</th>
                        <th style="background-color:#311908; color:#fff;">Harga Satuan (Rp)</th>
                        <th style="background-color:#311908; color:#fff;">Qty (Pcs)</th>
                        <th style="background-color:#311908; color:#fff;">Subtotal Barang (Rp)</th>
                        <th style="background-color:#311908; color:#fff;">Total Transaksi (Rp)</th>
                        <th style="background-color:#311908; color:#fff;">Metode Pengiriman</th>
                        <th style="background-color:#311908; color:#fff;">Ongkir (Rp)</th>
                        <th style="background-color:#311908; color:#fff;">Status Keuangan</th>
                    </tr>
                </thead>
                <tbody>');

            $no = 1;
            $grandTotalMasuk = 0;
            $grandTotalPending = 0;
            $grandTotalDibatalkan = 0;
            $grandTotalPcs = 0;

            foreach ($orders as $order) {
                $customerName  = htmlspecialchars($order->customer_name ?: ($order->user?->name ?? 'Pelanggan'));
                $customerPhone = htmlspecialchars($order->customer_phone ?: ($order->user?->phone ?? '-'));
                $items = $order->items;
                $rowCount = max(1, $items->count());

                if ($order->status === 'completed') {
                    $statusClass = 'bg-masuk';
                    $statusLabel = 'Pendapatan Masuk (Diterima)';
                    $grandTotalMasuk += $order->total_price;
                } elseif ($order->status === 'cancelled') {
                    $statusClass = 'bg-batal';
                    $statusLabel = 'Dibatalkan';
                    $grandTotalDibatalkan += $order->total_price;
                } else {
                    $statusClass = 'bg-pending';
                    $statusLabel = 'Pending (Belum Diterima)';
                    $grandTotalPending += $order->total_price;
                }

                if ($items->isEmpty()) {
                    fwrite($output, '<tr>
                        <td class="center">' . $no++ . '</td>
                        <td class="center">' . $order->created_at->format('d/m/Y H:i') . '</td>
                        <td class="center bold">' . htmlspecialchars($order->order_number) . '</td>
                        <td>' . $customerName . '</td>
                        <td style="mso-number-format:\'\@\';">' . $customerPhone . '</td>
                        <td colspan="5" class="center">-</td>
                        <td class="right bold">' . number_format($order->total_price, 0, ',', '.') . '</td>
                        <td class="center">' . htmlspecialchars($order->shipping_courier) . '</td>
                        <td class="right">' . number_format($order->shipping_cost, 0, ',', '.') . '</td>
                        <td class="center ' . $statusClass . '">' . $statusLabel . '</td>
                    </tr>');
                } else {
                    foreach ($items as $idx => $item) {
                        $productName = htmlspecialchars($item->product?->name ?? 'Produk Dihapus');
                        $catName     = htmlspecialchars($item->product?->category?->name ?? 'Batik');
                        $subtotal    = $item->price * $item->quantity;

                        if ($order->status === 'completed') {
                            $grandTotalPcs += $item->quantity;
                        }

                        fwrite($output, '<tr>');

                        if ($idx === 0) {
                            fwrite($output, '<td rowspan="' . $rowCount . '" class="center">' . $no++ . '</td>');
                            fwrite($output, '<td rowspan="' . $rowCount . '" class="center">' . $order->created_at->format('d/m/Y H:i') . '</td>');
                            fwrite($output, '<td rowspan="' . $rowCount . '" class="center bold">' . htmlspecialchars($order->order_number) . '</td>');
                            fwrite($output, '<td rowspan="' . $rowCount . '">' . $customerName . '</td>');
                            fwrite($output, '<td rowspan="' . $rowCount . '" style="mso-number-format:\'\@\';">' . $customerPhone . '</td>');
                        }

                        fwrite($output, '<td>' . $productName . '</td>');
                        fwrite($output, '<td class="center">' . $catName . '</td>');
                        fwrite($output, '<td class="right">' . number_format($item->price, 0, ',', '.') . '</td>');
                        fwrite($output, '<td class="center bold">' . $item->quantity . '</td>');
                        fwrite($output, '<td class="right bold">' . number_format($subtotal, 0, ',', '.') . '</td>');

                        if ($idx === 0) {
                            fwrite($output, '<td rowspan="' . $rowCount . '" class="right bold">' . number_format($order->total_price, 0, ',', '.') . '</td>');
                            fwrite($output, '<td rowspan="' . $rowCount . '" class="center">' . htmlspecialchars($order->shipping_courier) . '</td>');
                            fwrite($output, '<td rowspan="' . $rowCount . '" class="right">' . number_format($order->shipping_cost, 0, ',', '.') . '</td>');
                            fwrite($output, '<td rowspan="' . $rowCount . '" class="center ' . $statusClass . '">' . $statusLabel . '</td>');
                        }

                        fwrite($output, '</tr>');
                    }
                }
            }

            // Bagian Bawah Ringkasan Total
            fwrite($output, '<tr><td colspan="14" style="border:none; height:15px;"></td></tr>');
            fwrite($output, '<tr class="total-row">
                <td colspan="10" class="right bold">TOTAL PENDAPATAN MASUK (Selesai/Diterima):</td>
                <td colspan="4" class="left bold" style="background-color:#d1fae5; color:#065f46;">Rp ' . number_format($grandTotalMasuk, 0, ',', '.') . '</td>
            </tr>');
            fwrite($output, '<tr class="total-row">
                <td colspan="10" class="right bold">TOTAL NOMINAL PENDING (Belum Selesai):</td>
                <td colspan="4" class="left bold" style="background-color:#fef3c7; color:#92400e;">Rp ' . number_format($grandTotalPending, 0, ',', '.') . '</td>
            </tr>');
            fwrite($output, '<tr class="total-row">
                <td colspan="10" class="right bold">TOTAL NOMINAL DIBATALKAN:</td>
                <td colspan="4" class="left bold" style="background-color:#fee2e2; color:#991b1b;">Rp ' . number_format($grandTotalDibatalkan, 0, ',', '.') . '</td>
            </tr>');
            fwrite($output, '<tr class="total-row">
                <td colspan="10" class="right bold">TOTAL BATIK TERJUAL (Pesanan Selesai):</td>
                <td colspan="4" class="left bold">' . number_format($grandTotalPcs, 0, ',', '.') . ' pcs</td>
            </tr>');

            fwrite($output, '</tbody></table></body></html>');

            fclose($output);
        }, 200, $headers);
    }
}
