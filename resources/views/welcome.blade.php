@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        	<h4>Products</h4>
            <div class="panel panel-default">
                <div class="panel-heading">Get a list of the products</div>
                <div class="panel-body">
                    GET /api/v1/products
                </div>
                <div class="panel-heading">Create product</div>
                <div class="panel-body">
                    POST /api/v1/products
                </div>
                <div class="panel-heading">Edit product</div>
                <div class="panel-body">
                    PATCH /api/v1/products/{sku}
                </div>    
                <div class="panel-heading">View product</div>
                <div class="panel-body">
                    GET /api/v1/products/{sku}
                </div>
                <div class="panel-heading">Delete product</div>
                <div class="panel-body">
                    DELETE /api/v1/products/{sku}
                </div>                                               
            </div>        
        	<h4>Channels</h4>
            <div class="panel panel-default">
                <div class="panel-heading">Get a list of the channels</div>
                <div class="panel-body">
                    GET /api/v1/channels
                </div>
                <div class="panel-heading">Create channel</div>
                <div class="panel-body">
                    POST /api/v1/channels
                </div>
                <div class="panel-heading">Edit channel</div>
                <div class="panel-body">
                    PATCH /api/v1/channels/{id}
                </div>    
                <div class="panel-heading">View channel</div>
                <div class="panel-body">
                    GET /api/v1/channels/{id}
                </div>
                <div class="panel-heading">Delete channel</div>
                <div class="panel-body">
                    DELETE /api/v1/channels/{id}
                </div>                                               
            </div>
        </div>
    </div>
</div>
@endsection
