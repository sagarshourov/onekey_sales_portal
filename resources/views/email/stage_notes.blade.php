<p>Dear {{$user}}</p>
<h2>You have notes on stage please check below details and update according that.</h2>
@php
$n_stage=$note_stage;
@endphp
<table class="table">
    <tr>
        <td>Stage :</td>
        <td>{{ $stage[$n_stage] }}</td>
    </tr>
    <tr>
        <td>Notes :</td>
        <td>{{ $notes }}</td>
    </tr>
</table>