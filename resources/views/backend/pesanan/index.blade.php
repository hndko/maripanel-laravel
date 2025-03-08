@extends('layouts.app-backend')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $pages }}</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">Form Pesanan</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" id="kategori" class="form-control select2">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category => $services)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Layanan</label>
                            <select name="layanan" id="layanan" class="form-control select2">
                                <option value="">Pilih Layanan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <div id="note" class="form-control bg-transparent p-3"
                                style="height: auto; min-height: 100px; border: 1px solid #ddd; border-radius: 4px;"></div>
                        </div>
                        <div class="form-group">
                            <label>Harga/K</label>
                            <input type="text" name="harga" id="harga" class="form-control bg-transparent"
                                placeholder="0" readonly>
                        </div>
                        <div class="form-group">
                            <label>Data/Target</label>
                            <input type="text" name="data_target" id="data_target" class="form-control"
                                placeholder="Link/Username">
                        </div>
                        <div class="form-group">
                            <label>Jumlah Pesanan</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="0"
                                min="0" max="0">
                            <small class="text-danger">Min: <span id="min">0</span>, Max: <span
                                    id="max">0</span></small>
                        </div>
                        <div class="form-group">
                            <label>Total Harga <sup><small class="text-danger">(otomatis)</small></sup></label>
                            <input type="text" name="total_harga" id="total_harga" class="form-control bg-transparent"
                                placeholder="0" readonly>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Buat Permintaan</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2();

            // Event ketika kategori dipilih
            $('#kategori').on('change', function() {
                let category = $(this).val();
                let services = @json($categories);

                // Kosongkan dropdown layanan
                $('#layanan').empty().append('<option value="">Pilih Layanan</option>');

                if (category && services[category]) {
                    // Tambahkan layanan berdasarkan kategori yang dipilih
                    services[category].forEach(service => {
                        $('#layanan').append(
                            `<option value="${service.id}" data-price="${service.price}" data-min="${service.min}" data-max="${service.max}" data-note="${service.note}">${service.name}</option>`
                        );
                    });
                }
            });

            // Event ketika layanan dipilih
            $('#layanan').on('change', function() {
                let selectedOption = $(this).find(':selected');
                let price = selectedOption.data('price');
                let min = selectedOption.data('min');
                let max = selectedOption.data('max');
                let note = selectedOption.data('note');

                // Update nilai form
                $('#harga').val(price);
                $('#min').text(min);
                $('#max').text(max);
                $('#jumlah').attr('min', min).attr('max', max);
                $('#note').html(note); // Gunakan .html() untuk menampilkan konten HTML
            });

            // Event ketika jumlah pesanan diubah
            $('#jumlah').on('input', function() {
                let jumlah = $(this).val();
                let harga = $('#harga').val();

                if (jumlah && harga) {
                    let totalHarga = jumlah * harga;
                    $('#total_harga').val(totalHarga);
                } else {
                    $('#total_harga').val(0);
                }
            });
        });
    </script>
@endsection
