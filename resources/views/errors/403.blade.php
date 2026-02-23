@extends('errors::minimal')

@section('title', __('messages.forbidden'))
@section('code', '403')
@section('message', __('messages.access_denied'))