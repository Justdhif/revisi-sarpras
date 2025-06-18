<div class="p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">
                {{ $selected->notification_type == 'request_peminjaman' ? 'Permintaan Peminjaman' : 'Permintaan Pengembalian' }}
            </h2>
            <div class="flex items-center">
                <span
                    class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full
                    @if ($selected->notification_type == 'request_peminjaman') bg-blue-100 text-blue-800
                    @else bg-green-100 text-green-800 @endif">
                    {{ $selected->notification_type == 'request_peminjaman' ? 'Peminjaman' : 'Pengembalian' }}
                </span>
                <span class="text-xs text-gray-500 ml-2">{{ $selected->created_at->format('d M Y H:i') }}</span>
            </div>
        </div>
        @if (!$selected->is_read)
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                Belum dibaca
            </span>
        @endif
    </div>

    <div class="prose prose-indigo max-w-none mb-8 p-4 bg-gray-50 rounded-lg">
        <p class="text-gray-700">{{ $selected->message }}</p>
    </div>

    @if ($selected->notification_type === 'request_peminjaman' && $selected->borrowRequest)
        <div class="border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-boxes mr-2 text-indigo-500"></i> Detail Peminjaman
            </h3>
            <div class="grid gap-3">
                @foreach ($selected->borrowRequest->borrowDetail as $detail)
                    <div class="flex items-start gap-4 p-3 rounded-lg bg-white border border-gray-200">
                        @if ($detail->itemUnit->item->image_url ?? false)
                            <div class="flex-shrink-0 h-12 w-12 rounded-md bg-gray-100 overflow-hidden">
                                <img src="{{ asset($detail->itemUnit->item->image_url) }}" alt=""
                                    class="h-full w-full object-cover">
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900">
                                {{ $detail->itemUnit->item->name ?? 'Barang tidak tersedia' }}
                            </h4>
                            <div class="mt-1 flex flex-wrap gap-1.5">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-800">
                                    <i class="fas fa-barcode mr-1 text-xs"></i> {{ $detail->itemUnit->sku }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-800">
                                    <i class="fas fa-layer-group mr-1 text-xs"></i> {{ $detail->quantity }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif ($selected->notification_type === 'request_pengembalian' && $selected->returnRequest)
        <div class="border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-undo-alt mr-2 text-indigo-500"></i> Detail Pengembalian
            </h3>
            <div class="grid gap-3">
                @foreach ($selected->returnRequest->details as $detail)
                    <div class="flex items-start gap-4 p-3 rounded-lg bg-white border border-gray-200">
                        @if ($detail->itemUnit->item->image_url ?? false)
                            <div class="flex-shrink-0 h-12 w-12 rounded-md bg-gray-100 overflow-hidden">
                                <img src="{{ asset($detail->itemUnit->item->image_url) }}" alt=""
                                    class="h-full w-full object-cover">
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900">
                                {{ $detail->itemUnit->item->name ?? 'Barang tidak tersedia' }}
                            </h4>
                            <div class="mt-1 flex flex-wrap gap-1.5">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-800">
                                    <i class="fas fa-barcode mr-1 text-xs"></i> {{ $detail->item_unit_id }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-800">
                                    <i class="fas fa-layer-group mr-1 text-xs"></i> {{ $detail->quantity }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
