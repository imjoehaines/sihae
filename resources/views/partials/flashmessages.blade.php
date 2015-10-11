@if (Session::has('flash-message'))
  <script>
    var type = '{{ Session::get("flash-message-type"), "info" }}'
    var message = '{{ Session::get("flash-message") }}'
    var config = { timeout: 5000 }

    Alert.alert(type, message, config)
  </script>
@endif
