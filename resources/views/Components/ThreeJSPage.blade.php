@extends('index')

@section('page')
    <div id="three-js-page">
        <div class="particle-container">
            <canvas id="canvas"></canvas>
            <script type="x-shader/x-vertex" id="vertexshader">
                attribute float scale;
                void main() {
                    vec4 mvPosition = modelViewMatrix * vec4( position, 1.0 );
                    gl_PointSize = ((scale * 0.5 * ( 100.0 / - mvPosition.z)) + 1.0) * 0.9;
                    gl_Position = projectionMatrix * mvPosition;
                }
            </script>
            <script type="x-shader/x-fragment" id="fragmentshader">
                void main() {
                    // https://airtightinteractive.com/util/hex-to-glsl/
                    vec3 color = vec3(0.,0.694,0.91);
                    if ( length( gl_PointCoord - vec2( 0.5, 0.5 ) ) > 0.475 ) discard;
                    gl_FragColor = vec4( color, 1.0 );
                }
            </script>
            <div id="overlay">
                @yield('page-content')
            </div>
        </div>
        <script src="{{ asset('/js/components/ThreeJsBackground.js') }}"></script>
    </div>
@endsection
