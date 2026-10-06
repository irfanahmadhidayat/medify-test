<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            order: [[0, 'desc']],
            ajax: {
                url: '{{url("kategori-items/search")}}',
                type: 'GET',
                data: function(d) {
                    d.kode = $('#filter-kode').val();
                    d.nama = $('#filter-nama').val();
                }
            },
            columns: [
                { data: 0 },
                { data: 1 },
                { data: 2, orderable: false }
            ]
        });
    });

    $('.btn-get-data').click(function() {
        $('#loading-filter').show();
        $('#table').DataTable().ajax.reload();
    });

    $('#table').on('xhr.dt', function() {
        $('#loading-filter').hide();
    });
</script>