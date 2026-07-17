@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>School Management Dashboard</h1>
@stop

@section('content')
    <div class="alert alert-success">
        AdminLTE Theme Working Successfully 🎉
    </div>

    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>0</h3>
                    <p>Total Students</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>0</h3>
                    <p>Total Teachers</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>0</h3>
                    <p>Total Classes</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>0</h3>
                    <p>Total Subjects</p>
                </div>
            </div>
        </div>

    </div>
@stop