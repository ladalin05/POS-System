<?php

namespace App\Http\Controllers;

abstract class Controller
{
    
    protected function successResponse($message = null, $data = null, $code = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse($message = null, $code = 400)
    {
        $code = (is_int($code) && $code >= 100 && $code < 600) ? $code : 500;

        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'data'    => null,
        ], $code);
    }

    protected function modalResponse($title, $view, $data, $action = '', $modal = 'action-modal')
    {
        return response()->json([
            'title'   => $title,
            'status'  => 'success',
            'message' => 'success',
            'html'    => view($view, array_merge($data, compact('title', 'action')))->render(),
            'modal'   => $modal,
        ]);
    }

    protected function redirectResponse($message, $route, $code = 200)
    {
        return response()->json([
            'status'   => 'success',
            'message'  => $message,
            'redirect' => $route,
        ], $code);
    }

    protected function viewResponse($view, $action, array $data = [])
    {
        return view($view, array_merge($data, compact('action')));
    }
    
}
