@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-6">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-light tracking-wide text-gray-800">Our Product Collection</h1>
            <a href="{{ route('cart.index') }}"
                class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                View Cart ({{ $totalCarts }})
            </a>
        </div>

        @if (session('success'))
            <div class="mb-8 p-4 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($items as $unit)
                <div
                    class="group relative bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-medium text-gray-900 mb-1">{{ $unit->item->name }}</h2>
                                <p class="text-xs text-gray-500 tracking-wider uppercase">SKU: {{ $unit->sku }}</p>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                In Stock
                            </span>
                        </div>

                        <form method="POST" action="{{ route('cart.store') }}" class="mt-6">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $unit->item->id }}">
                            <input type="hidden" name="item_unit_id" value="{{ $unit->id }}">

                            <div class="flex items-center">
                                <div class="relative">
                                    <input type="number" name="quantity" value="1" min="1"
                                        class="appearance-none border border-gray-300 rounded-lg w-20 px-4 py-2 text-center focus:outline-none focus:ring-1 focus:ring-gray-400">
                                    <div
                                        class="absolute right-0 top-0 bottom-0 flex flex-col justify-center items-center border-l border-gray-300 w-6">
                                        <button type="button"
                                            onclick="this.parentNode.parentNode.querySelector('input[type=number]').stepUp()"
                                            class="h-1/2 flex items-center justify-center w-full text-gray-500 hover:text-gray-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </button>
                                        <button type="button"
                                            onclick="this.parentNode.parentNode.querySelector('input[type=number]').stepDown()"
                                            class="h-1/2 flex items-center justify-center w-full text-gray-500 hover:text-gray-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="ml-3 flex-1 bg-gray-900 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add to Cart
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
