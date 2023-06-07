<p>Dear {{$user}}</p>
<h2>You have Event's notes please check below notes details.</h2>

<table class="table">
   
    <tr>
        <td>Event's Notes :</td>
        <td>{{ $notes }}</td>
    </tr>
    <tr>
    <td>Date :</td>
    <td>{{ date('d-m-Y',strtotime($date)) }}</td>
    </tr>
    
</table>