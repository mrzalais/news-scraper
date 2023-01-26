@extends('layouts.app')

@auth
    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-16">
                    <div class="card" style="overflow: scroll">
                        <datatable-component></datatable-component>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endauth
