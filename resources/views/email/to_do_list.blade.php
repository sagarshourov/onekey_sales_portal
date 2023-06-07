<p>Dear {{$user}}</p>
<h2>You have notes that has been added by your consultant. Please log in to the portal, check the To Do list section and update accordingly </h2>
<table class="table">
    <tr>
        <td>Stage :</td>
        <td>{{ $stage_name[$stage] }}</td>
    </tr>
    <tr>
        <td>Event's Notes :</td>
        <td>{{ $notes }}</td>
    </tr>
    
</table>