@if($errors->any())
<div id="blade-errors" class="hidden"
     data-errors="{{ json_encode($errors->all()) }}"></div>
@endif

@if (session()->hasAny(['success', 'error', 'warning', 'info', 'swal']))
<script>
document.addEventListener('DOMContentLoaded', function () {

    @if (session('success'))
        SwalHelper.toast('success', @json(session('success')));
    @endif

    @if (session('error'))
        SwalHelper.toast('error', @json(session('error')));
    @endif

    @if (session('warning'))
        SwalHelper.toast('warning', @json(session('warning')));
    @endif

    @if (session('info'))
        SwalHelper.toast('info', @json(session('info')));
    @endif

    @if (session('swal'))
        @php $swal = session('swal'); @endphp
        SwalHelper.fire({
            icon:  @json($swal['icon']  ?? 'info'),
            title: @json($swal['title'] ?? ''),
            text:  @json($swal['text']  ?? ''),
        });
    @endif

});
</script>
@endif