<h2>{{$form}} has submited</h2>
<table class="table">
    <tr>
        <td>Name :</td>
        <td>{{ $user->first_name }}</td>
    </tr>
    <tr>
        <td>Email :</td>
        <td>{{ $user->email }}</td>
    </tr>
</table>