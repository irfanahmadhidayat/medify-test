@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('master-items')}}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">
                <div class="card-header">Master Item</div>

                <div class="card-body">
                    <table>
                        <tr>
                            <th>Foto</th>
                            <td>:</td>
                            <td>
                                @if($data->foto != '')
                                <img src="{{asset('storage/'.$data->foto)}}" width="150" height="150" style="object-fit:cover; border:1px solid #ddd; border-radius:4px;">
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{$data->nama}}</td>
                        </tr>
                        <tr>
                            <th>Harga Beli</th>
                            <td>:</td>
                            <td>{{$data->harga_beli}}</td>
                        </tr>
                        <tr>
                            <th>Laba</th>
                            <td>:</td>
                            <td>{{$data->laba}}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>:</td>
                            <td>{{$data->harga_beli + $data->harga_beli * $data->laba / 100 }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>:</td>
                            <td>{{$data->supplier}}</td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td>:</td>
                            <td>{{$data->jenis}}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>:</td>
                            <td>
                                @forelse($data->kategoriItems as $kategori)
                                <a href="{{url('kategori-items/view')}}/{{$kategori->kode}}">{{$kategori->nama}}</a>@if(!$loop->last), @endif
                                @empty
                                -
                                @endforelse
                            </td>
                        </tr>
                    </table>
                    <a class="btn btn-info" href="{{url('master-items/form/edit')}}/{{$data->id}}">Edit</a>
                    <form method="POST" action="{{url('master-items/delete')}}/{{$data->id}}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
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