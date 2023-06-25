@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="bg-shadow">

            <table id="order_datatable" class="table table-bordered table-fs-sm table-striped table-responsive" style="width:100%;">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Full name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($orders as $order)

                    <tr class="fw-semibold">
                        <td class="align-middle">{{ ++$loop->index }}</td>
                        <td class="align-middle">{{ $order->full_name }}</td>
                        <td class="align-middle">{{ $order->address }}</td>
                        <td class="align-middle">{{ \App\Helpers\Helper::phone_format($order->phone) }}</td>
                        <td class="align-middle">{{ date('d.m.Y H:i', strtotime($order->created_at)) }}</td>
                        <td class="align-middle">
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
            <div class="paginate-div">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script>

        // every 10 minutes page refresh
        let setTime = setTimeout(function(){
            window.location.reload();
        }, 600000);

        modal.on('show.bs.modal', function() {
            clearTimeout(setTime);
        });
        modal.on('hidden.bs.modal', function() {
            setTime = setTimeout(function(){
                window.location.reload();
            }, 600000);
        });

    </script>
@endsection
