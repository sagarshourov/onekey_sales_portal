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

if(!function_exists('extra_title')){

function extra_title($arr, $group, $index) {
$value = "";
if (isset($arr->extra) && count($arr->extra) > 0) {


foreach($arr->extra as $ind => $dat){


if ($dat->groups == $group && $dat->values[$index]->value) {
$value = $dat->values[$index]->value;
}

}



}

if ($index === 0 && $value != "") {
return date('j F, Y', strtotime($value));
}


return $value;
}

}

@endphp



<table border="1">
    <thead>
        <tr>
            <th style="background-color:  #333cff; color:white;">Client</th>
            <th style="background-color:  #333cff; color:white;">Email</th>



            <th style="background-color:  #333cff; color:white;">Cancel Date</th>

            <th style="background-color:  #333cff; color:white;">Reason</th>

            <th style="background-color:  #333cff; color:white;">Assigned To </th>

            <th style="background-color:  #333cff; color:white;">Priority</th>

            <th style="background-color:  #333cff; color:white;">WhatsApp</th>
            <th style="background-color:  #333cff; color:white;">Age</th>
            <th style="background-color:  #333cff; color:white;">Call Schedule Date</th>
            <th style="background-color:  #333cff; color:white;">Case Type</th>
            <th style="background-color:  #333cff; color:white;"> First Call Date</th>
            <th style="background-color:  #333cff; color:white;">First Call Note</th>
            <th style="background-color:  #333cff; color:white;">Package</th>
            <th style="background-color:  #333cff; color:white;">Agreement Sent</th>
            <th style="background-color:  #333cff; color:white;">Agreement Signed</th>
            <th style="background-color:  #333cff; color:white;">Status</th>
            <th style="background-color:  #333cff; color:white;"> Next Step Date</th>

            <th style="background-color:  #333cff; color:white;"> Next Step Note</th>
            <th style="background-color:  #333cff; color:white;"> Follow up date</th>

            <th style="background-color:  #333cff; color:white;"> Follow up note</th>
            <th style="background-color:  #333cff; color:white;"> Feedback</th>






        </tr>
    </thead>
    <tbody>
        @foreach($calls as $key => $cal)
        <tr>
            <td colspan="18" style="background-color: #FFFF00; height : 20px; padding:5em; text-align : center">
                {{get_title_id($section,$key)}}

            </td>
        </tr>
        @foreach($cal as $call)
        <tr>
            <td>
                @isset($call->first_name)
                {{$call->first_name}}
                @endisset
                @isset($call->last_name)
                {{$call->last_name}}
                @endisset
            </td>

            <td>


                @isset($call->email)
                {{ $call->email }}
                @endisset

            </td>

            <td>
                @isset($call->cancel_date)
                {{ $call->cancel_date }}
                @endisset

            </td>
            <td>
                @isset($call->cancelReason)
                {{ $call->cancelReason->title }}
                @endisset

            </td>
            <td>
                @isset($call->assignedTo)
                {{ $call->assignedTo->first_name }}
                {{ $call->assignedTo->last_name }}
                @endisset

            </td>


            <td>
                @isset($call->priority)
                {{ $call->priority }}
                @endisset

            </td>
            <td>


                @isset($call->whatsapp)
                {{ $call->whatsapp }}
                @endisset

            </td>

            <td>


                @isset($call->age)
                {{ $call->age }}
                @endisset

            </td>

            <td>
                @isset($call->call_schedule_date)
                {{ $call->call_schedule_date ? date('j F, Y', strtotime($call->call_schedule_date)) : "" }}

                @endisset

            </td>
            <td>
                @isset($call->case_type)
                {{$call->case_type==1?'F-1':'F-1/F2'}}
                @endisset

            </td>
            <td>

                @isset($call->first_contact)
                {{ $call->first_contact ? date('j F, Y', strtotime($call->first_contact)) : "" }}
                @endisset



            </td>


            <td>




                @isset($call->first_call_notes)
                {{ $call->first_call_notes }}
                @endisset



            </td>

            <td>
                @isset($call->package)
                {{get_title_id($package,$call->package)}}
                @endisset



            </td>
            <td>
                @isset($call->ag)
                {{$call->ag==0?'No':'Yes'}}
                @endisset

            </td>
            <td>
                @isset($call->agreed_to_signed)
                {{$call->agreed_to_signed==0?'No':'Yes'}}
                @endisset

            </td>

            <td>

                @isset($call->status)
                {{get_title_id($status,$call->status)}}
                @endisset


            </td>

            <td> {{extra_title($call,'my_step',0)}}</td>

            <td>{{extra_title($call,'my_step',1)}}</td>


            <td>

                @isset($call->follow_up_date)
                {{ $call->follow_up_date ? date('j F, Y', strtotime($call->follow_up_date)) : "" }}

                @endisset

            </td>

            <td>


                @isset($call->follow_up_notes)
                {{ $call->follow_up_notes }}
                @endisset

            </td>

            <td>


                @isset($call->feedbacks)
                {{ $call->feedbacks }}
                @endisset
            </td>


        </tr>

        @endforeach

        @endforeach
    </tbody>
</table>