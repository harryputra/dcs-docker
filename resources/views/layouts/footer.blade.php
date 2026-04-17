<!-- jQuery & Bootstrap -->
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

<!-- Additional Libraries -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

<!-- jQuery (CDN & Local) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<!-- Select2 Global JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Universal Dropdown Modernizer Script -->
<script>
    $(document).ready(function() {
        // Function to initialize select2 with premium theme
        function initiateModernSelects() {
            $('.form-select, select:not(.no-select2)').each(function() {
                const $el = $(this);
                // Avoid double initialization
                if ($el.hasClass('select2-hidden-accessible')) return;

                const placeholder = $el.attr('placeholder') || $el.find('option:first').text() || 'Pilih Opsi...';
                
                $el.select2({
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%',
                    // Ensure it works inside modals
                    dropdownParent: $el.closest('.modal').length ? $el.closest('.modal-content') : $(document.body)
                });
            });
        }

        // Run on load
        initiateModernSelects();

        // Initialize Bootstrap Data Tooltips Globally
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Re-run when Bootstrap modals are shown (to fix parent issues)
        $(document).on('shown.bs.modal', function() {
            initiateModernSelects();
        });
    });
</script>

<!-- Dropdown Notification -->
<script src="{{ asset('assets/js/dropdownNotification.js') }}"></script>

<!-- Inisialisasi DataTable -->
<script>
    $(document).ready(function() {
        // Generic DataTable initialization removed to favor page-specific premium configurations
    });
</script>

<!-- Custom flash modal & toast (replaces SweetAlert) -->

<!-- Flash Modal -->
<div class="modal fade" id="flashModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="flashModalHeader">
                <h5 class="modal-title" id="flashModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="flashModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="top-0 p-3 position-fixed end-0" style="z-index: 1080">
    <div id="flashToast" class="text-white border-0 toast align-items-center bg-success" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="flashToastBody"></div>
            <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        function showFlashModal(title, body, type) {
            var header = $('#flashModalHeader');
            header.removeClass('bg-success bg-danger bg-warning bg-info text-white');
            if (type === 'success') header.addClass('bg-success text-white');
            else if (type === 'error') header.addClass('bg-danger text-white');
            else if (type === 'warning') header.addClass('bg-warning');
            else header.addClass('bg-info text-white');

            $('#flashModalTitle').text(title);
            $('#flashModalBody').html(body);
            var modal = new bootstrap.Modal(document.getElementById('flashModal'));
            modal.show();
        }

        function showFlashToast(body, type, timeout = 3000) {
            var toastEl = $('#flashToast');
            var toastBody = $('#flashToastBody');
            toastBody.html(body);
            toastEl.removeClass('bg-success bg-danger bg-warning bg-info');
            if (type === 'success') toastEl.addClass('bg-success');
            else if (type === 'error') toastEl.addClass('bg-danger');
            else if (type === 'warning') toastEl.addClass('bg-warning');
            else toastEl.addClass('bg-info');

            var toast = new bootstrap.Toast(document.getElementById('flashToast'));
            toast.show();
            if (timeout > 0) setTimeout(function() {
                toast.hide();
            }, timeout);
        }

        // Show flash messages from session
        @if (session('success'))
            showFlashToast("{{ session('success') }}", 'success', 3500);
        @endif

        @if (session('error'))
            showFlashModal('Gagal!', "{{ session('error') }}", 'error');
        @endif

        @if ($errors->any())
            var html = '<ul class="mb-0 text-start">';
            @foreach ($errors->all() as $error)
                html += '<li>{{ $error }}</li>';
            @endforeach
            html += '</ul>';
            showFlashModal('Terdapat Kesalahan!', html, 'error');
        @endif

        // Keep existing cancel/back protection behaviour (adapted)
        $(document).on('click', '.btn-danger', function(e) {
            if ($(this).is('a')) {
                return true;
            }

            var buttonText = $(this).text().toLowerCase();
            if (buttonText.includes('kembali') || buttonText.includes('cancel') || buttonText.includes(
                    'batal')) {
                e.preventDefault();
                e.stopPropagation();
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    var currentPath = window.location.pathname;
                    if (currentPath.includes('document')) {
                        window.location.href = '{{ route('document_revision.index') }}';
                    } else if (currentPath.includes('categor')) {
                        window.location.href = '{{ route('categories.index') }}';
                    } else {
                        window.location.href = '/dashboard';
                    }
                }
                return false;
            }
        });

        $(document).on('keydown', '.btn-danger', function(e) {
            if (e.which === 13 || e.which === 32) {
                var buttonText = $(this).text().toLowerCase();
                if (buttonText.includes('kembali') || buttonText.includes('cancel') || buttonText
                    .includes('batal')) {
                    e.preventDefault();
                    $(this).click();
                    return false;
                }
            }
        });
        // Modern Logout Handler
        window.handleLogout = function(event) {
            if(event) event.preventDefault();
            document.getElementById('logout-form').submit();
        };
    });
</script>

@yield('customJS')
</body>

</html>
