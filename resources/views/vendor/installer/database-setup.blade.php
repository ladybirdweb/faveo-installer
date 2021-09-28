@extends('installer::layouts.master')

@section('template_title')
    {{ trans('installer_messages.requirements.templateTitle')??'Server Requirements' }}
@endsection

@section('title')
    <i class="fa fa-list-ul fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.requirements.title')??'Server Requirement' }}
@endsection

@section('container')

    <h1>Database Setup</h1>

    @if(\Illuminate\Support\Facades\Session::has('error'))
        <p style="color: red">{{\Illuminate\Support\Facades\Session::get('error')}}</p>
    @endif

    <form action="{{route('LaravelInstaller::environment')}}" method="post">
        @csrf
        <input type="text" name="database_hostname" placeholder="Host">
        <input type="text" name="database_name" placeholder="DB Name">
        <input type="text" name="database_username" placeholder="username">
        <input type="text" name="database_password" placeholder="password">
        <input type="text" name="database_port" placeholder="port_number">
        <br>
        <br>
        <label>TimeZone</label>
        <select name="timezone">
            @foreach($timezone as $zone)
                <option value="{{$zone['zone']}}">{{$zone['GMT_difference'].' '.$zone['zone']}}</option>
            @endforeach
        </select>
        <br>
        <br>
        <label>Language</label>
        <?php
        $path = base_path('resources/lang');
        $values = scandir($path);
        $values = array_slice($values, 2);
        ?>

        <select name="language" data-placeholder="Choose a timezone..." class="chosen-select" style="width:295px;">
            @foreach($values as $value)
                @if(!empty(Config::get('language.'.$value)))
                    <option value="{!! $value !!}"
                            @if($value=="en") selected @endif>{!! Config::get('language.'.$value)[0]??'' !!}
                        &nbsp;({!! Config::get('language.'.$value)[1]??'' !!})
                    </option>
                @endif
            @endforeach
        </select>
        <br>
        <br>

        <label>Environment</label>
        <select name="environment" data-placeholder="{{ trans('lang.select-environment')}}"
                class="chosen-select" style="width:295px;" tabindex="2">
            <option selected="true"
                    value="production">{{ trans('lang.production') }}</option>
            {{--            @if(strpos(config('app.version'), "Enterprise") !== false)--}}
            <option value="development">{{ trans('lang.development') }}</option>
            <option value="testing">{{ trans('lang.testing') }}</option>
            {{--            @endif--}}
        </select>
        <br>
        <br>

        <?php
        $disks = [];
        $allDisks = config('filesystems.disks');
        foreach ($allDisks as $diskKey => $disk) {
            if (in_array($diskKey, ['private', 'rackspace', 'local', 'public'])) {
                //above check is not required once s3 and other disks are supported
                continue;
            }
            $disks[] = ['id' => $diskKey, 'name' => ucfirst($diskKey)];
        }
        ?>

        <label>Driver</label>
        <select required id="driverSelect" name="driver"
                data-placeholder="{{ trans('lang.probe_storage_driver_placeholder')}}"
                class="chosen-select pnChosen" style="width:295px;" tabindex="2">
            <option value="system">System</option>
            @foreach($disks as $disk)
                <option value="{!! $disk['id'] !!}"
                        @if ($disk['id'] == 'system') selected @endif >{!! $disk['name'] !!}</option>
            @endforeach
        </select>

        <br>
        <br>
        <h3>Required if AWS Driver is S3</h3>
        <br>
        <br>

        <label for="box4">{{trans('lang.probe_aws_access_key_id')}} <span style="color
                                    : red;font-size:12px;">*</span>
        </label>

        <input name="aws_access_key_id" placeholder="AWS Access Key Id">

        <label for="box4">{{trans('lang.probe_aws_access_key')}} <span style="color
                                    : red;font-size:12px;">*</span>
        </label>

        <input name="aws_access_key" placeholder="AWS Access Key ">

        <label for="box4">{{trans('lang.probe_aws_default_region')}} <span style="color
                                    : red;font-size:12px;">*</span>
        </label>

        <input name="aws_default_region" placeholder="AWS Default Region">

        <label for="box4">{{trans('lang.probe_aws_bucket')}} <span style="color
                                    : red;font-size:12px;">*</span>
        </label>

        <input name="aws_bucket" placeholder="AWS Bucket">

        <label for="box4">{{trans('lang.probe_aws_endpoint')}} <span style="color
                                    : red;font-size:12px;">*</span>
        </label>

        <input name="aws_endpoint" placeholder="AWS Endpoint">

        <br>
        <br>
        <button class="btn" type="submit">Submit</button>
    </form>

@endsection
