@push('css')
    <!-- Jquery UI -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/jquery-ui.min.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
    <!-- Validasi -->
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script src="{{ asset('js/localization/messages_id.js') }}"></script>
    <script>

        $('#tambahDaftarAnjungan').on('click', function(e) {
            // Mencegah form submit secara default
            e.preventDefault();

            // Satima: hanya UUID yang dibuat/dipakai di browser ini (data-baru) yang menandai browser ini sebagai anjungan,
            // supaya menyimpan ubahan dari komputer admin tidak menjadikan komputer itu anjungan.
            let field = $('#anjungan_id');
            let uuid = field.val();
            if (uuid && field.attr('data-baru') === '1') {
                try {
                    localStorage.setItem('anjungan_uuid', uuid);
                } catch (e) {}
                document.cookie = 'anjungan_uuid=' + encodeURIComponent(uuid) + '; path=/; max-age=315360000; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
            }
            // Submit form secara manual setelah menyimpan ke localStorage
            $('#validasi').submit();
        });

        function is_form_valid(form_id) {
            form_id = form_id.startsWith('#') ? form_id : '#' + form_id;
            let validate = $(form_id).validate();
            if (validate.errorList.length > 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        html: validate.errorList[0].message,
                    })
                } else {
                    alert(validate.errorList[0].message);
                }
                validate.errorList[0].element.focus();
                return false;
            }
            return true;
        }

        $.fn.select2.defaults.set("language", {
            inputTooShort: function(args) {
                return "Masukkan minimal " + args.minimum + " karakter";
            },
            inputTooLong: function(args) {
                return "Masukkan maksimal " + args.maximum + " karakter";
            },
            noResults: function() {
                return "Tidak ada hasil yang ditemukan";
            },
            searching: function() {
                return "Mencari...";
            },
            loadingMore: function() {
                return "Memuat lebih banyak hasil...";
            },
            escapeMarkup: function(markup) {
                return markup;
            }
        });
    </script>
@endpush
