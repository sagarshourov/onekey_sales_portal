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
        if ($arr->extra && count($arr->extra) > 0) {


        foreach($arr->extra as $index => $dat){
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
        @foreach($calls as $key=>$call)
        <tr>
            <td colspan="18" style="background-color: #FFFF00; height : 20px; padding:5em; text-align : center">
                {{get_title_id($section,$key)}}

            </td>
        </tr>
        @foreach($call as $call)
        <tr>
            <td>
                {{$call->first_name}}
                {{$call->last_name}}

            </td>

            <td>
                {{$call->email}}

            </td>
            <td>
                {{$call->priority}}

            </td>
            <td>
                {{$call->whatsapp}}

            </td>

            <td>
                {{$call->age}}

            </td>

            <td>

                {{ $call->call_schedule_date ? date('j F, Y', strtotime($call->call_schedule_date)) : "" }}



            </td>
            <td>
                {{$call->case_type==1?'F-1':'F-1/F2'}}

            </td>
            <td>



                {{ $call->first_contact ? date('j F, Y', strtotime($call->first_contact)) : "" }}

            </td>


            <td>


                {{$call->first_call_notes}}



            </td>

            <td>
                {{get_title_id($package,$call->package)}}

            </td>
            <td>
                {{$call->ag==0?'No':'Yes'}}

            </td>
            <td>
                {{$call->agreed_to_signed==0?'No':'Yes'}}

            </td>

            <td>{{get_title_id($status,$call->status)}}</td>

            <td> {{extra_title($call,'my_step',0)}}</td>

            <td></td>


            <td>


                {{ $call->follow_up_date ? date('j F, Y', strtotime($call->follow_up_date)) : "" }}

            </td>

            <td>
                {{$call->follow_up_notes}}

            </td>

            <td>
                {{$call->feedbacks}}
            </td>


        </tr>

        @endforeach

        @endforeach
    </tbody>
</table>