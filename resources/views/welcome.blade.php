@extends('layouts.app')

@section('content')
    <div class="row justify-content-center align-items-center">
        <div class="col-12">
            <div id="swagger-ui"></div>
            <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-bundle.js" crossorigin></script>
            <script>
                window.onload = () => {
                    window.ui = SwaggerUIBundle({
                        url: "/json/swagger.json",
                        dom_id: '#swagger-ui',
                    });
                };
            </script>
        </div>
    </div>

@endsection