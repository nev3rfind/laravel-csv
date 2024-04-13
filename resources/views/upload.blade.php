<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel CSV Upload</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Styles -->
    <style>
        body, html {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #333;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            width: 60%;
            max-width: 80%;
            margin: 10px;
        }
        .title {
            font-size: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group, .table-container {
            margin-bottom: 20px;
        }
        .form-control, .btn, .table {
            width: 100%;
        }
        .form-control {
            border: 1px solid #ccc;
            padding: 10px 0;
        }
        .btn {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .alert {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f2f2f2;
        }
        ul {
            list-style-type: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="title">Upload CSV File</div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" name="csv_file" class="form-control" required>
                </div>
                <button type="submit" class="btn">Upload</button>
            </form>
        </div>
        <div class="card table-container">
            <div class="title">Uploaded Files Status</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Date uploaded</th>
                        <th>Status</th>
                        <th>Error Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($csvImports as $import)
                    <tr>
                        <td>{{ $import->filename }}</td>
                        <td>{{ $import->created_at }}</td>
                        <td>{{ $import->status }}</td>
                        <td>{{ $import->error_message ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
