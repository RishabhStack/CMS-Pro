/**
 * HRMS - Global Application Helper
 * Single source of truth for all frontend operations
 */
window.App = {
    config: {
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        baseUrl: window.location.origin,
    },

    init() {
        this.setupAjax();
        this.setupTheme();
        this.setupNotifications();
        this.setupSidebar();
        this.setupPasswordToggle();
        this.initTooltips();
        this.initSelect2();
        this.initFlatpickr();
        this.setupAutoCloseAlerts();
    },

    setupAjax() {
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = this.config.csrfToken;
        axios.defaults.headers.common['Accept'] = 'application/json';

        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response) {
                    if (error.response.status === 401) {
                        window.location.href = '/login';
                    }
                    if (error.response.status === 419) {
                        window.location.reload();
                    }
                    if (error.response.status === 403) {
                        this.error(error.response.data.message || 'You do not have permission.');
                    }
                }
                return Promise.reject(error);
            }
        );
    },

    // ========== MODAL SYSTEM ==========
    modal(config) {
        const defaults = {
            title: 'Modal',
            size: 'md',
            url: null,
            method: 'GET',
            formData: null,
            callback: null,
            onClose: null,
        };
        const options = { ...defaults, ...config };

        const dialog = document.getElementById('globalModalDialog');
        const label = document.getElementById('globalModalLabel');
        const body = document.getElementById('globalModalBody');

        label.textContent = options.title;
        dialog.className = 'modal-dialog modal-dialog-centered modal-dialog-scrollable';

        const sizeMap = { sm: 'modal-sm', md: '', lg: 'modal-lg', xl: 'modal-xl', fullscreen: 'modal-fullscreen' };
        if (sizeMap[options.size]) {
            dialog.classList.add(sizeMap[options.size]);
        }

        const modal = new bootstrap.Modal('#globalModal');
        body.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        modal.show();

        if (options.url) {
            const requestConfig = {
                method: options.method || 'GET',
                url: options.url,
            };
            if (options.formData) {
                requestConfig.data = options.formData;
            }

            axios(requestConfig)
                .then(response => {
                    body.innerHTML = response.data;
                    this.initSelect2();
                    this.initFlatpickr();
                    if (options.callback) {
                        options.callback(response.data);
                    }
                })
                .catch(error => {
                    body.innerHTML = `
                        <div class="alert alert-danger m-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Failed to load content. Please try again.
                        </div>
                    `;
                });
        }

        document.getElementById('globalModal').addEventListener('hidden.bs.modal', function () {
            if (options.onClose) options.onClose();
            body.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
        }, { once: true });
    },

    closeModal() {
        const modal = bootstrap.Modal.getInstance('#globalModal');
        if (modal) modal.hide();
    },

    // ========== AJAX ==========
    ajax(config) {
        const defaults = {
            method: 'GET',
            url: '',
            data: null,
            success: null,
            error: null,
            complete: null,
            button: null,
        };
        const options = { ...defaults, ...config };

        if (options.button) {
            this.button.loading(options.button);
        }

        axios({
            method: options.method,
            url: options.url,
            data: options.data,
        })
            .then(response => {
                if (options.success) options.success(response.data);
                if (options.button) this.button.success(options.button);
            })
            .catch(error => {
                if (options.error) {
                    options.error(error.response?.data);
                } else {
                    const msg = error.response?.data?.message || 'An error occurred';
                    this.error(msg);
                }
                if (options.button) this.button.restore(options.button);
            })
            .finally(() => {
                if (options.complete) options.complete();
            });
    },

    // ========== CONFIRMATION ==========
    confirm(config) {
        const defaults = {
            title: 'Are you sure?',
            text: 'You will not be able to revert this!',
            icon: 'warning',
            confirmText: 'Yes, proceed!',
            cancelText: 'Cancel',
            confirmColor: '#dc3545',
            callback: null,
        };
        const options = { ...defaults, ...config };

        Swal.fire({
            title: options.title,
            text: options.text,
            icon: options.icon,
            showCancelButton: true,
            confirmButtonColor: options.confirmColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: options.confirmText,
            cancelButtonText: options.cancelText,
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed && options.callback) {
                options.callback();
            }
        });
    },

    // ========== TOAST NOTIFICATIONS ==========
    toast(message, type = 'success', title = '') {
        const config = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000,
            extendedTimeOut: 1000,
        };

        switch (type) {
            case 'success': toastr.success(message, title || 'Success', config); break;
            case 'error': toastr.error(message, title || 'Error', config); break;
            case 'warning': toastr.warning(message, title || 'Warning', config); break;
            case 'info': toastr.info(message, title || 'Info', config); break;
        }
    },

    success(message) {
        this.toast(message, 'success');
    },

    error(message) {
        this.toast(message, 'error');
    },

    warning(message) {
        this.toast(message, 'warning');
    },

    info(message) {
        this.toast(message, 'info');
    },

    // ========== LOADER ==========
    loader(show = true) {
        const existing = document.getElementById('globalLoader');
        if (show) {
            if (!existing) {
                const div = document.createElement('div');
                div.id = 'globalLoader';
                div.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
                div.style.cssText = 'background: rgba(0,0,0,0.3); z-index: 9999;';
                div.innerHTML = '<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>';
                document.body.appendChild(div);
            }
        } else {
            if (existing) existing.remove();
        }
    },

    // ========== DATA TABLE ==========
    datatable(tableId, options = {}) {
        const defaults = {
            processing: true,
            serverSide: true,
            ajax: {
                url: '',
                data: function (d) {
                    d.search = d.search?.value || '';
                    if (options.filters) {
                        Object.keys(options.filters).forEach(key => {
                            d[key] = typeof options.filters[key] === 'function'
                                ? options.filters[key]()
                                : options.filters[key];
                        });
                    }
                },
            },
            columns: [],
            order: [[0, 'desc']],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            responsive: true,
            stateSave: true,
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            buttons: [
                {
                    text: '<i class="bi bi-arrow-clockwise"></i>',
                    action: function (e, dt) {
                        dt.ajax.reload();
                    },
                    className: 'btn btn-outline-secondary btn-sm',
                },
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i>',
                    className: 'btn btn-outline-success btn-sm',
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf"></i>',
                    className: 'btn btn-outline-danger btn-sm',
                },
                {
                    extend: 'colvis',
                    text: '<i class="bi bi-eye"></i>',
                    className: 'btn btn-outline-secondary btn-sm',
                },
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search...',
                lengthMenu: '_MENU_ records per page',
                zeroRecords: 'No records found',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries available',
                infoFiltered: '(filtered from _MAX_ total entries)',
            },
        };

        const config = $.extend(true, {}, defaults, options);
        return $(tableId).DataTable(config);
    },

    reloadTable(tableId) {
        const table = $(tableId).DataTable();
        if (table) table.ajax.reload(null, false);
    },

    // ========== BUTTON ==========
    button: {
        loading(btn) {
            const $btn = $(btn);
            $btn.data('original-text', $btn.html());
            $btn.data('original-disabled', $btn.prop('disabled'));
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
        },

        success(btn) {
            const $btn = $(btn);
            $btn.html('<i class="bi bi-check-lg me-1"></i>Saved');
            setTimeout(() => {
                $btn.prop('disabled', false);
                $btn.html($btn.data('original-text') || 'Submit');
            }, 2000);
        },

        restore(btn) {
            const $btn = $(btn);
            $btn.prop('disabled', false);
            $btn.html($btn.data('original-text') || 'Submit');
        },

        disable(btn) {
            $(btn).prop('disabled', true).addClass('disabled');
        },

        enable(btn) {
            $(btn).prop('disabled', false).removeClass('disabled');
        },
    },

    // ========== FORM HANDLER ==========
    form(formId, options = {}) {
        const $form = $(formId);
        const defaults = {
            success: null,
            error: null,
            complete: null,
            resetOnSuccess: false,
        };
        const opts = { ...defaults, ...options };

        $form.off('submit').on('submit', function (e) {
            e.preventDefault();
            const $this = $(this);
            const submitBtn = $this.find('[type="submit"]');
            const hasFiles = $this.find('[type="file"]').length > 0;
            const data = hasFiles ? new FormData(this) : new URLSearchParams($this.serialize());

            $this.find('.invalid-feedback').remove();
            $this.find('.is-invalid').removeClass('is-invalid');

            App.button.loading(submitBtn);

            axios({
                method: 'POST',
                url: $this.attr('action'),
                data: data,
            })
                .then(response => {
                    App.button.success(submitBtn);
                    App.success(response.data.message || 'Operation completed successfully.');

                    if (opts.success) opts.success(response.data);
                    if (opts.resetOnSuccess) $this[0].reset();
                })
                .catch(error => {
                    App.button.restore(submitBtn);
                    const data = error.response?.data;

                    if (data?.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = $this.find(`[name="${field}"]`);
                            const errorMsg = data.errors[field][0];
                            input.addClass('is-invalid');
                            input.after(`<div class="invalid-feedback d-block">${errorMsg}</div>`);
                        });

                        const firstError = $this.find('.is-invalid').first();
                        if (firstError.length) {
                            firstError.focus();
                            firstError[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } else {
                        App.error(data?.message || 'An error occurred.');
                    }

                    if (opts.error) opts.error(data);
                })
                .finally(() => {
                    if (opts.complete) opts.complete();
                });
        });
    },

    // ========== SELECT2 ==========
    initSelect2(selector = '.select2') {
        $(selector).each(function () {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(this).closest('.modal') || $(document.body),
                placeholder: $(this).data('placeholder') || 'Select an option',
                allowClear: $(this).data('allow-clear') !== false,
            });
        });
    },

    // ========== FLATPICKR ==========
    initFlatpickr(selector = '.datepicker') {
        $(selector).each(function () {
            if (this._flatpickr) {
                this._flatpickr.destroy();
            }
            flatpickr(this, {
                dateFormat: 'Y-m-d',
                allowInput: true,
                animate: true,
            });
        });

        $('.datetimepicker').each(function () {
            if (this._flatpickr) {
                this._flatpickr.destroy();
            }
            flatpickr(this, {
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                allowInput: true,
                animate: true,
            });
        });
    },

    // ========== PASSWORD TOGGLE ==========
    setupPasswordToggle() {
        $(document).on('click', '.toggle-password', function () {
            const target = $(this).data('target');
            const input = $(target);
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });
    },

    // ========== THEME ==========
    setupTheme() {
        const themeToggle = document.getElementById('darkModeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const html = document.documentElement;
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);

                axios.post('/settings/theme', { theme: newTheme })
                    .catch(() => {});
            });
        }

    },

    // ========== NOTIFICATIONS ==========
    setupNotifications() {
        const bell = document.getElementById('notificationBell');
        if (bell) {
            bell.addEventListener('click', () => {
                App.toast('No new notifications', 'info');
            });
        }
    },

    // ========== SIDEBAR ==========
    setupSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', (e) => {
                e.preventDefault();
                if (window.innerWidth < 992) {
                    sidebar.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                }
            });
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
            } else {
                sidebar.classList.remove('collapsed');
            }
        });
    },

    // ========== TOOLTIPS ==========
    initTooltips() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltips.length) {
            [...tooltips].forEach(el => new bootstrap.Tooltip(el));
        }
    },

    // ========== COPY TO CLIPBOARD ==========
    copy(text) {
        navigator.clipboard.writeText(text).then(() => {
            this.toast('Copied to clipboard!', 'success');
        }).catch(() => {
            this.toast('Failed to copy', 'error');
        });
    },

    // ========== DATE/TIME HELPERS ==========
    date(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    },

    time(timeStr) {
        if (!timeStr) return '-';
        const [hours, minutes] = timeStr.split(':');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const h = hours % 12 || 12;
        return `${h}:${minutes} ${ampm}`;
    },

    currency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(amount || 0);
    },

    number(num) {
        return new Intl.NumberFormat('en-US').format(num || 0);
    },

    // ========== PREVIEW ==========
    preview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById(input.dataset.preview);
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    },

    // ========== DOWNLOAD ==========
    download(url, filename = 'download') {
        axios({
            url: url,
            method: 'GET',
            responseType: 'blob',
        }).then(response => {
            const blob = new Blob([response.data]);
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;
            link.click();
            URL.revokeObjectURL(link.href);
        }).catch(() => {
            this.error('Download failed');
        });
    },

    // ========== AUTO CLOSE ALERTS ==========
    setupAutoCloseAlerts() {
        document.querySelectorAll('.alert-auto-close').forEach(alert => {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    },

    // ========== VALIDATE ==========
    validate(input) {
        const isValid = input.checkValidity();
        if (!isValid) {
            input.classList.add('is-invalid');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = input.validationMessage;
            }
            return false;
        }
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        return true;
    },

    // ========== RESET FORM ==========
    reset(formId) {
        $(formId)[0].reset();
        $(formId).find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $(formId).find('.invalid-feedback').remove();
    },
};

// Initialize on document ready
$(document).ready(function () {
    App.init();
});
