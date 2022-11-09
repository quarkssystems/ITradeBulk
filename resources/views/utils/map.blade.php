<cfoutput>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('app.map_api_key')}}&libraries=places"></script>
</cfoutput>
<script src="{{asset('assets/plugins/google-map/map.js')}}"></script>
<link type="text/css" href="{{asset('assets/plugins/google-map/map.css')}}" rel="stylesheet">