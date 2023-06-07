@php 

if(!function_exists('get_title_id')){


function get_title_id($data,$id){



    foreach($data as $array){
        if ($array['id'] == $id)
            return $array['title'];
    }
    return '';

}
}

@endphp



<table border="1">
    <thead>
        <tr>
            <th style="background-color:  #333cff; color:white;">First Name</th>
            <th style="background-color:  #333cff; color:white;">Last Name</th>
            <th style="background-color:  #333cff; color:white;">Phone</th>

            <th style="background-color:  #333cff; color:white;">Email</th>
            <th style="background-color:  #333cff; color:white;">Note</th>
            <th style="background-color:  #333cff; color:white;">AG</th>
            <th style="background-color:  #333cff; color:white;">Status</th>
            <th style="background-color:  #333cff; color:white;">Package</th>
            <th style="background-color:  #333cff; color:white;">Last Contact</th>
            <th style="background-color:  #333cff; color:white;">Age</th>
            <th style="background-color:  #333cff; color:white;">GPA</th>
            <th style="background-color:  #333cff; color:white;">Last Status</th>
            <th style="background-color:  #333cff; color:white;">Feedback</th>

        </tr>
    </thead>
    <tbody>
        @foreach($calls as $key=>$call)
        <tr>
            <td colspan="13" style="background-color: #FFFF00; height : 20px; padding:5em; text-align : center">
            {{get_title_id($section,$key)}}

            </td>
        </tr>
        @foreach($call as $call)
        <tr>
            <td>
                {{$call->first_name}}

            </td>
            <td>
                {{$call->last_name}}

            </td>
            <td>
                {{$call->phone_number}}

            </td>
            <td>
                {{$call->email}}

            </td>
            <td>
                {{$call->note}}

            </td>
            <td>
                {{$call->ag==0?'FALSE':'TRUE'}}

            </td>
            <td>
            
                {{get_title_id($status,$call->status)}}
            </td>


            <td>
                {{get_title_id($package,$call->package)}}



            </td>
            <td>
                {{$call->last_contact}}

            </td>

            <td>
                {{$call->age}}

            </td>


            <td>
                {{$call->gpa}}

            </td>

            <td>

                {{$call->last_status_notes}}
            </td>
            <td>
                {{$call->feedbacks}}
            </td>


        </tr>

        @endforeach

        @endforeach
    </tbody>
</table>