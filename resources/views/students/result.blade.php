@extends('layouts.star')

@section('title', 'My Result')

@section('content')

<h1>My Result</h1>

<div class="row">
 
    <div class="col-md-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $attendanceSummary['present'] }}</h3>
                <p>Present Days</p>
            </div>
            <div class="icon">
                <i class="fa fa-check-circle"></i>
            </div>
        </div>
    </div>
 
    <div class="col-md-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $attendanceSummary['absent'] }}</h3>
                <p>Absent Days</p>
            </div>
            <div class="icon">
                <i class="fa fa-times-circle"></i>
            </div>
        </div>
    </div>
 
    <div class="col-md-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $attendanceSummary['leave'] }}</h3>
                <p>Leave Days</p>
            </div>
            <div class="icon">
                <i class="fa fa-calendar-times"></i>
            </div>
        </div>
    </div>
 
    <div class="col-md-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $overallPercentage }}%</h3>
                <p>Overall Percentage</p>
            </div>
            <div class="icon">
                <i class="fa fa-award"></i>
            </div>
        </div>
    </div>
 
</div>
 
<div class="card">
 
    <div class="card-header">
        <h3 class="card-title">Subject Wise Marks</h3>
    </div>
 
    <div class="card-body">
 
        <table class="table table-bordered">
 
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Obtained Marks</th>
                    <th>Total Marks</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                </tr>
            </thead>
 
            <tbody>
 
            @forelse($marks as $mark)
 
                <tr>
                    <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                    <td>{{ $mark->marks }}</td>
                    <td>{{ $mark->total_marks }}</td>
                    <td>{{ round(($mark->marks / $mark->total_marks) * 100, 2) }}%</td>
                    <td>
                        <span class="badge
                            @if($mark->grade == 'F') badge-danger
                            @elseif(in_array($mark->grade, ['A+', 'A'])) badge-success
                            @else badge-warning
                            @endif
                        ">
                            {{ $mark->grade }}
                        </span>
                    </td>
                </tr>
 
            @empty
 
                <tr>
                    <td colspan="5" class="text-center">
                        No marks have been added yet.
                    </td>
                </tr>
 
            @endforelse
 
            </tbody>
 
            @if($marks->isNotEmpty())
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th>{{ $totalObtained }}</th>
                    <th>{{ $totalMax }}</th>
                    <th>{{ $overallPercentage }}%</th>
                    <th></th>
                </tr>
            </tfoot>
            @endif
 
        </table>
 
    </div>
 
</div>

@endsection
