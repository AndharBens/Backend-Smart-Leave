<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Leave Management')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --primary: #1e3a5f;
            --primary-dark: #0f2744;
            --secondary: #64748b;
            --accent: #3b82f6;
            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
            --light: #f8fafc;
            --border: #e2e8f0;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        .bg-primary { background-color: var(--primary); }
        .bg-primary-dark { background-color: var(--primary-dark); }
        .text-primary { color: var(--primary); }
        .border-primary { border-color: var(--primary); }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
        }

        .btn-secondary {
            background-color: white;
            color: var(--primary);
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        .btn-secondary:hover {
            background-color: var(--light);
            border-color: var(--primary);
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }
        .input-field:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--secondary);
            transition: all 0.2s ease;
            border-radius: 6px;
            margin-bottom: 0.25rem;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: rgba(30, 58, 95, 0.08);
            color: var(--primary);
        }
        .sidebar-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        /* Style form button to look like a link */
        .sidebar-link button {
            background: none;
            border: none;
            cursor: pointer;
            font: inherit;
            color: inherit;
            width: 100%;
            text-align: left;
            padding: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #d1fae5; color: #065f46; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background-color: var(--light);
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--secondary);
            border-bottom: 1px solid var(--border);
        }
        .data-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
        }
        .data-table tr:hover {
            background-color: var(--light);
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1.5rem;
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50">
    @if(auth()->check())
        <div class="flex min-h-screen">
            <aside class="w-64 bg-white border-r border-gray-200 fixed h-full">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-primary">Smart Leave</h1>
                            <p class="text-xs text-gray-500">Management System</p>
                        </div>
                    </div>
                </div>

                <nav class="p-4">
                    @if(auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                        <a href="{{ route('manager.dashboard') }}" class="sidebar-link {{ request()->routeIs('manager.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Manager Dashboard</span>
                        </a>
                        <a href="{{ route('manager.pending') }}" class="sidebar-link {{ request()->routeIs('manager.pending') ? 'active' : '' }}">
                            <i class="fas fa-clock"></i>
                            <span>Pending Requests</span>
                        </a>
                        <a href="{{ route('manager.history') }}" class="sidebar-link {{ request()->routeIs('manager.history') ? 'active' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Approval History</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('leave.create') }}" class="sidebar-link {{ request()->routeIs('leave.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>
                            <span>New Request</span>
                        </a>
                        <a href="{{ route('leave.my-requests') }}" class="sidebar-link {{ request()->routeIs('leave.my-requests') ? 'active' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>My Requests</span>
                        </a>
                    @endif

                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <a href="{{ route('profile') }}" class="sidebar-link">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="sidebar-link text-red-600 hover:text-red-700 w-full text-left">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            <main class="flex-1 ml-64">
                <header class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-primary">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-sm text-gray-500">@yield('page-subtitle', 'Welcome back')</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button class="relative p-2 text-gray-500 hover:text-primary transition">
                                <i class="fas fa-bell"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div class="flex items-center space-x-3 pl-4 border-l border-gray-200">
                                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    @else
        @yield('content')
    @endif

    @stack('scripts')
</body>
</html>
