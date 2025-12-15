<!DOCTYPE html>
<html>
<head>
    <title><?= isset($page_title) ? $page_title : 'Employee Management System' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Global Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #003399;
            color: white;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar h2 {
            margin: 0;
            font-size: 20px;
        }

        .navbar-buttons {
            display: flex;
            gap: 10px;
        }

        .navbar-buttons button {
            background-color: white;
            color: #003399;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .navbar-buttons button:hover {
            background-color: #ddd;
        }

        /* Additional styles for forms */
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 1000px;
            width: 90%;
            margin: 40px auto;
            backdrop-filter: blur(10px);
        }

        h2.form-title {
            text-align: center;
            color: #2c3e50;
            font-size: 2.2em;
            margin-bottom: 30px;
            font-weight: 700;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-row {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            color: #34495e;
            font-size: 14px;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            padding: 12px 15px;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .submit-btn {
            align-self: center;
            width: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.6);
        }

        .errors {
            background: linear-gradient(45deg, #f8d7da, #f5c6cb);
            color: #721c24;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid #f5c6cb;
        }

        /* Main Container for lists */
        .container {
            max-width: 1400px;
            margin: 40px auto 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
            backdrop-filter: blur(10px);
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        h1 {
            color: #2c3e50;
            font-size: 2.3em;
            font-weight: 700;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .add-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .add-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .edit-btn {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
        }

        .edit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
        }

        .delete-btn {
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
        }

        .delete-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }

        /* Table */
        .table-container {
            overflow-x: auto;
            border-radius: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #e6e6e6;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Form Inputs in table */
        table input[type="text"],
        table input[type="email"],
        table input[type="date"],
        table select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.2s;
        }

        table select {
            background-color: #f5f5f5;
        }

        table input:focus, 
        table select:focus {
            outline: none;
            border-color: #764ba2;
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Status */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #d4edda;
            color: #155724;
        }

        /* No Data Row */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 16px;
        }

        .no-data a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        .no-data a:hover {
            text-decoration: underline;
        }

        /* Action Buttons */
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Welcome page specific styles */
        #container {
            margin: 10px;
            border: 1px solid #D0D0D0;
            box-shadow: 0 0 8px #D0D0D0;
            background: white;
        }

        code {
            font-family: Consolas, Monaco, Courier New, Courier, monospace;
            font-size: 12px;
            background-color: #f9f9f9;
            border: 1px solid #D0D0D0;
            color: #002166;
            display: block;
            margin: 14px 0 14px 0;
            padding: 12px 10px 12px 10px;
        }

        #body {
            margin: 0 15px 0 15px;
            min-height: 96px;
        }

        p {
            margin: 0 0 10px;
            padding:0;
        }

        p.footer {
            text-align: right;
            font-size: 11px;
            border-top: 1px solid #D0D0D0;
            line-height: 32px;
            padding: 0 10px 0 10px;
            margin: 20px 0 0 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .submit-btn {
                width: 100%;
            }

            h1 {
                font-size: 1.8em;
                text-align: center;
                width: 100%;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .actions {
                flex-direction: column;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>My App</h2>
    <div class="navbar-buttons">
        <button onclick="location.href='<?= base_url('welcome'); ?>'">Home</button>
        <button onclick="location.href='<?= base_url('employees'); ?>'">View Employees</button>
        <button onclick="location.href='<?= base_url('employees/add'); ?>'">Add Employee</button>
    </div>
</div>