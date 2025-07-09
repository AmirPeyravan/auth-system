@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h4 class="mb-4 text-center">گزارش لاگ کدهای ورود</h4>

        <table class="table table-bordered table-striped table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>شماره موبایل</th>
                    <th>کد</th>
                    <th>ارسال شده در</th>
                    <th>انقضا</th>
                    <th>استفاده شده؟</th>
                    <th>منقضی شده؟</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $i => $log)
                    <tr>
                        <td>{{ $i + 1 + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                        <td>{{ $log->user->phone }}</td>
                        <td>{{ $log->code }}</td>
                        <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $log->expired_at->format('H:i:s') }}</td>
                        <td>
                            <span class="badge bg-{{ $log->used ? 'success' : 'secondary' }}">
                                {{ $log->used ? 'بله' : 'خیر' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $log->expired_at->isPast() ? 'danger' : 'info' }}">
                                {{ $log->expired_at->isPast() ? 'بله' : 'خیر' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
