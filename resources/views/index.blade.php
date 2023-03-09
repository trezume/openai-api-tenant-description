<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>

<body>
    <h1>Tenant</h1>
    <form id="chat-form" method="POST" action="/openai/chat">
        @csrf
        <div class="mb-3">
            <div class="row">
                <div class="col">
                    <label for="chat-log" class="form-label">Bio</label>
                    <a href="#" class="button" onclick="event.preventDefault(); $('#chat-form').submit();"><i
                        class="bi bi-lightbulb"></i></a>
                </div>
            </div>
            <textarea id="chat-log" class="form-control" rows="3" name="message" placeholder="Type your message..."></textarea>
        </div>
        {{-- <button class="btn btn-primary" type="submit">Send</button> --}}
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#chat-form').on('submit', function(event) {
                event.preventDefault();
                var message = $('textarea[name="message"]').val();
                $.ajax({
                    url: '/openai/chat',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        message: message
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#chat-log').val(data.message.trim());
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>
