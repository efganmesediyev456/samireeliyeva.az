@extends('backend.layouts.layout')

@section('content')
    <div class="container">
        <!-- Order Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Sifariş #{{ $order->order_number }} - Ətraflı məlumat</h4>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Sifarişlər siyahısına qayıt
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Customer Information Column -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Müştəri məlumatları</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Ad Soyad:</th>
                                <td>{{ $order->user?->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $order->user?->email }}</td>
                            </tr>
                            <tr>
                                <th>Telefon:</th>
                                <td>{{ $order->user?->phone }}</td>
                            </tr>
                            <tr>
                                <th>Sifariş tarixi:</th>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    {{ $order->status }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Delivery Information Column -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Məhsul məlumatları</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Kateqoriya növü:</th>
                                <td>{{ $order->subCategory?->title }}</td>
                            </tr>
                            <tr>
                                <th>Bitmə tarixi:</th>
                                <td>{{ $order->expires_at?->format("Y-m-d H:i:s") }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

      
    </div>
@endsection

