<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <option @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <option @if($selected == 'Umum') selected @endif>Umum</option>
            <option @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kategori</label>
        @if($semuaKategori->isNotEmpty())
        <div class="border rounded p-2" style="max-height: 180px; overflow-y: auto;">
            @foreach($semuaKategori as $kategori)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="kategori[]" value="{{$kategori->id}}" id="kategori-{{$kategori->id}}" @if(in_array($kategori->id, $kategoriTerpilih)) checked @endif>
                <label class="form-check-label" for="kategori-{{$kategori->id}}">
                    {{$kategori->nama}} ({{$kategori->kode}})
                </label>
            </div>
            @endforeach
        </div>
        @else
        <p class="form-text text-muted mb-2">Belum ada kategori. Tambahkan kategori terlebih dahulu.</p>
        @endif
        <small class="form-text text-muted">Centang untuk memilih kategori — bisa lebih dari satu tanpa menahan tombol Ctrl.</small>
    </div>

    <div class="form-group">
        <label>Foto</label>
        <div class="foto-uploader">
            <input type="file" class="d-none" id="inputFoto" name="foto" accept="image/*" onchange="previewFoto(event)">
            <div class="foto-preview" id="fotoPreview" onclick="document.getElementById('inputFoto').click()">
                @if(isset($item->foto) && $item->foto != '')
                <img src="{{asset('storage/'.$item->foto)}}" alt="Foto Item">
                @else
                <div class="foto-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.059L.002 10.462V3a1 1 0 0 1 1-1h12z"/>
                    </svg>
                    <span>Klik untuk memilih foto</span>
                    <small>jpeg, png, jpg, gif, svg · maks 2MB</small>
                </div>
                @endif
            </div>
            <div class="d-flex gap-2 justify-content-center mt-2">
                <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('inputFoto').click()">Pilih Foto</button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="resetFoto()">Hapus</button>
            </div>
        </div>
        <style>
            .foto-uploader { max-width: 320px; }
            .foto-preview {
                width: 100%;
                height: 220px;
                border: 2px dashed #adb5bd;
                border-radius: 8px;
                background: #f8f9fa;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: border-color .2s, background .2s;
            }
            .foto-preview:hover { border-color: #0d6efd; background: #e9ecef; }
            .foto-preview img { width: 100%; height: 100%; object-fit: cover; }
            .foto-placeholder { text-align: center; color: #6c757d; padding: 1rem; }
            .foto-placeholder span { display: block; margin-top: .5rem; font-weight: 500; }
            .foto-placeholder small { display: block; margin-top: .25rem; }
        </style>
    </div>

    <script>
        function previewFoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar.');
                event.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('fotoPreview').innerHTML =
                    '<img src="' + e.target.result + '" alt="Foto Item">';
            };
            reader.readAsDataURL(file);
        }

        function resetFoto() {
            document.getElementById('inputFoto').value = '';
            document.getElementById('fotoPreview').innerHTML =
                '<div class="foto-placeholder">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">' +
                '<path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>' +
                '<path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.059L.002 10.462V3a1 1 0 0 1 1-1h12z"/>' +
                '</svg>' +
                '<span>Klik untuk memilih foto</span>' +
                '<small>jpeg, png, jpg, gif, svg · maks 2MB</small>' +
                '</div>';
        }
    </script>

    <button class="btn btn-primary mt-3">Submit</button>

</form>