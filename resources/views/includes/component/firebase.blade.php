<script>
	var user_id = "{{ Auth::user()->pengguna_nip }}";
    
    var database = firebase.database();

    if({!! Auth::user() !!}) {
        var session_id = "{!! Session::getId() !!}";
        firebase.database().ref('/pengguna/' + user_id + '/session_id').set(session_id);
    }

    firebase.database().ref('/pengguna/' + user_id).on('value', function(snapshot) {
        var pengguna = snapshot.val();
        if(pengguna.session_id != "{{ Session::getId() }}") {
            $.gritter.add({
                title: 'Logged Out',
                text: 'Akun anda sudah melakukan login di device lainnya',
                sticky: true,
                time: 3000,
                class_name: 'my-sticky-class'
            });
            setTimeout(function() {
                window.location = '/logout';
            }, 4000);
        }
    });
</script>