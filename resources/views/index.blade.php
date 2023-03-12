<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>
<style>
    .form-control[disabled],
    .form-control[readonly],
    fieldset[disabled] .form-control {
        background-color: white;
    }

    .tagItem {
        display: flex;
    }

    .tagItem li {
        list-style: none;
        border-radius: 5px;
        border: 1px solid #e3d1e1;
        padding: 5px 8px 5px 10px;
        background: #f2f2f2;
    }
</style>

<body>
    <h1>Tenant</h1>
    <div class="mb-3">
        <div class="d-flex flex-row mb-2">
            <div>
                <form id="chat-form" method="POST" action="/openai/chat">
                    @csrf
                    <label for="chat-log" class="form-label">Bio</label>
                    <a href="#" class="button" onclick="event.preventDefault(); $('#chat-form').submit();"
                        id="chat-button"><i class="bi bi-lightbulb"></i></a>
                </form>
            </div>
            <div>
                <input class="tagInput form-control" type="text" placeholder="Provide Keyword...">
            </div>
            <div>
                <ul class="tagItem"></ul>
            </div>
        </div>
        <textarea id="chat-log" class="form-control" rows="3" name="message" placeholder="Type your message..."></textarea>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let tags = [];

        function removeTag(element, tag) {
            let index = tags.indexOf(tag);
            tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
            element.parentElement.remove();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const tagInput = document.querySelector(".tagInput");
            const tagItem = document.querySelector(".tagItem");
            // countTag = document.querySelector("");

            function createTag() {
                tagItem.querySelectorAll("li").forEach(li => li.remove());
                tags.slice().reverse().forEach(tag => {
                    let liTag =
                        `<li>${tag}<i class="bi bi-x" onclick="removeTag(this, '${tag}')"></i></li>`;
                    tagItem.insertAdjacentHTML("afterbegin", liTag);
                });
            }

            function addTag(e) {
                if (e.key === "Enter") {
                    let tag = e.target.value.replace(/\s+/g, ' ');
                    if (tag.length > 1 && !tags.includes(tag)) {
                        tag.split(',').forEach(tag => {
                            tags.push(tag);
                            createTag();
                        });
                    }
                    e.target.value = "";
                }
            }

            tagInput.addEventListener("keyup", addTag);
        });
        
        $(document).ready(function() {
            $('#chat-form').on('submit', function(event) {
                event.preventDefault();
                var message;
                let keyword = tags.toString();
                if(keyword == '')
                {
                    message = 'Write me a maximum of 50 words of good tenant bio caption in my tenant resume to attract landlord, starts with i am ';
                }
                else
                {
                    message = 'Write me a minimum of 50 words of good tenant bio caption in my tenant resume to attract landlord (The tenant bio caption should start with i am meanwhile the sentences should including the following word [' + keyword + '])';
                }
                // var message = $('textarea[name="message"]').val();
                $('#chat-button').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                );
                $('#chat-log').prop('disabled', true);

                var interval;
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
                        var textarea = $('#chat-log');
                        $('#chat-log').val(data.message.trim());
                        $('#chat-button').prop('disabled', false).html(
                            '<i class="bi bi-lightbulb"></i>');
                        $('#chat-log').prop('disabled', false);
                    },
                    beforeSend: function() {
                        var message2 = 'Generating Bio Caption............';
                        var index = 0;
                        interval = setInterval(function() {
                            $('#chat-log').val(message2.substring(0, index++));
                            if (index > message2.length) {
                                index = 22;
                            }
                        }, 80);
                        $('#loading-spinner').show();

                    },
                    complete: function() {
                        clearInterval(interval);
                        $('#loading-spinner').hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);

                        var textarea = $('#chat-log');
                        $('#chat-log').val(data.message.trim());
                        $('#chat-button').prop('disabled', false).html(
                            '<i class="bi bi-lightbulb"></i>');
                        $('#chat-log').prop('disabled', false);
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
