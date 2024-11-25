@extends('layouts.app')

@section('title', __('Project overview'))

@section('body')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto py-6 space-y-4">
            <h1 class="text-3xl font-semibold text-center text-gray-800 mb-6">{{ __('Project overview') }}</h1>

            <livewire:search-component />

            <div>
                <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Search projects</a>
            </div>

            <livewire:package-list-component />

        </div>
    </div>
@endsection
