<div class="p-6">
    @if($selected)
        <div class="flex items-start">
            <div class="flex-shrink-0 mr-4">
                @if ($selected->type === 'borrow_request')
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-hand-holding text-blue-500 text-lg"></i>
                    </div>
                @elseif($selected->type === 'return_request')
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-undo text-green-500 text-lg"></i>
                    </div>
                @else
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-info-circle text-indigo-500 text-lg"></i>
                    </div>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $selected->title }}</h2>
                <div class="flex items-center mt-1 text-sm text-gray-500">
                    <span>{{ $selected->created_at->format('d M Y, H:i') }}</span>
                    @if ($selected->notifiable)
                        <span class="mx-2">•</span>
                        <span>Oleh: {{ $selected->notifiable->name }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white p-4 rounded-lg shadow-xs">
            @if ($selected->type === 'borrow_request' && $selected->borrowRequest)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1 text-sm font-medium {{ $selected->borrowRequest->status === 'approved' ? 'text-green-600' : ($selected->borrowRequest->status === 'rejected' ? 'text-red-600' : 'text-blue-600') }}">
                            {{ ucfirst($selected->borrowRequest->status) }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Tanggal Pinjam</h3>
                            <p class="mt-1 text-sm">{{ $selected->borrowRequest->borrow_date_expected }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Tanggal Kembali</h3>
                            <p class="mt-1 text-sm">{{ $selected->borrowRequest->return_date_expected }}</p>
                        </div>
                    </div>

                    @if ($selected->borrowRequest->notes)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Catatan</h3>
                            <p class="mt-1 text-sm text-gray-700">{{ $selected->borrowRequest->notes }}</p>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Barang Dipinjam</h3>
                        <div class="mt-2 space-y-3">
                            @foreach ($selected->borrowRequest->borrowDetail as $detail)
                                <div class="flex items-start p-2 border border-gray-100 rounded-lg">
                                    <img src="{{ asset($detail->itemUnit->item->image_url) }}"
                                        alt="{{ $detail->itemUnit->item->name }}" class="w-12 h-12 object-cover rounded-md">
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium">{{ $detail->itemUnit->item->name }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">SKU: {{ $detail->itemUnit->sku }}</p>
                                        <p class="text-xs text-gray-500">Qty: {{ $detail->quantity }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($selected->borrowRequest->status === 'pending')
                <div class="flex justify-end space-x-3 pt-4 mt-6 border-t border-gray-100">
                    <button wire:click="rejectBorrowRequest({{ $selected->borrowRequest->id }})" class="px-4 py-2 border border-red-500 text-red-500 rounded-md hover:bg-red-50 transition-colors">
                        Tolak
                    </button>
                    <button wire:click="approveBorrowRequest({{ $selected->borrowRequest->id }})" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                        Setujui
                    </button>
                </div>
                @endif

            @elseif($selected->type === 'return_request' && $selected->returnRequest)
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1 text-sm font-medium {{ $selected->returnRequest->status === 'approved' ? 'text-green-600' : ($selected->returnRequest->status === 'rejected' ? 'text-red-600' : 'text-blue-600') }}">
                            {{ ucfirst($selected->returnRequest->status) }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Tanggal Dikembalikan</h3>
                        <p class="mt-1 text-sm">{{ $selected->returnRequest->returned_at }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Barang Dikembalikan</h3>
                        <div class="mt-2 space-y-3">
                            @foreach ($selected->returnRequest->returnDetails as $detail)
                                <div class="p-2 border border-gray-100 rounded-lg">
                                    <div class="flex items-start">
                                        <img src="{{ asset($detail->borrowDetail->itemUnit->item->image_url) }}"
                                            alt="{{ $detail->borrowDetail->itemUnit->item->name }}"
                                            class="w-12 h-12 object-cover rounded-md">
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium">
                                                {{ $detail->borrowDetail->itemUnit->item->name }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">Unit:
                                                {{ $detail->borrowDetail->itemUnit->name }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <span class="text-gray-500">Qty:</span>
                                            <span class="font-medium ml-1">{{ $detail->quantity }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Kondisi:</span>
                                            <span class="font-medium ml-1 capitalize">{{ $detail->condition }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($selected->returnRequest->status === 'pending')
                <div class="flex justify-end space-x-3 pt-4 mt-6 border-t border-gray-100">
                    <button wire:click="rejectReturnRequest({{ $selected->returnRequest->id }})" class="px-4 py-2 border border-red-500 text-red-500 rounded-md hover:bg-red-50 transition-colors">
                        Tolak
                    </button>
                    <button wire:click="approveReturnRequest({{ $selected->returnRequest->id }})" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                        Setujui
                    </button>
                </div>
                @endif

            @else
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($selected->body)) !!}
                </div>
            @endif
        </div>
    @else
        <!-- Modern Empty State -->
        <div class="flex flex-col items-center justify-center h-full py-12 px-4 text-center">
            <div class="mb-6 p-4 bg-gray-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada notifikasi dipilih</h3>
            <p class="text-gray-500 max-w-md">Pilih notifikasi dari daftar di sebelah kiri untuk melihat detail lengkap.</p>
        </div>
    @endif
</div>
