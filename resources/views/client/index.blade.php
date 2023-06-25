@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="bg-shadow">

            <table id="order_datatable" class="table table-bordered table-fs-sm table-striped table-responsive" style="width:100%;">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Chat Id</th>
                    <th>Full name</th>
                    <th>Username</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($clients as $client)

                    <tr class="fw-semibold">
                        <td class="align-middle">{{ ++$loop->index }}</td>
                        <td class="align-middle">{{ $client->chat_id }}</td>
                        <td class="align-middle">{{ $client->full_name }}</td>
                        <td class="align-middle">{{ $client->username }}</td>
                        <td class="align-middle">{{ date('d.m.Y H:i', strtotime($client->created_at)) }}</td>
                        <td class="align-middle">
                            <button class="btn btn-secondary btn-sm">action</button>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
            <div class="paginate-div">
                {{ $clients->links() }}
            </div>
        </div>
    </div>

@endsection


