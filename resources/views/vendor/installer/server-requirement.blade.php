@extends('installer::layouts.master')

@section('template_title')
    {{ trans('installer_messages.requirements.templateTitle')??'Server Requirements' }}
@endsection

@section('title')
    <i class="fa fa-list-ul fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.requirements.title')??'Server Requirement' }}
@endsection

@section('container')

    <H1>
        Permission Block
    </H1>

    @if(\Illuminate\Support\Facades\Session::has('error'))
        <h1 style="color: red">{{\Illuminate\Support\Facades\Session::get('error')}}</h1>
    @endif

    @foreach($permissionBlock as $permission)
        <table>
            <tr>
                <th>Name</th>
                <th>Message</th>
            </tr>
            <tr>
                <td>{{$permission['extensionName']}}</td>
                <td style="color: {{$permission['color']}}">{{$permission['message']}}</td>
            </tr>
        </table>
    @endforeach

    <H1>
        Requirement Block
    </H1>
    <!-- table Requirement Check block-->
    @foreach($requisites as $requisites)
        <table>
            <tr>
                <th>Requisites</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>{{$requisites['extensionName']}}</td>
                <td style="color: {{$requisites['color']}}">{{$requisites['connection']}}</td>
            </tr>
        </table>
    @endforeach


    <H1>
        Extension check Block
    </H1>

    @php
        $extColor = 'red';
                 $extString = 'Enabled';
                 $extraStringForRedisExtension ='ok' ?' style="pointer-events: none;color:#444;"' : ' target="_blank"';

    @endphp

    <!-- table PHP Extension Check block-->
    @foreach($phpExtension as $phpExtensions)
        <table>
            <tr>
                <th>Requisites</th>
                <th>Status</th>
            </tr>

            @php
                $extString = "Not Enabled<p>To enable this, please install the extension on your server and  update '".php_ini_loaded_file()."' to enable ".$phpExtensions["extensionName"]."</p>"
                                           .'<a href="https://support.faveohelpdesk.com/show/how-to-enable-required-php-extension-on-different-servers-for-faveo-installation"'.$extraStringForRedisExtension.'>How to install PHP extensions on my server?</a>';

                if($phpExtensions['key'] == 'required') {
                        $extColor = 'red';
                        $errorCount = $errorCount+1;

                    } elseif($phpExtensions['key'] == 'optional') {
                        $extColor = '#F89C0D';
                    } else {
                         $extColor = 'green';
                         $extString = 'Enabled';
                    }
            @endphp

            <tr>
                <td>{{$phpExtensions['extensionName']}}</td>
                <td style="color: {{$extColor}}">{{$extString}}</td>

            </tr>
        </table>
    @endforeach

    <h1>Rewrite Engine</h1>

    <table>
        <td>Mod Rewrite</td>
        <td>Status</td>
        </th>

        <tr>
            <td>Rewrite Engine</td>
            <td>{{$modRewrite['rewriteEngine']}}</td>
        </tr>

        <tr>
            <td>User Friendly URL</td>
            <td>{{$modRewrite['safeUrl']}}</td>
        </tr>
    </table>

    <form action="{{route('LaravelInstaller::license-agreement')}}" method="post">
        @csrf
        <input name="server_requirement_error" hidden value="{{$errorCount}}">
        <button class="btn" type="submit">Submit</button>
    </form>

@endsection
