@extends('layouts.app')

@section('title', __('Project overview'))

@section('body')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto py-6">
            <h1 class="text-3xl font-semibold text-center text-gray-800 mb-6">{{ __('Project overview') }}</h1>

            <livewire:search-component />

            <livewire:project-list-component />
        </div>
    </div>
@endsection
