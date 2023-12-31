<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
{{--    <link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
{{--    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> --}}
{{--    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300&family=Ubuntu:wght@300&display=swap" rel="stylesheet">--}}


</head>
<body>
    <div id="app">

    <b-navbar>
        <template #brand>
            <b-navbar-item>
                <img
                    src=""
                    alt=""
                >
            </b-navbar-item>
        </template>
        <template #start>
            @php
                $role = auth()->user()->role;
            @endphp

            @if($role === 'ADMINISTRATOR')
                <b-navbar-item href="/dashboard">
                    Home
                </b-navbar-item>

                <b-navbar-dropdown label="Setting">
                    <b-navbar-item href="/academic-years">
                        Academic Years
                    </b-navbar-item>

                    <b-navbar-item href="/questions">
                        Questions
                    </b-navbar-item>
                    <b-navbar-item href="/departments">
                        Departments
                    </b-navbar-item>
                    <b-navbar-item href="/event-types">
                        Event Types
                    </b-navbar-item>
                    <b-navbar-item href="/event-venues">
                        Facilities/Equipments
                    </b-navbar-item>
                </b-navbar-dropdown>
            
            
                <!-- <b-navbar-dropdown label="Events">
                    <b-navbar-item href="/events">
                        Events
                    </b-navbar-item>
                </b-navbar-dropdown> -->
                <b-navbar-item href="/events">
                    Events
                </b-navbar-item>

                
                <b-navbar-dropdown label="Evaluation">
                    <b-navbar-item href="/evaluations">
                        Evaluations
                    </b-navbar-item>
                    <b-navbar-item href="/student-evaluated">
                        Participant Evaluated
                    </b-navbar-item>
                </b-navbar-dropdown>
               

                <b-navbar-dropdown label="Report">
                    <b-navbar-item href="/report-event-list">
                        Report Event List
                    </b-navbar-item>
                    <b-navbar-item href="/report-event-attendances">
                        Report Attendance
                    </b-navbar-item>
                </b-navbar-dropdown>


                <b-navbar-item href="/users">
                    User
                </b-navbar-item>

            @else
                <b-navbar-item href="/dashboard">
                        Home
                </b-navbar-item>

                <b-navbar-dropdown label="Setting">
                    <b-navbar-item href="/academic-years">
                        Academic Years
                    </b-navbar-item>
                   
                </b-navbar-dropdown>

                <b-navbar-item href="/events">
                    Events
                </b-navbar-item>

                <!-- <b-navbar-dropdown label="Events">
                    <b-navbar-item href="/events">
                        Events
                    </b-navbar-item>
                </b-navbar-dropdown> -->

                
                <b-navbar-dropdown label="Evaluation">
                    <b-navbar-item href="/evaluations">
                        Evaluations
                    </b-navbar-item>
                    <b-navbar-item href="/student-evaluated">
                        Participant Evaluated
                    </b-navbar-item>
                </b-navbar-dropdown>
                <b-navbar-dropdown label="Report">
                    <b-navbar-item href="/report-event-list">
                        Report Event List
                    </b-navbar-item>
                    <b-navbar-item href="/report-event-attendances">
                        Report Attendance
                    </b-navbar-item>
                </b-navbar-dropdown>
             
            @endif
           
        </template>

        <template #end>
            <b-navbar-item href="#">
                {{ strtoupper(Auth::user()->fname) }}
            </b-navbar-item>
            <b-navbar-item tag="div">
                <div class="buttons">
                    <b-button
                        onclick="document.getElementById('logout').submit()"
                        icon-left="logout"
                        class="button is-danger" outlined>
                    </b-button>
                </div>
            </b-navbar-item>
        </template>
    </b-navbar>

    <form action="/logout" method="post" id="logout">
        @csrf
    </form>

    <div>
        @yield('content')
    </div>


    </div>
</body>
</html>
