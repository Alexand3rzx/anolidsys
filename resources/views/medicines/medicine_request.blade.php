<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicine Request - Health Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #8B0000;
            height: 100vh;
            padding: 20px;
            color: white;
        }
        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px 0;
            text-decoration: none;
        }
        .sidebar a.active {
            font-weight: bold;
            background-color: #B22222;
            padding-left: 10px;
            border-radius: 5px;
        }
        .main {
            flex: 1;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .topbar {
            background-color: #fff;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            height: 40px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        </div>
        <h2>Welcome, User</h2>
        <a href="/dashboard">Dashboard</a>
        <a href="#" class="active">Medicine Request</a>

        <form action="{{ route('logout') }}" method="POST" style="margin-top: 20px;">
            @csrf
            <button type="submit" class="btn btn-light w-100">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="topbar">
            <h3>Medicine Request</h3>
        </div>

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Quantity Available</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Static Sample Row -->
                <tr>
                    <td>Paracetamol</td>
                    <td>Tablet</td>
                    <td>50</td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#requestModal1">
                            Request
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="requestModal1" tabindex="-1">
            <div class="modal-dialog">
                <form action="#" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Request Paracetamol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="medicine_id" value="1">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" required min="1" max="50">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit Request</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
