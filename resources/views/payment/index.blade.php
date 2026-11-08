<x-master-layout>
    <div class="container-fluid">
        <div class="card card-block card-stretch">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="font-weight-bold mb-0">{{ $pageTitle ?? 'پرداخت‌ها' }}</h5>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="reset-filters">پاک کردن فیلترها</button>
                </div>
                <div class="row mt-3">
                    <div class="col-md-2 mb-2"><label>از تاریخ</label><input type="date" id="date-from" class="form-control"></div>
                    <div class="col-md-2 mb-2"><label>تا تاریخ</label><input type="date" id="date-to" class="form-control"></div>
                    <div class="col-md-2 mb-2">
                        <label>وضعیت</label>
                        <select id="payment-status" class="form-control">
                            <option value="">همه</option><option value="initiated">ایجاد شده</option>
                            <option value="pending">در انتظار پرداخت</option><option value="paid">پرداخت موفق</option>
                            <option value="failed">ناموفق</option><option value="cancelled">لغو شده</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2"><label>مشتری</label><input type="text" id="customer-filter" class="form-control" placeholder="نام، موبایل یا ایمیل"></div>
                    <div class="col-md-2 mb-2"><label>متخصص</label><input type="text" id="handyman-filter" class="form-control" placeholder="نام، موبایل یا ایمیل"></div>
                    <div class="col-md-2 mb-2"><label>نمایندگی</label><input type="text" id="provider-filter" class="form-control" placeholder="نام، موبایل یا ایمیل"></div>
                </div>
            </div>
        </div>
        <div class="card"><div class="card-body">
            <div class="d-flex justify-content-end mb-3"><div class="input-group" style="max-width:420px">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control dt-search" placeholder="شناسه، رزرو، Authority یا کد پیگیری">
            </div></div>
            <div class="table-responsive"><table id="datatable" class="table table-striped border w-100"></table></div>
        </div></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('#datatable').DataTable({
                processing: true, serverSide: true, autoWidth: false, responsive: true, searching: false,
                dom: '<"table-responsive my-3"rt><"row align-items-center"<"col-md-6"l><"col-md-6"p>><"clear">',
                ajax: { type: 'GET', url: '{{ route("payment.index_data") }}', data: function (data) {
                    data.query = $('.dt-search').val();
                    data.filter = { date_from: $('#date-from').val(), date_to: $('#date-to').val(),
                        status: $('#payment-status').val(), customer: $('#customer-filter').val(),
                        handyman: $('#handyman-filter').val(), provider: $('#provider-filter').val() };
                }},
                columns: [
                    {data:'id',name:'id',title:'پرداخت'}, {data:'booking_id',name:'booking_id',title:'رزرو',orderable:false},
                    {data:'customer_id',name:'customer_id',title:'مشتری',orderable:false},
                    {data:'provider_name',name:'provider_name',title:'نمایندگی',orderable:false},
                    {data:'handyman_name',name:'handyman_name',title:'متخصص',orderable:false},
                    {data:'payment_status',name:'payment_status',title:'وضعیت'},
                    {data:'reference_id',name:'reference_id',title:'کد پیگیری',defaultContent:'-'},
                    {data:'datetime',name:'datetime',title:'تاریخ'}, {data:'total_amount',name:'total_amount',title:'مبلغ'}
                ]
            });
            let timer;
            $('.dt-search,#customer-filter,#handyman-filter,#provider-filter').on('input', function () {
                clearTimeout(timer); timer = setTimeout(function () { table.ajax.reload(); }, 400);
            });
            $('#date-from,#date-to,#payment-status').on('change', function () { table.ajax.reload(); });
            $('#reset-filters').on('click', function () {
                $('#date-from,#date-to,#payment-status,#customer-filter,#handyman-filter,#provider-filter,.dt-search').val('');
                table.ajax.reload();
            });
        });
    </script>
</x-master-layout>
