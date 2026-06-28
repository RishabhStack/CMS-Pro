<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as IlluminateController;

class BaseController extends IlluminateController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function success(string $message = 'Success', $data = null, $meta = null, int $code = 200)
    {
        return responseSuccess($message, $data, $meta, $code);
    }

    protected function error(string $message = 'Error', $errors = null, int $code = 400)
    {
        return responseError($message, $errors, $code);
    }

    protected function created(string $message = 'Created successfully', $data = null)
    {
        return $this->success($message, $data, null, 201);
    }

    protected function updated(string $message = 'Updated successfully', $data = null)
    {
        return $this->success($message, $data);
    }

    protected function deleted(string $message = 'Deleted successfully')
    {
        return $this->success($message);
    }

    protected function view($view, $data = [], $mergeData = [])
    {
        return view($view, $data, $mergeData);
    }

    protected function getCompanyId(): ?int
    {
        return auth()->user()->company_id;
    }

    protected function datatableResponse($query, Request $request, ?int $recordsTotal = null)
    {
        $draw = $request->input('draw', 0);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 25);

        $columns = $request->input('columns', []);
        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $sortField = $columns[$orderColumnIndex]['name'] ?? null;

        if ($sortField && !str_contains($sortField, '.')) {
            $query->orderBy($sortField, $orderDir);
        }

        $data = $query->paginate($length, ['*'], 'page', (int) floor($start / max($length, 1)) + 1);

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $recordsTotal ?? (int) $data->total(),
            'recordsFiltered' => (int) $data->total(),
            'data' => $data->items(),
        ]);
    }
}
