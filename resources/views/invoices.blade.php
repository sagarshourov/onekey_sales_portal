<table>
    <thead>
    <tr>
        <th style="font-weiget: blod">Name</th>
        <th>Email</th>
        <th>Select </th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        <tr >
            <td style="background-color: #b4b7b7;" >{{ $invoice->first_name }}</td>
            <td style="background-color: #b4b7b7;">{{ $invoice->email }}</td>
            <td>
                <select>
            
                    <option> Select 1 </option>
                    <option> Select 2 </option>
              
                </select>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
