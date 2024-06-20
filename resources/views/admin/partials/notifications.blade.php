<script type="text/javascript">
    $(document).ready(function () {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "preventDuplicates": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        // handle validation messages
        @if($errors->any())
            var msg = '';
        @foreach ($errors->all() as $error)
            msg = msg + "- {{ $error }} <br/>";
        @endforeach
            toastr.error(msg, "Error");
        @endif

        // handle status messages
        @if(session('status'))
        toastr.info("{{ session('status') }}", "Info")
        @endif

        // handle flash messages
        @foreach (session('flash_notification', collect())->toArray() as $message)
            toastr["{{ $message['level'] }}"]("{!! $message['message'] !!}", "{{ ucfirst($message['level']) }}");
        @endforeach

        {{ session()->forget('flash_notification') }}
    });
</script>