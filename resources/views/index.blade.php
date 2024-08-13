@extends('layouts.app')
@section('title') النموذج @endsection
@section('css')
<style>
    a.card:hover{
        text-decoration: none
    }
</style>
@endsection
@section('content')
    {{-- current shift --}}
    @if($current_shift)
        <div class="card shdow border-0 mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span><b>وقت بدأ الوردية:</b> {{ $current_shift->day }} {{ $current_shift->start_date }} {{formatTime($current_shift->start_time)}} </span>
                    @can('end_shift')
                        <form action="{{route('shifts.update', $current_shift)}}" method="post">
                            @csrf
                            @method('put')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من أنك تريد انهاء الوردية الجارية؟')">انهاء الوردية</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @endif
    
    {{-- boxes --}}
    <div class="row">
        @foreach ($counts as $item)
            <div class="col-md-6 col-xl-3 mb-4">
                <a href="{{route($item['url'])}}" class="card shadow bg-{{$item['color']}} text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                        <div class="col-3 text-center">
                            <span class="circle circle-sm bg-{{$item['color']}}-light">
                            <i class="fe fe-16 fe-{{$item['icon']}} text-white mb-0"></i>
                            </span>
                        </div>
                        <div class="col pr-0">
                            <p class="text-light mb-0">{{$item['title']}}</p>
                            <span class="h2 mb-0 text-white">{{$item['amount']}}</span>
                        </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- products --}}
    <div class="row">
        @can('products_statistics')
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <strong class="card-title mb-0">أكثر المنتجات مبيعاً هذا الشهر</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="top_sold_products" style="width: 100%"></canvas>
                    </div>
                </div>
            </div>
        @endcan
        @can('warehouse_statistics')
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <strong class="card-title mb-0">أكثر المنتجات في المخزن</strong>
                        <a href="{{route('warehouse.index')}}" class="btn btn-primary">عرض جميع المنتجات</a>
                    </div>
                    <div class="card-body py-0">
                        <div class="table-responsive w-100">
                            <table class="table table-stripe text-center w-100" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center border-0">المنتج</th>
                                        <th class="text-center border-0">الرصيد</th>
                                        <th class="text-center border-0">سعر الشراء</th>
                                        <th class="text-center border-0">سعر البيع</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouse_products as $warehouse_product)
                                        <tr>
                                            <td>{{$warehouse_product->product->name}}</td>
                                            <td class="text-{{$warehouse_product->balance < 5 ? 'danger' : 'success'}}">{{$warehouse_product->balance}}</td>
                                            <td>{{$warehouse_product->product->purchasing_price}}</td>
                                            <td>{{$warehouse_product->product->selling_price}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    {{-- invoices --}}
    <div class="row">
        @can('transaction_statistics')
            <div class="col-md-8 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <strong class="card-title mb-0">الايداعات / الصادرات خلال العام الجاي</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="payments_per_month" style="width: 100%"></canvas>
                    </div>
                </div>
            </div>
        @endcan
        <div class="col-md-4 mb-4">
            @can('customers_statistics')
                <div class="card shadow">
                    <div class="card-header">
                        <strong class="card-title mb-0">أهم العملاء هذا الشهر</strong>
                    </div>
                    <div class="card-body py-0">
                        <div class="table-responsive w-100">
                            <table class="table table-stripe text-center w-100" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center border-0">العميل</th>
                                        <th class="text-center border-0">الفواتير</th>
                                        <th class="text-center border-0">المدفوعات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_customers as $top_customer)
                                        <?php $customer = \App\Models\Customer::find($top_customer->customer_id); ?>
                                        <tr>
                                            <td>{{$customer->name}}</td>
                                            <td>{{$top_customer->count}}</td>
                                            <td>{{$top_customer->sum}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endcan
            @can('invoices_statistics')
                <div class="card shadow mt-4">
                    <div class="card-header">
                        <strong class="card-title mb-0">الفواتير في هذا الشهر</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="invoices_count_this_month" style="width: 100%"></canvas>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection

@section('js')
<script src="{{asset('assets/js/chart2.js')}}"></script>

<script>
    // top sold products
    const ctx = document.getElementById('top_sold_products');
    const topProducts = @json($top_sold);

    const topProductslabels = topProducts.map(product => product.name);
    const topProductsdata = topProducts.map(product => product.total_quantity);

    new Chart(ctx, {
    type: 'bar',
    data: {
        labels: topProductslabels,
        datasets: [{
            backgroundColor: [
                "rgba(0,255,0,1.0)",
                "rgba(0,255,0,0.8)",
                "rgba(0,255,0,0.6)",
                "rgba(0,255,0,0.4)",
                "rgba(0,255,0,0.2)"
            ],
            label: '# للوحدة',
            data: topProductsdata,
            borderWidth: 1,
            hoverBorderWidth: 2,
        }]
    }
    });
</script>

<script>
    // payments sum per month
    const payments_per_month_canvas = document.getElementById('payments_per_month');
    const payments_per_month = @json($payment_sum_per_month);
    
    const month_labels = [
    'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
    'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
    ];
    

    new Chart(payments_per_month_canvas, {
        type: 'line',
        data: {
            labels: month_labels,
            datasets: [
                {
                    label: 'الواردات',
                    data: payments_per_month[0],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'الصادرات',
                    data: payments_per_month[1],
                    fill: false,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                },
            ]
        }
    });
</script>

<script>
    // payments sum per month
    const invoices_count_this_month_canvas = document.getElementById('invoices_count_this_month');
    const invoices_count_this_month = @json($invoices_count_this_month);
    
    new Chart(invoices_count_this_month_canvas, {
        type: 'pie',
        data: {
            labels: ['المبيعات', 'المشتريات', 'السندات'],
            datasets: [{
                label: 'الفواتير',
                data: invoices_count_this_month,
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(75, 192, 192)',
                'rgb(255, 205, 86)',
                ]
            }]
        }
    });
</script>
@endsection