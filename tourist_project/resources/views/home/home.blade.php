@extends('layouts.master')
@section('title', 'Trang chủ')
@section('content')
@include('home.components.destination')
@include('home.components.tour')
@include('home.components.bookingtour')
@endsection
