@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('kategori-items')}}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            </div>
            <div class="card">
                <div class="card-header">Kategori Item</div>

                <div class="card-body">
                    <table>
                        <tr>
                            <th>Kode</th>
                            <td>:</td>
                            <td>{{$data->kode}}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{$data->nama}}</td>
                        </tr>
                    </table>

                    <h5 class="mt-3">Items dengan Kategori Ini</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->masterItems as $item)
                            <tr>
                                <td>{{$item->kode}}</td>
                                <td>{{$item->nama}}</td>
                                <td><a class="btn btn-primary" href="{{url('master-items/view')}}/{{$item->kode}}">View</a></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">Tidak ada item pada kategori ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <a class="btn btn-info" href="{{url('kategori-items/form/edit')}}/{{$data->id}}">Edit</a>
                    <a class="btn btn-success" href="{{url('kategori-items/print')}}/{{$data->kode}}">Download PDF</a>
                    <form method="POST" action="{{url('kategori-items/delete')}}/{{$data->id}}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection