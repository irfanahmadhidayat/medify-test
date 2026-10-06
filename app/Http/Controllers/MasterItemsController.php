<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\KategoriItem;
use App\Exports\MasterItemsExport;
use App\Http\Requests\MasterItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $orderDir = $request->input('order.0.dir', 'asc');

        $query = MasterItem::query()->with('kategoriItems');

        if (!empty($request->kode)) {
            $query->where('kode', $request->kode);
        }
        if (!empty($request->nama)) {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }
        if ($request->filled('hargamin')) {
            $query->where('harga_beli', '>=', (int) $request->hargamin);
        }
        if ($request->filled('hargamax')) {
            $query->where('harga_beli', '<=', (int) $request->hargamax);
        }

        $recordsTotal = MasterItem::count();
        $recordsFiltered = (clone $query)->count();

        $sortColumns = [
            1 => 'kode',
            2 => 'nama',
            3 => 'jenis',
            4 => 'harga_beli',
            6 => 'supplier',
        ];
        $orderColumn = (int) $request->input('order.0.column', 1);
        $query->orderBy($sortColumns[$orderColumn] ?? 'id', $orderDir === 'desc' ? 'desc' : 'asc');

        $items = $query->offset($start)->limit($length)->get();

        $data = $items->map(function ($item) {
            $hargaJual = (int) round($item->harga_beli + $item->harga_beli * $item->laba / 100);

            $fotoHtml = '-';
            if ($item->foto) {
                $fotoHtml = '<img src="' . e(asset('storage/' . $item->foto)) . '" width="60" height="60" style="object-fit:cover; border:1px solid #ddd; border-radius:4px;">';
            }

            $kategori = $item->kategoriItems->pluck('nama')->implode(', ');

            $view = '<a href="' . e(url('master-items/view/' . $item->kode)) . '" class="btn btn-primary">View</a>';

            return [
                $fotoHtml,
                e($item->kode),
                e($item->nama),
                e($item->jenis),
                $item->harga_beli,
                $hargaJual,
                e($item->supplier),
                e($kategori),
                $view,
            ];
        })->toArray();

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = null;
        } else {
$item = MasterItem::with('kategoriItems')->find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['semuaKategori'] = KategoriItem::all();
        $data['kategoriTerpilih'] = ($method != 'new' && $item != null) ? $item->kategoriItems->pluck('id')->toArray() : [];
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->with('kategoriItems')->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(MasterItemRequest $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            if ($data_item == null) {
                abort(404);
            }
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        if ($request->hasFile('foto')) {
            if ($data_item->foto != null) {
                Storage::disk('public')->delete($data_item->foto);
            }

            $data_item->foto = $request->file('foto')->store('items', 'public');
        }

        $data_item->save();

        $data_item->kategoriItems()->sync($request->kategori ?? []);

        return redirect('master-items');
    }

    public function delete($id)
    {
        $item = MasterItem::find($id);

        if ($item != null) {
            if ($item->foto != null) {
                Storage::disk('public')->delete($item->foto);
            }

            $item->delete();
        }

        return redirect('master-items');
    }

    public function exportExcel()
    {
        return Excel::download(new MasterItemsExport, 'master-items.xlsx');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }
}