<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>THC</title>
</head>
<body>
  <script>
  window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'vapidPublicKey' => config('webpush.vapid.public_key'),
            'pusher' => [
                'key' => config('broadcasting.connections.pusher.key'),
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            ],
        ]) !!};
  </script>
  <div id="app">
  </div>

  <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
