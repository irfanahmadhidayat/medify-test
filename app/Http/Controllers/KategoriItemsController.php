<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriItemRequest;
use App\Models\KategoriItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KategoriItemsController extends Controller
{
    public function index()
    {
        return view('kategori_items.index.index');
    }

    public function search(Request $request)
    {
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $orderDir = $request->input('order.0.dir', 'asc');

        $query = KategoriItem::query();

        if (!empty($request->kode)) {
            $query->where('kode', $request->kode);
        }
        if (!empty($request->nama)) {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        $recordsTotal = KategoriItem::count();
        $recordsFiltered = (clone $query)->count();

        $sortColumns = [
            0 => 'kode',
            1 => 'nama',
        ];
        $orderColumn = (int) $request->input('order.0.column', 0);
        $query->orderBy($sortColumns[$orderColumn] ?? 'id', $orderDir === 'desc' ? 'desc' : 'asc');

        $items = $query->offset($start)->limit($length)->get();

        $data = $items->map(function ($item) {
            $view = '<a href="' . e(url('kategori-items/view/' . $item->kode)) . '" class="btn btn-primary">View</a>';

            return [
                e($item->kode),
                e($item->nama),
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
            $item = KategoriItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori_items.form.index', $data);
    }

    public function formSubmit(KategoriItemRequest $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new KategoriItem;
            $kode = KategoriItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
        } else {
            $data_item = KategoriItem::find($id);
            if ($data_item == null) {
                abort(404);
            }
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->kode = $kode;

        $data_item->save();

        return redirect('kategori-items');
    }

    public function singleView($kode)
    {
        $data['data'] = KategoriItem::where('kode', $kode)->with('masterItems')->first();
        return view('kategori_items.single.index', $data);
    }

    public function printPdf($kode)
    {
        $data['data'] = KategoriItem::where('kode', $kode)->with('masterItems')->first();

        $pdf = Pdf::loadView('kategori_items.print.index', $data);
        return $pdf->download('kategori-' . $data['data']->kode . '.pdf');
    }

    public function delete($id)
    {
        $item = KategoriItem::find($id);

        if ($item != null) {
            $item->masterItems()->detach();
            $item->delete();
        }

        return redirect('kategori-items');
    }
}